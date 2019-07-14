<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require_once '../src/clases/login.php';
require_once '../src/clases/usuarioApi.php';
require_once '../src/clases/MiddleWare/MWparaAutentificar.php';
require_once '../src/clases/compraApi.php';
require_once '../src/clases/MiddleWare/MWGuardarInfoEnDB.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });
    $app->post('/usuario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(UsuarioApi::AltaUsuario($request,$response,$args)));
    });
    $app->post('/compra', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(CompraApi::GestionCompra($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');
    
    $app->get('/compra', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(CompraApi::ConsultarCompra($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');

    $app->get('/usuario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::TraerTodos($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuario');

    $app->add(\MWGuardarInfoEnDB::class . ':GuardarDatosEnDB');//middleware global
};