<?php

use Dompdf\Dompdf;
require_once 'pedidoDetalle.php';

class PdfHelper{


    public function ConstruirPDF(){

       $objPedidoMenosVendidos = PedidoDetalle::TraerPedidosMenosVendidos();
       $objPedidoMasVendidos = PedidoDetalle::TraerPedidosMasVendidos();
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
        <h1>Lista de Pedidos Mas vendidos</h1>
        <table>
            <tr>
                <th> ID </th>
                <th> Menu </th>
                <th> Cantidad Vendida </th>
            </tr>
            <tr>
                <td>".$objPedidoMasVendidos->id ." </td>
                <td>". $objPedidoMasVendidos->Menu ." </td>
                <td>". $objPedidoMasVendidos->Cantidad_Ventas ." </td>
            </tr>
        </table>
        <h1>Lista de Pedidos Menos vendidos</h1>
        <table>
            <tr>
                <th> ID </th>
                <th> Menu </th>
                <th> Cantidad Vendida </th>
            </tr>
            <tr>
                <td>".$objPedidoMenosVendidos->id ." </td>
                <td>".$objPedidoMenosVendidos->Menu ." </td>
                <td>".$objPedidoMenosVendidos->Cantidad_Ventas ." </td>
            </tr>
        </table>
        </body>
        </html>");
        $dompdf->render();
        $dompdf->stream("pedidos.pdf", array("Attachment"=>0));
    }
}
?>