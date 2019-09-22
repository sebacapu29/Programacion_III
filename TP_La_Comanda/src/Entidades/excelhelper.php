<?php

class ExcelHelper{


    public function ConstruirExcel(){

        $objPedidoMenosVendidos = PedidoDetalle::TraerPedidosMenosVendidos();
        $objPedidoMasVendidos = PedidoDetalle::TraerPedidosMasVendidos();
        
        //    $strExcel= ExcelHelper::ExportarExcel();
        // NOMBRE DEL ARCHIVO Y CHARSET
         header('Content-Type:text/csv; charset=latin1');
         header('Content-Disposition: attachment; filename="Pedidos.csv"');
         
        // SALIDA DEL ARCHIVO
        $salida=fopen('php://output', 'w');
        // ENCABEZADOS
               fputcsv($salida, array('Pedidos Menos vendidos'));
        fputcsv($salida, array('ID', 'Menu', 'Cantidad Ventas', 'Grupo'));
        fputcsv($salida, array($objPedidoMenosVendidos->id, $objPedidoMenosVendidos->Menu, $objPedidoMenosVendidos->Cantidad_Ventas));
        fputcsv($salida, array('Pedidos Mas vendidos'));
        fputcsv($salida, array('ID', 'Menu', 'Cantidad Ventas', 'Grupo'));
        fputcsv($salida, array($objPedidoMasVendidos->id, $objPedidoMasVendidos->Menu, $objPedidoMasVendidos->Cantidad_Ventas));
    }
}


?>