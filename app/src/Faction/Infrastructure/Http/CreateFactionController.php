<?php

namespace App\Faction\Infrastructure\Http;

use App\Faction\Application\CreateFactionUseCase;
use App\Faction\Application\CreateFactionUseCaseRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * CreateFactionController is a class that is used to create a new faction.
 *
 * @package App\Faction\Infrastructure\Http
 */

class CreateFactionController
{
    public function __construct(
        private CreateFactionUseCase $useCase
    ) {}


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $requiredFields = ['faction_name', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $response->getBody()->write(json_encode(['error' => "Missing required field: {$field}"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        try {
            $request = new CreateFactionUseCaseRequest(
                $data['faction_name'],
                $data['description']
            );

            $faction = $this->useCase->execute($request);

            // Return success response
            $response->getBody()->write(json_encode([
                'message' => 'Faction created successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
