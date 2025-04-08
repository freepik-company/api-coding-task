<?php

namespace App\Faction\Infrastructure\Http;

use App\Faction\Application\DeleteFactionUseCase;
use App\Faction\Infrastructure\Persistence\Pdo\Exception\FactionNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * DeleteFactionController is a class that is used to delete a faction.
 *
 * @package App\Faction\Infrastructure\Http
 */

class DeleteFactionController
{
    public function __construct(
        private DeleteFactionUseCase $useCase
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $this->useCase->execute($args['id']);

            $response->getBody()->write(json_encode([
                'message' => 'Faction deleted successfully'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (FactionNotFoundException $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Faction not found'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'message' => 'Failed to delete faction'
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
