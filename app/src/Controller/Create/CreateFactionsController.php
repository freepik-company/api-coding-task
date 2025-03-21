<?php

namespace App\Controller\Create;

use App\Model\Faction;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateFactionsController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // Get JSON content from request body
        $data = $request->getParsedBody();

        // Validate JSON content
        if (empty($data)) {
            $response->getBody()->write(json_encode([
                'error' => 'Request body must be valid JSON'
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Validate required fields
        $requiredFields = ['faction_name', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $response->getBody()->write(json_encode([
                    'error' => "Missing required field: {$field}"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
        
        try {
            // Check if faction already exists
            $faction = new Faction($this->pdo);
            $existingFaction = $faction->findByName($data['faction_name']);
            
            if ($existingFaction) {
                $response->getBody()->write(json_encode([
                    'error' => 'A faction with this name already exists',
                    'existing_faction' => $existingFaction->toArray()
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(409);
            }
            
            // Create a new Faction instance
            $faction->setFactionName($data['faction_name'])
                   ->setDescription($data['description']);
            
            // Save the faction to the database
            $result = $faction->save();
            
            if (!$result) {
                throw new \Exception('Failed to save faction');
            }
            
            // Return success response
            $response->getBody()->write(json_encode([
                'id' => $faction->getId(),
                'message' => 'Faction created successfully'
            ]));
            
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Failed to create faction',
                'message' => $e->getMessage()
            ]));
            
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
