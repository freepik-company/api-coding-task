<?php

namespace App\Controller\Create;

use App\Model\Character;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class CreateCharactersController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            // Validar campos requeridos
            $requiredFields = ['name', 'birth_date', 'kingdom', 'equipment_id', 'faction_id'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    $response->getBody()->write(json_encode([
                        'status' => 'error',
                        'code' => 400,
                        'message' => "Missing required field: {$field}",
                        'data' => $data
                    ], JSON_PRETTY_PRINT));
                    
                    return $response
                        ->withHeader('Content-Type', 'application/json')
                        ->withStatus(400);
                }
            }

            // Verificar si ya existe un personaje con el mismo nombre
            $existingCharacter = Character::findByName($this->db, $data['name']);
            if ($existingCharacter) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'code' => 409,
                    'message' => 'A character with this name already exists',
                    'data' => [
                        'existing_character' => $existingCharacter->toArray()
                    ]
                ], JSON_PRETTY_PRINT));
                
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(409);
            }

            // Crear nuevo personaje
            $character = new Character($this->db);
            $character->setName($data['name'])
                     ->setBirthDate($data['birth_date'])
                     ->setKingdom($data['kingdom'])
                     ->setEquipmentId($data['equipment_id'])
                     ->setFactionId($data['faction_id']);

            if (!$character->save()) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Error creating character',
                    'data' => null
                ], JSON_PRETTY_PRINT));
                
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(500);
            }

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'code' => 201,
                'message' => 'Character created successfully',
                'data' => $character->toArray()
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'Error creating character: ' . $e->getMessage(),
                'data' => null
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
