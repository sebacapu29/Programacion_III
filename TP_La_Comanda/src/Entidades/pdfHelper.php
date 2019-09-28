<?php

use Dompdf\Dompdf;
require_once 'pedidoDetalle.php';
require_once 'pedido.php';

class PdfHelper{


    public function ConstruirPDF(){

        $objPedidoMenosVendidos = PedidoDetalle::TraerPedidosMenosVendidos();
        $objPedidoMasVendidos = PedidoDetalle::TraerPedidosMasVendidos();
        $objTodosLosPedidos = Pedido::TraerTodosLosPedidos();
        $str="";
         foreach($objTodosLosPedidos as $aux) {
             $str .= "
             <tr>
                 <td style='text-align: center'>".$aux->id ." </td>
                 <td style='text-align: center'>".$aux->estado ." </td>
                 <td style='text-align: center'>".$aux->tiempoestimado ." </td>
                 <td style='text-align: center'>".$aux->tiempoentrega ." </td>
                 <td style='text-align: center'>".$aux->codigo ." </td>
                 <td style='text-align: center'>".$aux->idmesa ." </td>
                 <td style='text-align: center width ='150px' height='150px''>"."<img src=" .$objTodosLosPedidos->foto . " alt='No Se encuentra foto'/>" . " </td>
             </tr>";
             }
       //generate some PDFs!
         $dompdf = new DOMPDF();  //if you use namespaces you may use new \DOMPDF()
         $dompdf->loadHtml("<!DOCTYPE html>
                     <html lang='es'>
                         <head>
             <meta charset=UTF-8>
             <meta name='viewport' content='width=device-width', initial-scale=1.0>
             <meta http-equiv= 'X-UA-Compatible content=ie=edge'>
             <title>Document</title>
         </head>
         <body>
         <style>
                 table, th, td {
           border: 1px solid black;
           border-collapse: collapse;
           text
         }
         tr:hover {background-color: #f5f5f5;}
         th {
           background-color: #4CAF50;
           color: white;
         }
         </style
         <h1>TP La Comanda - Capurro</h1>
         <h2>Lista de Pedidos Mas vendidos</h2>
         <table align='center'>
             <tr>
                 <th> ID </th>
                 <th> Menu </th>
                 <th> Cantidad Vendida </th>
             </tr>
             <tr>
                 <td style='text-align: center'>".$objPedidoMasVendidos->id ." </td>
                 <td style='text-align: center'>". $objPedidoMasVendidos->Menu ." </td>
                 <td style='text-align: center'>". $objPedidoMasVendidos->Cantidad_Ventas ." </td>
             </tr>
         </table>
         <h2>Lista de Pedidos Menos vendidos</h2>
         <table align='center'>
             <tr>
                 <th> ID </th>
                 <th> Menu </th>
                 <th> Cantidad Vendida </th>
             </tr>
             <tr>
                 <td style='text-align: center'>".$objPedidoMenosVendidos->id ." </td>
                 <td style='text-align: center'>".$objPedidoMenosVendidos->Menu ." </td>
                 <td style='text-align: center'>".$objPedidoMenosVendidos->Cantidad_Ventas ." </td>
             </tr>
         </table>
                 <h2>Lista Todos Los Pedidos</h2>
         <table align='center'>
             <tr>
                 <th> ID </th>
                 <th> Estado </th>
                 <th> Tiempo Estimado Entrega </th>
                 <th> Tiempo Entregado </th>
                 <th> Codigo </th>
                 <th> ID Mesa </th>
                 <th> Foto </th>
             </tr>
             
                       ". $str . "
         </table>
         </body>
         </html>");
         $dompdf->render();
         $dompdf->stream("sample.pdf", array("Attachment"=>0));
         
    }
}
?>