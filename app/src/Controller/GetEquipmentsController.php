<?php

namespace App\Controller;

use App\Model\Equipment;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class GetEquipmentsController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response)
    {
        $equipments = Equipment::getAll($this->db);

        $response->getBody()->write(json_encode([
            'equipments' => $equipments
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
} 