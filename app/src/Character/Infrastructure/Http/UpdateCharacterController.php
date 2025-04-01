<?php

namespace App\Character\Infrastructure\Http;

use App\Character\Application\UpdateCharacterUseCase;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UpdateCharacterController
{
    public function __construct(
        private UpdateCharacterUseCase $updateCharacterUseCase
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $requiredFields = ['name', 'birth_date', 'kingdom', 'equipment_id', 'faction_id'];
        foreach ($requiredFields as $field){
            if (!isset($data[$field])){
                $response->getBody()->write(json_encode(['error' => "Missing required field: {$field}"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        try {
            $request = new \App\Character\Application\UpdateCharacterUseCaseRequest(
                id: $args['id'],
                name: $data['name'],
                birthDate: $data['birth_date'],
                kingdom: $data['kingdom'],
                equipmentId: $data['equipment_id'],
                factionId: $data['faction_id']
            );

            $character = $this->updateCharacterUseCase->execute($request);

            $response->getBody()->write(json_encode([
                'id' => $character->getId(),
                'message' => 'Character updated successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
