<?php

namespace App\Tests\Controller;

use App\Controller\ProductsController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testمنتجات extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new ProductsController($this->pdoMock);
    }

    public function testGetProducts()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM products')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getProducts();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateProduct()
    {
        $product = [
            'name' => 'Product 1',
            'price' => 10.99,
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO products (name, price) VALUES (:name, :price)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createProduct($product);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateProduct()
    {
        $product = [
            'id' => 1,
            'name' => 'Product 1',
            'price' => 10.99,
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE products SET name = :name, price = :price WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateProduct($product);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteProduct()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM products WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteProduct($id);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}



// ProductsController.php

namespace App\Controller;

use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProducts()
    {
        $stmt = $this->pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();
        return new Response(json_encode($products), 200);
    }

    public function createProduct(array $product)
    {
        $stmt = $this->pdo->prepare('INSERT INTO products (name, price) VALUES (:name, :price)');
        $stmt->execute($product);
        return new Response('', 201);
    }

    public function updateProduct(array $product)
    {
        $stmt = $this->pdo->prepare('UPDATE products SET name = :name, price = :price WHERE id = :id');
        $stmt->execute($product);
        return new Response('', 200);
    }

    public function deleteProduct(int $id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return new Response('', 200);
    }
}