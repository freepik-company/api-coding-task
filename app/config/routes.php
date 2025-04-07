<?php

use App\Character\Infrastructure\Http\CreateCharacterController;
use App\Character\Infrastructure\Http\DeleteCharacterController;
use App\Character\Infrastructure\Http\ReadAllCharactersController;
use App\Character\Infrastructure\Http\ReadCharacterController;
use App\Character\Infrastructure\Http\UpdateCharacterController;
use App\Equipment\Infrastructure\Http\CreateEquipmentController;
use App\Equipment\Infrastructure\Http\DeleteEquipmentController;
use App\Equipment\Infrastructure\Http\ReadAllEquipmentController;
use App\Equipment\Infrastructure\Http\ReadEquipmentController;
use App\Equipment\Infrastructure\Http\UpdateEquipmentController;
use Slim\App;

/**
 * This file is used to define the routes of the application.
 *
 * @param App $app
 * @return void
 */

return function (App $app) {
    // Add error handling middleware
    $app->add(new \App\Shared\Infrastructure\Exception\Http\ErrorHandlerMiddleware());

    // Add routes for the character resource
    $app->post('/character', CreateCharacterController::class);
    $app->get('/characters', ReadAllCharactersController::class);
    $app->get('/character/{id}', ReadCharacterController::class);
    $app->delete('/character/{id}', DeleteCharacterController::class);
    $app->put('/character/{id}', UpdateCharacterController::class);

    // Add routes for the equipment resource
    $app->post('/equipment', CreateEquipmentController::class);
    $app->get('/equipment/{id}', ReadEquipmentController::class);
    $app->put('/equipment/{id}', UpdateEquipmentController::class);
    $app->get('/equipments', ReadAllEquipmentController::class);
    $app->delete('/equipment/{id}', DeleteEquipmentController::class);
    $app->run();
};
