<?php

namespace App\Controller\Create;

use App\Model\Equipment;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class CreateEquipmentsController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        // Log raw body for debugging
        $rawBody = (string) $request->getBody();
        error_log('Raw request body: ' . $rawBody);

        // Get JSON content from request body
        $data = $request->getParsedBody();
        error_log('Parsed request body: ' . print_r($data, true));

        // Validate JSON content
        if (empty($data)) {
            error_log('Empty request body received');
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Request body must be valid JSON'
            ], JSON_PRETTY_PRINT));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Validate required fields
        $requiredFields = ['name', 'type', 'made_by'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Missing required field: {$field}");
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => "Missing required field: {$field}",
                    'data' => $data
                ], JSON_PRETTY_PRINT));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        // Check if equipment already exists
        $existingEquipment = Equipment::findByName($this->db, $data['name']);
        if ($existingEquipment) {
            error_log('Equipment already exists: ' . $data['name']);
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 409,
                'message' => 'An equipment with this name already exists',
                'data' => [
                    'id' => $existingEquipment->getId(),
                    'name' => $existingEquipment->getName(),
                    'type' => $existingEquipment->getType(),
                    'made_by' => $existingEquipment->getMadeBy()
                ]
            ], JSON_PRETTY_PRINT));
            return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
        }

        // Create a new Equipment instance
        $equipment = new Equipment($this->db);
        $equipment->setName($data['name']);
        $equipment->setType($data['type']);
        $equipment->setMadeBy($data['made_by']);

        // Save the equipment to the database
        if (!$equipment->save()) {
            error_log('Failed to save equipment: ' . $data['name']);
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to save equipment'
            ], JSON_PRETTY_PRINT));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

        error_log('Equipment created successfully: ' . $data['name']);
        // Return success response
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'code' => 201,
            'message' => 'Equipment created successfully',
            'data' => [
                'id' => $equipment->getId(),
                'name' => $equipment->getName(),
                'type' => $equipment->getType(),
                'made_by' => $equipment->getMadeBy()
            ]
        ], JSON_PRETTY_PRINT));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
} 