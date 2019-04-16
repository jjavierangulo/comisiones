<?php

  if (isset($_GET['Agente']) and $_GET['Agente'] <> ""){
      $Agente = $_GET['Agente'];
  }else{
    $Agente="";
  }

  if (isset($_GET['FechaInicio']) and $_GET['FechaInicio'] <> ""){
      $FechaInicio = $_GET['FechaInicio']; 
  }else{
       $FechaInicio ="";
  }

  if (isset($_GET['FechaFin']) and $_GET['FechaFin'] <> ""){
      $FechaFin = $_GET['FechaFin'];
  }else{
        $FechaFin="";
  }

  include_once("cnx/cons.php");

  require_once 'clases/PHPExcel.php';
  $objPHPExcel = new PHPExcel();


    $ArregloCons = TraerConsulta($Agente,$FechaInicio,$FechaFin);
    $Filas = count($ArregloCons);
    
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

          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $Agente)
            ->setCellValue('B1', "DE: " . $FechaInicio)
            ->setCellValue('C1', "A: " . $FechaFin)
            ->setCellValue('A2', 'RBO')
            ->setCellValue('B2', 'FRECIBO')
            ->setCellValue('C2', 'AGTE')
            ->setCellValue('D2', 'FACT')
            ->setCellValue('E2', 'PEDIDO')
            ->setCellValue('F2', 'NOMBRE')
            ->setCellValue('G2', 'COBRANZA')
            ->setCellValue('H2', 'ANTICIPO')
            ->setCellValue('I2', 'INSTALACION')
            ->setCellValue('J2', 'VIATICOS')
            ->setCellValue('K2', 'FLETES')
            ->setCellValue('L2', 'IPCLIENTE')
            ->setCellValue('M2', 'DESCTO')
            ->setCellValue('N2', 'COMISIONABLE')
            ->setCellValue('O2', 'CONT')
            ->setCellValue('P2', 'SEG')
            ->setCellValue('Q2', 'CIERRE')
            ->setCellValue('R2', 'ENT')
            ->setCellValue('S2', 'VIST')
            ->setCellValue('T2', 'TOTAL')
            ->setCellValue('U2', 'COMENTARIO')
            ;
            
             
   $i = 3;    
  if ($Filas > 0 ){
    $TotalFinal = 0;
    $CobranzaTotal= 0;
    $Anticipo = 0;
    for( $j=0; $j<$Filas; $j++){

      if ($ArregloCons[$j]['TIPO'] == 'C'){
        $Signo = " - ";
      } 
      else{
        $Signo = "";
      }
      $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A'.$i, $ArregloCons[$j]['RBO']) 
          ->setCellValue('B'.$i, $ArregloCons[$j]['FRECIBO'])
          ->setCellValue('C'.$i, $ArregloCons[$j]['AGTE'])
          ->setCellValue('D'.$i, $ArregloCons[$j]['FACT'])
          ->setCellValue('E'.$i, $ArregloCons[$j]['PEDIDO'])
          ->setCellValue('F'.$i, $ArregloCons[$j]['NOMBRE'])
          ->setCellValueExplicit('G'.$i, number_format($ArregloCons[$j]['COBRANZA'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit('H'.$i, number_format($ArregloCons[$j]['ANTICIPO'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit('I'.$i, number_format($ArregloCons[$j]['INSTALACION'], 2, '.',','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit('J'.$i, number_format($ArregloCons[$j]['VIATICOS'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValueExplicit('K'.$i, number_format($ArregloCons[$j]['FLETES'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValue('L'.$i, ($ArregloCons[$j]['IPCLIENTE']))
          ->setCellValue('M'.$i, $ArregloCons[$j]['DESCTO'])
          ->setCellValueExplicit('N'.$i, number_format($ArregloCons[$j]['COMISIONABLE'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValue('O'.$i, $ArregloCons[$j]['CONT'])
          ->setCellValue('P'.$i, $ArregloCons[$j]['SEG'])
          ->setCellValue('Q'.$i, $ArregloCons[$j]['CIERRE'])
          ->setCellValue('R'.$i, $ArregloCons[$j]['ENT'])
          ->setCellValue('S'.$i, $ArregloCons[$j]['VIST'])
          ->setCellValueExplicit('T'.$i, $Signo . number_format($ArregloCons[$j]['TOTAL'], 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
          ->setCellValue('U'.$i, $ArregloCons[$j]['Comentarios']);
           if ($ArregloCons[$j]['TIPO'] == 'C'){
            $TotalFinal = $TotalFinal - $ArregloCons[$j]['TOTAL'];
          }
          else{
            $TotalFinal = $TotalFinal + $ArregloCons[$j]['TOTAL'];
            $CobranzaTotal= $CobranzaTotal + $ArregloCons[$j]['COBRANZA'];
            $Anticipo = $Anticipo + $ArregloCons[$j]['ANTICIPO'];
          }
      $i++;    
    }
    $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue('F'.$i, "TOTALES:")
    ->setCellValueExplicit('G'.$i, number_format($CobranzaTotal, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
    ->setCellValueExplicit('H'.$i, number_format($Anticipo, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING)
    ->setCellValue('S'.$i, "TOTAL:")
    ->setCellValueExplicit('T'.$i, number_format($TotalFinal, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING);
  }
              
  
  
  $objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
  //$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(TRUE);
 for($col = 'A'; $col !== 'S'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
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
