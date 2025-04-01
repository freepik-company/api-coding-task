<?php

use App\Character\Infrastructure\Http\CreateCharacterController;
use App\Character\Infrastructure\Http\DeleteCharacterController;
use App\Character\Infrastructure\Http\ReadAllCharactersController;
use App\Character\Infrastructure\Http\ReadCharacterController;
use App\Character\Infrastructure\Http\UpdateCharacterController;
use App\Equipment\Infrastructure\Http\CreateEquipmentController;
use Slim\App;
use Slim\Middleware\BodyParsingMiddleware;

/**
 * This file is used to define the routes of the application.
 * 
 * @param App $app
 * @return void
 */

return function (App $app){

// Add middleware to parse the body of the request as JSON
$app->add(new BodyParsingMiddleware());

    // Add error handling middleware
$app->add(new \App\Shared\Infrastructure\Exception\Http\ErrorHandlerMiddleware());

// Add routes for the character resource
$app->post('/character', CreateCharacterController::class);
$app->get('/characters', ReadAllCharactersController::class);
$app->get('/character/{id}', ReadCharacterController::class);
$app->delete('/character/{id}', DeleteCharacterController::class);
$app->put('/character/{id}', UpdateCharacterController::class);

// Add routes for the equipment resource
$app->post('/equipments', CreateEquipmentController::class);
$app->run();
};