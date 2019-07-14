<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require_once '../src/Entidades/login.php';
require_once '../src/EntidadesApi/mesaApi.php';
require_once '../src/EntidadesApi/pedidoApi.php';
require_once '../src/MiddleWare/MWparaAutentificar.php';

return function (App $app) {
    $container = $app->getContainer();

    $app->post('/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });

    $app->post('/mesa', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::InsertarMesa($request,$response,$args)));
    });
    $app->post('/mesa/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::BorrarMesa($request,$response,$args)));
    });
    $app->post('/mesa/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::ModificarMesa($request,$response,$args)));
    });
    $app->post('/mesa/cancelar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::CancelarMesa($request,$response,$args)));
    });
    $app->post('/mesa/cerrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::CerrarMesa($request,$response,$args)));
    });
    $app->post('/mesa/comentario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::CerrarMesa($request,$response,$args)));
    });
    $app->post('/mesa/comentario/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_decode(MesaApi::CerrarMesa($request,$response,$args)));
    });
    
    $app->post('/pedido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::HacerPedido($request,$response,$args));
    });

    // $app->group('/mesa', function () {

        // $this->post('/ ', \MesaApi::class . ':InsertarLaMesa');
    
        // $this->delete('/', \MesaApi::class . ':BorrarMesa');
    
        // $this->put('/', \MesaApi::class . ':ModificarMesa');
    
        // $this->post('/cerrar', \MesaApi::class . ':CerrarMesa');
    
        // $this->post('/comentario', \MesaApi::class . ':AgregarComentario');
    
        // $this->delete('/comentario', \MesaApi::class . ':BorrarComentario');
    
        // $this->put('/comentario', \MesaApi::class . ':ModificarComentario');
    
    // });
    
    // $app->group('/pedido', function () {
    
    //     $this->post('/', \MesaApi::class . ':HacerPedido');
    
        // $this->delete('/', \MesaApi::class . ':BorrarPedido');
    
        // $this->put('/', \MesaApi::class . ':ModificarPedido');
    
        // $this->post('/listo', \MesaApi::class . ':PedidoListo');
    
        // $this->post('/entregar', \MesaApi::class . ':PedidoEntregado');
    
        // $this->post('/facturar', \MesaApi::class . ':Facturar');
    
        // $this->post('/cancelar', \MesaApi::class . ':CancelarPedido');
    
        // $this->post('/items', \MesaApi::class . ':AgregarItems');
    
    // });
    
    // $app->group('/empleado', function () {
    
        // $this->post('/', \EmpleadosApi::class . ':InsertarEmpleado');
    
        // $this->delete('/', \MesaApi::class . ':BorrarEmpleado');
    
        // $this->put('/', \MesaApi::class . ':ModificarEmpleado');
    
        // $this->post('/login', \LoginApi::class . ':AltaDatos');
    
        // $this->post('/fichar', \EmpleadosApi::class . ':Fichar');
    
        // $this->post('/suspender', \EmpleadosApi::class . ':Suspender');
    
        // $this->post('/borrar', \EmpleadosApi::class . ':BajaLogica');
    
    // });
    $app->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');
};
