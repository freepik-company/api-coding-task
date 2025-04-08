<?php

namespace App\Faction\Infrastructure\Http;

use App\Faction\Application\ReadFactionUseCase;
use App\Faction\Domain\FactionToArrayTransformer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * ReadFactionController is a class that is used to read a faction.
 *
 * @package App\Faction\Infrastructure\Http
 */

class ReadFactionController
{
    public function __construct(
        private ReadFactionUseCase $useCase
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $faction = $this->useCase->execute($args['id']);

            $response->getBody()->write(json_encode([
                'faction' => FactionToArrayTransformer::transform($faction)
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Failed to read faction',
                'message' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
