<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
require_once '../src/Entidades/login.php';
require_once '../src/EntidadesApi/mesaApi.php';
require_once '../src/EntidadesApi/pedidoApi.php';
require_once '../src/EntidadesApi/empleadoApi.php';
require_once '../src/MiddleWare/MWparaAutentificar.php';
require_once '../src/MiddleWare/MWparaRegistroOperacion.php';


return function (App $app) {
    $container = $app->getContainer();

    //Grupo Mesa
    $app->post('/mesa', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::InsertarMesa($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/mesa/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::BorrarMesa($request,$response,$args));
    });
    $app->post('/mesa/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::ModificarMesa($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');
    
    $app->post('/mesa/cancelar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CancelarMesa($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/mesa/cerrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/mesa/facturar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::Facturar($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/mesa/comentario', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->post('/mesa/comentario/borrar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->post('/mesa/comentario/modificar', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(MesaApi::CerrarMesa($request,$response,$args));
    });
    $app->get('/masUsada',function (Request $request, Response $response, array $args) use ($container){
     $response->write(MesaApi::class . ':MesaMasUsada')->add(\MWEmpleado::class . ':VerificarUsuarioAdmin');
    });
    
    $app->get('/menosUsada',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':MesaMasUsada')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/masFacturada',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':MesaMasUsada')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/menosFacturada',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':MesaMenosUsada')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/facturadaConMasImporte',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':FactiradaConMasImporte')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/facturadaConMenosImporte',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':FacturadaConMenosImporte')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/masPuntuada',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':MasPuntuada')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
       });
    $app->get('/menosPuntuada',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':MenosPuntuada')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/facturacion/fechas',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':FacturacionEntreFechas')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/facturacion/promedioMensual',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':PromedioMensual')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    $app->get('/facturacion/promedioMensualMesa',function (Request $request, Response $response, array $args) use ($container){
        $response->write(MesaApi::class . ':PromedioMensualMesa')->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    });
    //Grupo Pedido
    $app->get('/pedido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerPedidosPorTipoEmpleado($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');
    
    $app->get('/pedido/cancelados', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerPedidosCancelados($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');
    
    $app->get('/pedido/fueradehorario', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::PedidosFueraDeHorario($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->get('/pedido/masvendido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerMasVendidos($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');
    
    $app->get('/pedido/menosvendido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::TraerMenosVendidos($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->post('/pedido', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::HacerPedido($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/pedido/modificar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::ModificarPedido($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/pedido/borrar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::BorrarPedido($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    $app->post('/pedido/listo', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::PedidoListo($request,$response,$args));
    });
    $app->post('/pedido/entregado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::PedidoEntregado($request,$response,$args));
    });
    $app->post('/pedido/cancelar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(PedidoApi::CancelarPedido($request,$response,$args));
    })->add(\MWparaRegistroOperacion::class . ':IncrementarOperacionAEmpleado')->add(\MWparaAutentificar::class . ':VerificarEmpleadoMozo');

    //Grupo Operacion (solo Admin)
    $app->get('/operacion/sector', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::OperacionesPorSector($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->get('/operacion/sector/empleado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::OperacionesPorSectorPorEmpleados($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->get('/operacion/empleado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::OperacionesEmpleados($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    //Grupo Empleado
    $app->post('/empleado/login', function (Request $request, Response $response, array $args) use ($container) {
        $response->write(json_encode(Login::_login($request,$response,$args)));
    });
    $app->get('/empleados', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::TraerEmpleados($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');;
    
    $app->get('/empleado/traeruno', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::TraerEmpleadoPorId($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->post('/empleado', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::AltaEmpleado($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');;
    
    $app->post('/empleado/fichar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::Fichar($request,$response,$args));
    });
    $app->get('/empleado/dias', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::TraerInfoEmpleadoDias($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->post('/empleado/suspender', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::Suspender($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->post('/empleado/borrar', function (Request $request, Response $response, array $args) use ($container) {    
        $response->write(EmpleadoApi::BorrarEmpleado($request,$response,$args));
    })->add(\MWparaAutentificar::class . ':VerificarUsuarioAdmin');

    $app->add(\MWparaAutentificar::class . ':VerificarUsuarioToken');
};
