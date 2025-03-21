<?php

namespace App\Controller\Read;

use App\Model\Character;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class ReadCharactersController
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
            
            // Convertir cada objeto Character a array
            $charactersArray = array_map(function($character) {
                return $character->toArray();
            }, $characters);
            
            $response->getBody()->write(json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Characters retrieved successfully',
                'data' => $charactersArray
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