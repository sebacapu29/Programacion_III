<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require_once '../src/clases/login.php';
require_once '../src/clases/usuarioApi.php';
require_once '../src/clases/MiddleWare/MWparaAutentificar.php';
require_once '../src/clases/compraApi.php';
require_once '../src/clases/productos.php';
require_once '../src/clases/MiddleWare/MWGuardarInfoEnDB.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });
    $app->post('/usuario/alta', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(UsuarioApi::AltaUsuario($request,$response,$args)));
    });
    $app->get('/usuario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::TraerTodos($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');

    $app->get('/productos', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Producto::TraerTodoLosProductos($request,$response,$args)));
    });//->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');

    $app->post('/productos/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Producto::BorrarProducto($request,$response,$args)));
    });
    $app->get('/productos/buscar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Producto::TraerProductoPorDescripcion($request,$response,$args)));
    });
    $app->post('/usuario/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::ModificarUsuario($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');

    $app->post('/usuario/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::BorrarUsuario($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');


    $app->post('/compra', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(CompraApi::GestionCompra($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');
    
    $app->get('/compra', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(CompraApi::ConsultarCompra($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');

    //$app->add(\MWparaCORS::class . 'HabilitarCORSTodos');
};
