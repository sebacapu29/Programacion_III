<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require_once '../src/Entidades/login.php';
require_once '../src/EntidadesApi/mesaApi.php';
require_once '../src/EntidadesApi/pedidoApi.php';
require_once '../src/EntidadesApi/usuarioApi.php';
require_once '../src/MiddleWare/MWparaAutentificar.php';


return function (App $app) {
    $container = $app->getContainer();

    //Grupo Login
    $app->post('/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });
    //Grupo Usuario
    $app->post('/usuario/alta', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::AltaUsuario($request,$response,$args));
    });
    $app->post('/usuario/baja', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::BajaUsuario($request,$response,$args));
    });
    $app->post('/usuario/modificacion', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(UsuarioApi::ModificacionUsuario($request,$response,$args));
    });
    //Grupo Mesa
    $app->post('/mesa', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::InsertarMesa($request,$response,$args));
    });
    $app->post('/mesa/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::BorrarMesa($request,$response,$args));
    });
    $app->post('/mesa/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::ModificarMesa($request,$response,$args));
    });
    $app->post('/mesa/cancelar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CancelarMesa($request,$response,$args));
    });
    $app->post('/mesa/cerrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->post('/mesa/facturar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::Facturar($request,$response,$args));
    });
    $app->post('/mesa/comentario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->post('/mesa/comentario/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->post('/mesa/comentario/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    
    //Grupo Pedido
    $app->get('/pedido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerPedidosPorTipoEmpleado($request,$response,$args));
    });
    $app->get('/pedido/{codigo}', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerPedidoPorCodigo($request,$response,$args));
    });
    $app->post('/pedido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::HacerPedido($request,$response,$args));
    });
    $app->post('/pedido/modificar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::ModificarPedido($request,$response,$args));
    });
    $app->post('/pedido/borrar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::BorrarPedido($request,$response,$args));
    });
    $app->post('/pedido/listo', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::PedidoListo($request,$response,$args));
    });
    $app->post('/pedido/entregado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::PedidoEntregado($request,$response,$args));
    });
    $app->post('/pedido/cancelar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::CancelarPedido($request,$response,$args));
    });

    //Grupo Empleado
    $app->post('empleado/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });
    $app->get('/empleado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::TraerEmpleados($request,$response,$args));
    });
    $app->get('/empleado/{idEmpleado}', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::TraerEmpleadoPorId($request,$response,$args));
    });
    $app->post('/empleado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::AltaEmpleado($request,$response,$args));
    });
    $app->post('/empleado/fichar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::Fichar($request,$response,$args));
    });
    $app->post('/empleado/suspender', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::Suspender($request,$response,$args));
    });
    $app->post('/empleado/borrar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::PedidoEntregado($request,$response,$args));
    });

    $app->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');
};
