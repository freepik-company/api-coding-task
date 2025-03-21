<?php

namespace App\Controller\Read;

use App\Model\Character;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReadCharacterByIdController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = $args['id'] ?? null;

        if (!$id) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'ID is required',
                'data' => null
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $character = new Character($this->db);
        $character = $character->find((int)$id);

        if (!$character) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 404,
                'message' => 'Character not found',
                'data' => null
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => 'Character retrieved successfully',
            'data' => $character->toArray()
        ], JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json');
    }
} 