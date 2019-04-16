<?php

   $Agente =  (isset($_GET['Agente'])) ? $_GET['Agente'] : "";
   $TipoCliente =  (isset($_GET['TipoCliente'])) ? $_GET['TipoCliente'] : "";
   $FechaInicio = (isset($_GET['FechaInicio'])) ? $_GET['FechaInicio'] : "";
   $FechaFin = (isset($_GET['FechaFin'])) ? $_GET['FechaFin'] : "";
   $GananciaAux = (isset($_GET['Ganancia'])) ? $_GET['Ganancia'] : "";


   $Clientes = explode(",",$TipoCliente); 
   $Vendedor = explode(",",$Agente); 
   $Ganancia = explode(",",$GananciaAux);

  
   include_once("cnx/cons_DH.php");
   require_once 'clases/PHPExcel.php';

  $objPHPExcel = new PHPExcel();

  foreach ($Vendedor as $iniciales) {    
    $InicialesVentas .= "'".$iniciales."',";
  }

   

   $InicialesVentas = trim($InicialesVentas,",");
   $ArregloFinal =  array();
  
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
         ->setCellValue('A1',"DUES HOME")
         ->setCellValue('B1', "DE: " . $FechaInicio)
         ->setCellValue('C1', "A: " . $FechaFin)
         ;  

   $i = 3;  
   foreach ($Clientes as $tipoC) {

      $ArregloCons = TraerConsulta($FechaInicio,$FechaFin,$tipoC,$InicialesVentas);
      $FilasAux = count($ArregloCons);


      $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i,$tipoC)
        ;
      //$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('29bb04');
      //$objPHPExcel->getActiveSheet()->getStyle('A'.$i':U'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setSize(18);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':U'.$i);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getActiveSheet()
                    ->getStyle('A'.$i.':U'.$i)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('afa4b7');

  
      $i = $i + 1;  
      # code...
//if ($FilasAux > 0 ) {
      $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue('A'.$i, 'RBO')
         ->setCellValue('B'.$i, 'FRECIBO')
         ->setCellValue('C'.$i, 'AGTE')
         ->setCellValue('D'.$i, 'FACT')
         ->setCellValue('E'.$i, 'PEDIDO')
         ->setCellValue('F'.$i, 'NOMBRE')
         ->setCellValue('G'.$i, 'COBRANZA')
         ->setCellValue('H'.$i, 'ANTICIPO')
         ->setCellValue('I'.$i, 'INSTALACION')
         ->setCellValue('J'.$i, 'VIATICOS')
         ->setCellValue('K'.$i, 'IPCLIENTE')
         ->setCellValue('L'.$i, 'DESCTO')
         ->setCellValue('M'.$i, 'VENTA')
         ->setCellValue('N'.$i, 'DESCTO2')
         ->setCellValue('O'.$i, 'COMISIONABLE')
         ->setCellValue('P'.$i, 'CONT')
         ->setCellValue('Q'.$i, 'SEG')
         ->setCellValue('R'.$i, 'CIERRE')
         ->setCellValue('S'.$i, 'ENT')
         ->setCellValue('T'.$i, 'VIST')
         ->setCellValue('U'.$i, 'TOTAL')
         ;
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                 
      $i = $i + 1;    
 
      if ($FilasAux > 0 ) {

         $TotalFinal = 0;
         
         $CobranzaFinal =0;
         $AnticipoFinal=0;
         $InstalacionFinal=0;
         $ViaticosFinal = 0;
         $VentaFinal = 0;
         $DesctoFinal = 0;
         $ComisionableFinal=0;

         for( $j=0; $j<$FilasAux; $j++){

            $Signo = ($ArregloCons[$j]['TIPO'] == 'C') ? " - " : "";

            if ($VendedorAux<>$ArregloCons[$j]['VENDEDORCOMISION']){

              $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $ArregloCons[$j]['VENDEDORCOMISION']);

              $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setBold(true);
              $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setSize(12);
              $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$i.':U'.$i);
              $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
              $objPHPExcel->getActiveSheet()
                            ->getStyle('A'.$i.':U'.$i)
                            ->getFill()
                            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FF3399');                
              $i++;
            
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $ArregloCons[$j]['RBO']) 
                ->setCellValue('B'.$i, $ArregloCons[$j]['FRECIBO'])
                ->setCellValue('C'.$i, $ArregloCons[$j]['AGTE'])
                ->setCellValue('D'.$i, $ArregloCons[$j]['FACT'])
                ->setCellValue('E'.$i, $ArregloCons[$j]['PEDIDO'])
                ->setCellValue('F'.$i, $ArregloCons[$j]['NOMBRE'])
                ->setCellValue('G'.$i, $ArregloCons[$j]['COBRANZA'])
                ->setCellValue('H'.$i, $ArregloCons[$j]['ANTICIPO'])
                ->setCellValue('I'.$i, $ArregloCons[$j]['INSTALACION'])
                ->setCellValue('J'.$i, $ArregloCons[$j]['VIATICOS'])
                ->setCellValue('K'.$i, ($ArregloCons[$j]['IPCLIENTE']))
                ->setCellValue('L'.$i, $ArregloCons[$j]['DESCTO'])
                ->setCellValue('M'.$i, $Signo . $ArregloCons[$j]['VENTA'])
                ->setCellValue('N'.$i, $ArregloCons[$j]['DESCTO2'])
                ->setCellValue('O'.$i, $ArregloCons[$j]['COMISIONABLE'])
                ->setCellValue('P'.$i, $ArregloCons[$j]['CONT'])
                ->setCellValue('Q'.$i, $ArregloCons[$j]['SEG'])
                ->setCellValue('R'.$i, $ArregloCons[$j]['CIERRE'])
                ->setCellValue('S'.$i, $ArregloCons[$j]['ENT'])
                ->setCellValue('T'.$i, $ArregloCons[$j]['VIST'])
                ->setCellValue('U'.$i, $Signo . $ArregloCons[$j]['TOTAL'])
                ->setCellValue('V'.$i, $ArregloCons[$j]['VENDEDORCOMISION']);
                
                $TotalFinal = ($ArregloCons[$j]['TIPO'] == 'C') ?   $TotalFinal - $ArregloCons[$j]['TOTAL'] : $TotalFinal + $ArregloCons[$j]['TOTAL'];
                $VendedorAux = $ArregloCons[$j]['VENDEDORCOMISION'];
                
                $CobranzaFinal = $CobranzaFinal + $ArregloCons[$j]['COBRANZA'];
                $AnticipoFinal= $AnticipoFinal + $ArregloCons[$j]['ANTICIPO'] ;
                $InstalacionFinal= $InstalacionFinal + $ArregloCons[$j]['INSTALACION'];
                $ViaticosFinal = $ViaticosFinal + $ArregloCons[$j]['VIATICOS'] ;
                $VentaFinal = ($ArregloCons[$j]['TIPO'] == 'C') ?   $VentaFinal - $ArregloCons[$j]['VENTA'] : $VentaFinal + $ArregloCons[$j]['VENTA']; //$VentaFinal + $ArregloCons[$j]['VENTA'];
                $DesctoFinal = $DesctoFinal + $ArregloCons[$j]['DESCTO2'];
                $ComisionableFinal= $ComisionableFinal + $ArregloCons[$j]['COMISIONABLE'];

            $i++;    
         }
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('F'.$i, "TOTALES " . $tipoC .":") 
          ->setCellValue('G'.$i, $CobranzaFinal)
          ->setCellValue('H'.$i,  $AnticipoFinal)
          ->setCellValue('I'.$i, $InstalacionFinal)
          ->setCellValue('J'.$i, $ViaticosFinal)
          ->setCellValue('M'.$i, $VentaFinal)
          ->setCellValue('N'.$i, $DesctoFinal)
          ->setCellValue('U'.$i, $TotalFinal);

           $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setBold(true);
              $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':U'.$i)->getFont()->setSize(12);
              $objPHPExcel->getActiveSheet()->getStyle('G'.$i.':U'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


          array_push($ArregloFinal, array('Tipo'=> $tipoC,
                                          'CobranzaFinal'=> $CobranzaFinal,
                                          'AnticipoFinal'=> $AnticipoFinal,
                                          'InstalacionFinal'=> $InstalacionFinal,
                                          'ViaticosFinal'=> $ViaticosFinal,
                                          'VentaFinal'=> $VentaFinal,
                                          'DesctoFinal'=> $DesctoFinal,
                                          'TotalFinal'=> $TotalFinal
                                          ));
        
      } else {

          array_push($ArregloFinal, array('Tipo'=> $tipoC,
                                          'CobranzaFinal'=> 0,
                                          'AnticipoFinal'=> 0,
                                          'InstalacionFinal'=> 0,
                                          'ViaticosFinal'=> 0,
                                          'VentaFinal'=> 0,
                                          'DesctoFinal'=> 0,
                                          'TotalFinal'=> 0
                                          ));


    }

      $i = $i + 2;
   }


    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('E'.$i, "TIPO") 
      ->setCellValueExplicit('F'.$i, "VTAS TOTALES SIN IVA")
      ->setCellValueExplicit('G'.$i, "% GANANCIA" )
      ->setCellValueExplicit('H'.$i, "UTILIDAD" )
      ->setCellValueExplicit('I'.$i, "(-) DESC OTORGADOS ")
      ->setCellValueExplicit('J'.$i, "(-) COMIS A VENDEDORAS" )
      ->setCellValueExplicit('K'.$i,  "(-) COMIS MESES SIN INT")
      ->setCellValueExplicit('L'.$i, "INGRESOS" )
      ;
      $objPHPExcel->getActiveSheet()->getStyle('E'.$i.':L'.$i)->getFont()->setBold(true);
              $objPHPExcel->getActiveSheet()->getStyle('E'.$i.':L'.$i)->getFont()->setSize(12);
              $objPHPExcel->getActiveSheet()->getStyle('E'.$i.':L'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $i ++;

    $FilasTotales = count($ArregloFinal);
    for( $k=0; $k<$FilasTotales; $k++){

      $VentasTotalesSinIva = $ArregloFinal[$k]['VentaFinal'] / 1.16;
      
      $PorcentajeGanancia = $Ganancia[$k];//MostrarGanancia($ArregloFinal[$k]['Tipo']);//57;

      

      $Utilidad = ($VentasTotalesSinIva * ($PorcentajeGanancia/100));
      $DescuentosOtorgados = ($ArregloFinal[$k]['DesctoFinal'] / 1.16);
      $PorcentajeViaticos = 0.02;

      $SumaViaticos = ($ArregloFinal[$k]['InstalacionFinal'] + $ArregloFinal[$k]['ViaticosFinal']) * $PorcentajeViaticos;
      $ComisionesVendedores = $SumaViaticos + $ArregloFinal[$k]['TotalFinal'];
      $Ingresos = $Utilidad - $DescuentosOtorgados - $ComisionesVendedores;

      $IngresoFinal = $IngresoFinal + $Ingresos;
      
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E'.$i, $ArregloFinal[$k]['Tipo']) 
        ->setCellValue('F'.$i, $VentasTotalesSinIva)
        ->setCellValue('G'.$i, $PorcentajeGanancia ."%")
        ->setCellValue('H'.$i, $Utilidad)
        ->setCellValue('I'.$i, $DescuentosOtorgados)
        ->setCellValue('J'.$i, $ComisionesVendedores)
        ->setCellValue('K'.$i,  "0")
        ->setCellValue('L'.$i, $Ingresos)
        ;

      $i ++;
    }


      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValueExplicit('K'.$i,  "TOTAL INGRESOS")
      ->setCellValueExplicit('L'.$i, number_format($IngresoFinal, 2, '.', ','),PHPExcel_Cell_DataType::TYPE_STRING )
      ;
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i.':L'.$i)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i.':L'.$i)->getFont()->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle('K'.$i.':L'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);




              
  
  
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
