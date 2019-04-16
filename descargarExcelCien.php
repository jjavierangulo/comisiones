<?php

  $ClienteAux = (isset($_GET['Cliente'])) ? $_GET['Cliente'] : ""; 
  $AgenteAux = (isset($_GET['Agente'])) ? $_GET['Agente'] : ""; 
  $FechaInicio =  (isset($_GET['FechaInicio'])) ? $_GET['FechaInicio'] : "";
  $FechaFin =  (isset($_GET['FechaFin'])) ? $_GET['FechaFin'] : "";

  $Cliente = explode("-", $ClienteAux);
  $Agente = explode("-", $AgenteAux);

  include_once("cnx/cons_cien.php");
  require_once 'clases/PHPExcel.php';

  $objPHPExcel = new PHPExcel();
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("DUES")
        ->setLastModifiedBy("DUES")
        ->setTitle("Exportar excel desde mysql")
        ->setSubject("Reporte")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("DUES")
        ->setCategory("Reporte");   

  $fila = 1;


  foreach ($Agente as $AgenteIniciales) {  

    $ArregloCons = TraerConsulta($AgenteIniciales,$FechaInicio,$FechaFin,$Cliente);
    $Filas = count($ArregloCons);

    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, $AgenteIniciales)
    ->setCellValue('B'.$fila, "DE: " . $FechaInicio)
    ->setCellValue('C'.$fila, "A: " . $FechaFin);

    $fila = $fila+1;
            
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'RBO')
    ->setCellValue('B'.$fila, 'FRECIBO')
    ->setCellValue('C'.$fila, 'AGTE')
    ->setCellValue('D'.$fila, 'FACT')
    ->setCellValue('E'.$fila, 'PEDIDO')
    ->setCellValue('F'.$fila, 'NOMBRE')
    ->setCellValue('G'.$fila, 'COBRANZA')
    ->setCellValue('H'.$fila, 'ANTICIPO')
    ->setCellValue('I'.$fila, 'INSTALACION')
    ->setCellValue('J'.$fila, 'VIATICOS')
    ->setCellValue('K'.$fila, 'IPCLIENTE')
    ->setCellValue('L'.$fila, 'DESCTO')
    ->setCellValue('M'.$fila, 'COMISIONABLE')
    ->setCellValue('N'.$fila, 'COMISION')
    ->setCellValue('O'.$fila, 'TOTAL');         
    
    $fila = $fila+1;

    if ($Filas > 0 ){
      $TotalFinal = 0;

      for( $j=0; $j<$Filas; $j++){

        $Signo = ($ArregloCons[$j]['TIPO'] == 'C') ? " - " : "";

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $ArregloCons[$j]['RBO']) 
        ->setCellValue('B'.$fila, $ArregloCons[$j]['FRECIBO'])
        ->setCellValue('C'.$fila, $ArregloCons[$j]['AGTE'])
        ->setCellValue('D'.$fila, $ArregloCons[$j]['FACT'])
        ->setCellValue('E'.$fila, $ArregloCons[$j]['PEDIDO'])
        ->setCellValue('F'.$fila, $ArregloCons[$j]['NOMBRE'])
        ->setCellValueExplicit('G'.$fila, number_format($ArregloCons[$j]['COBRANZA'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit('H'.$fila, number_format($ArregloCons[$j]['ANTICIPO'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit('I'.$fila, number_format($ArregloCons[$j]['INSTALACION'], 2, '.',','),PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit('J'.$fila, number_format($ArregloCons[$j]['VIATICOS'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('K'.$fila, ($ArregloCons[$j]['IPCLIENTE']))
        ->setCellValue('L'.$fila, $ArregloCons[$j]['DESCTO'])
        ->setCellValueExplicit('M'.$fila, number_format($ArregloCons[$j]['COMISIONABLE'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValue('N'.$fila, $ArregloCons[$j]['COMISION'])
        ->setCellValueExplicit('O'.$fila, $Signo . number_format($ArregloCons[$j]['COMISIONTOTAL'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING);
        
        $TotalFinal = ($ArregloCons[$j]['TIPO'] == 'C') ? $TotalFinal - $ArregloCons[$j]['COMISIONTOTAL'] : $TotalFinal + $ArregloCons[$j]['COMISIONTOTAL'];
        $fila = $fila+1;    
      }

      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('N'.$fila, "TOTAL:")
      ->setCellValueExplicit('O'.$fila, number_format($TotalFinal, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING);
      $fila = $fila + 3;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
    for($col = 'A'; $col !== 'S'; $col++) {
      $objPHPExcel->getActiveSheet()
      ->getColumnDimension($col)
      ->setAutoSize(true);
    }

  }
  // Redirect output to a client’s web browser (Excel5)
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="Reporte_Comisiones.xls"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');

  // If you're serving to IE over SSL, then the following may be needed
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header ('Pragma: public'); // HTTP/1.0

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->save('php://output');
  exit;

?>
