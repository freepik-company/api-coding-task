<?php

namespace App\Equipment\Infrastructure\Http;

use App\Equipment\Application\DeleteEquipmentUseCase;
use App\Equipment\Infrastructure\Persistance\Pdo\Exception\EquipmentNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteEquipmentController
{
    public function __construct(
        private DeleteEquipmentUseCase $useCase
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $this->useCase->execute($args['id']);

            $response->getBody()->write(json_encode([
                'message' => 'Equipment deleted successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (EquipmentNotFoundException $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Equipment not found'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Failed to delete equipment'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
