<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use App\Controller\TeslemController;
use App\Repository\TeslemRepository;
use App\Entity\Teslem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestTeslem extends TestCase
{
    private $controller;
    private $router;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TeslemRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new TeslemController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetTeslems()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Teslem('id1', 'name1'),
                new Teslem('id2', 'name2'),
            ]);

        $response = $this->controller->getTeslems();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostTeslem()
    {
        $teslem = new Teslem('id', 'name');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($teslem);

        $request = new Request([], [], ['_method' => 'POST'], json_encode(['id' => 'id', 'name' => 'name']));
        $response = $this->controller->postTeslem($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutTeslem()
    {
        $teslem = new Teslem('id', 'name');
        $this->repository->expects($this->once())
            ->method('find')
            ->with('id')
            ->willReturn($teslem);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($teslem);

        $request = new Request([], [], ['_method' => 'PUT'], json_encode(['id' => 'id', 'name' => 'name']));
        $response = $this->controller->putTeslem($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteTeslem()
    {
        $teslem = new Teslem('id', 'name');
        $this->repository->expects($this->once())
            ->method('find')
            ->with('id')
            ->willReturn($teslem);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($teslem);

        $request = new Request([], [], ['_method' => 'DELETE']);
        $response = $this->controller->deleteTeslem($request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// TeslemController.php
namespace App\Controller;

use App\Repository\TeslemRepository;
use App\Entity\Teslem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TeslemController
{
    private $repository;
    private $entityManager;
    private $router;

    public function __construct(TeslemRepository $repository, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function getTeslems()
    {
        $teslems = $this->repository->findAll();
        return new Response(json_encode($teslems), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function postTeslem(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $teslem = new Teslem($data['id'], $data['name']);
        $this->repository->save($teslem);
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function putTeslem(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $teslem = $this->repository->find($data['id']);
        $teslem->setName($data['name']);
        $this->repository->save($teslem);
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function deleteTeslem(Request $request)
    {
        $id = $request->get('id');
        $teslem = $this->repository->find($id);
        $this->repository->remove($teslem);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// TeslemRepository.php
namespace App\Repository;

use App\Entity\Teslem;
use Doctrine\ORM\EntityRepository;

class TeslemRepository extends EntityRepository
{
    public function save(Teslem $teslem)
    {
        $this->getEntityManager()->persist($teslem);
        $this->getEntityManager()->flush();
    }

    public function find($id)
    {
        return $this->find($id);
    }

    public function remove(Teslem $teslem)
    {
        $this->getEntityManager()->remove($teslem);
        $this->getEntityManager()->flush();
    }
}



// Teslem.php
namespace App\Entity;

class Teslem
{
    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}