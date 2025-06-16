<?php

namespace App\Controller;

use App\Model\Character;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCharactersController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        // Validate required fields
        $requiredFields = ['name', 'birth_date', 'kingdom', 'equipment_id', 'faction_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $response->getBody()->write(json_encode([
                    'error' => "Missing required field: {$field}"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
        
        try {
            // Create a new Character instance
            $character = new Character($this->pdo);
            $character->setName($data['name'])
                      ->setBirthDate($data['birth_date'])
                      ->setKingdom($data['kingdom'])
                      ->setEquipmentId((int) $data['equipment_id'])
                      ->setFactionId((int) $data['faction_id']);
            
            // Save the character to the database
            $result = $character->save();
            
            if (!$result) {
                throw new \Exception('Failed to save character');
            }
            
            // Return success response
            $response->getBody()->write(json_encode([
                'id' => $character->getId(),
                'message' => 'Character created successfully'
            ]));
            
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Failed to create character',
                'message' => $e->getMessage()
            ]));
            
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
