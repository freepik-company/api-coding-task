<?php

namespace App\Controller\Read;

use App\Model\Faction;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReadFactionsController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $faction = new Faction($this->pdo);
            $factions = $faction->findAll();
            
            $response->getBody()->write(json_encode([
                'factions' => array_map(function($faction) {
                    return $faction->toArray();
                }, $factions)
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error al obtener las facciones',
                'message' => $e->getMessage()
            ]));
            
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
} 