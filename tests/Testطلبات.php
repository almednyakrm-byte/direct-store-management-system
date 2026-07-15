<?php

namespace App\Tests\Controller;

use App\Controller\DemandesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestDemandesController extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new DemandesController($this->pdoMock);
    }

    public function testGetAllDemandes(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM demandes')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAllDemandes();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateDemande(): void
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"nom": "test", "prenom": "test"}');
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO demandes (nom, prenom) VALUES (:nom, :prenom)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createDemande($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateDemande(): void
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"nom": "test", "prenom": "test"}');
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE demandes SET nom = :nom, prenom = :prenom WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateDemande(1, $request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteDemande(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM demandes WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteDemande(1);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


Note: This test file assumes that the `DemandesController` class has the following methods: `getAllDemandes`, `createDemande`, `updateDemande`, and `deleteDemande`. The actual implementation of these methods is not shown here.