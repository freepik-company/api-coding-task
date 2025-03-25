<?php

namespace App\Equipment\Infraestructure\Http;

use App\Equipment\Application\CreateEquipmentUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateEquipmentController{
    public function __construct(private CreateEquipmentUseCase $useCase){
        
    }
    
    public function __invoke(Request $request, Response $response, array $args): Response{
        $data = $request->getParsedBody();
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $requiredFields = ['name', 'type', 'made_by' ];
        foreach ($requiredFields as $field){
            if (!isset($data[$field])){
                $response->getBody()->write(json_encode(['error'=> "Missing required field: {$field}"]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        } 

        try{
            $character = $this->useCase->execute(
                $data['naem'],
                $data['type'],
                $data['made_by']
            );

            //Return success response
            $response->getBody()
        }
    }
    
}