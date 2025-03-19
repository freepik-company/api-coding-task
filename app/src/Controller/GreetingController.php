<?php

namespace App\Controller;

use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GreetingController
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo = new PDO('mysql:host=db;dbname=lotr', 'root', 'root');
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $query = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id');
        $query->execute(['id' => $args['id']]);
        $character = $query->fetch();

        if (!$character) {
            $response->getBody()->write("Character not found");
            return $response->withStatus(404);
        }

        $response->getBody()->write("Hello, " . $character['name']);
        return $response;
    }
}