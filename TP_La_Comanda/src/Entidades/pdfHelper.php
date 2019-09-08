<?php

use Dompdf\Dompdf;

class PdfHelper{


    public function ConstruirPDF(){

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
            <p>jhfjhf</p>
        </body>
        </html>");
        $dompdf->render();
        $dompdf->stream("sample.pdf", array("Attachment"=>0));
    }
}


?>