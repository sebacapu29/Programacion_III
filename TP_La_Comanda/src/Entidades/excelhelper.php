<?php

class ExcelHelper{


    public function ConstruirExcel(){

       //    $strExcel= ExcelHelper::ExportarExcel();
       // NOMBRE DEL ARCHIVO Y CHARSET
    //    header('Content-Type:text/csv; charset=latin1');
    //    header('Content-Disposition: attachment; filename="Reporte.csv"');
    header('Content-Type:application/pdf; charset=latin1');
    header('Content-Disposition: attachment; filename="Reporte.pdf"');

       // SALIDA DEL ARCHIVO
       $salida=fopen('php://output', 'w');
       // ENCABEZADOS
       fputcsv($salida, array('Id Alumno', 'Nombre', 'Carrera', 'Grupo', 'Fecha de Ingreso'));
       // QUERY PARA CREAR EL REPORTE
       // $reporteCsv=$conexion->query("SELECT *  FROM alumnos where fecha_ingreso BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_alumno");
       // while($filaR= $reporteCsv->fetch_assoc())
       //     fputcsv($salida, array($filaR['id_alumno'], 
       //                             $filaR['nombre'],
       //                             $filaR['carrera'],
       //                             $filaR['grupo'],
       //                             $filaR['fecha_ingreso']));
   //    return $strExcel;
    }
}


?>