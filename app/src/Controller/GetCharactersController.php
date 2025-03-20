<?php

namespace App\Controller;

use App\Model\Character;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class GetCharactersController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $character = new Character($this->db);
            $characters = $character->findAll();
            
            $response->getBody()->write(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Characters retrieved successfully',
                'data' => $characters
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error retrieving characters: ' . $e->getMessage(),
                'data' => null
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
} 