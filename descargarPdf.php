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

 require_once 'clases/dompdf/dompdf_config.inc.php';
  $dompdf = new DOMPDF();

  ini_set("memory_limit", '256M');
  include_once("cnx/cons.php");
   $ArregloCons = TraerConsulta($Agente,$FechaInicio,$FechaFin);
   $Filas = count($ArregloCons);

   $variable= '<html><body marginwidth="0" marginheight="0"><h4>Agente:' .$Agente.'. De '. $FechaInicio .' a '. $FechaFin .'</h4>
                <table style=" font-size: 6px; border: 0.5pt solid black; border-collapse: collapse; width:100%" >
                  	<thead>
                  		<tr style="font-size: smaller;">
	                      <th>RBO111</th>
	                      <th>FRECIBO</th>
	                      <th >AGTE</th>
	                      <th >FACT</th>
	                      <th >PEDIDO</th>
	                      <th >NOMBRE</th>
	                      <th >COBRANZA</th>
	                      <th >ANTICIPO</th>
	                      <th >INSTALACION</th>
	                      <th >VIATICOS</th>
                        <th >FLETES</th>
	                      <th >IPCLIENTE</th>
	                      <th >DESCTO</th>
	                      <th >COMISIONABLE</th>
	                      <th >CONT</th>
	                      <th >SEG</th>
	                      <th >CIERRE</th>
	                      <th >ENT</th>
	                      <th >VIST</th>
	                      <th >TOTAL</th>
                  		</tr>
              		</thead>
                  <tbody>';

                 $TotalFinal = 0;
                for( $i=0; $i<$Filas; $i++){

                   if ($ArregloCons[$i]['TIPO'] == 'C'){
                    $Signo = " - ";
                  } 
                  else{
                    $Signo = "";
                  }

                  $variable = $variable . "<tr style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>" .
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['RBO']."</td>" .
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'> ".$ArregloCons[$i]['FRECIBO']."</td>".
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['AGTE']."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['FACT']."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['PEDIDO']."</td>".
                       "<td style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['NOMBRE']."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['COBRANZA'], 2, '.', ', ')."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['ANTICIPO'], 2, '.', ', ')."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['INSTALACION'], 2, '.', ', ')."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['VIATICOS'], 2, '.', ', ')."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['FLETES'], 2, '.', ', ')."</td>".
                       "<td style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".($ArregloCons[$i]['IPCLIENTE'])."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['DESCTO']."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format($ArregloCons[$i]['COMISIONABLE'], 2, '.', ', ')."</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['CONT']."%</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['SEG']."%</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['CIERRE']."%</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['ENT']."%</td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".$ArregloCons[$i]['VIST']."%</td>".
                       "<td  style='TEXT-ALIGN: RIGHT;font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".$Signo.number_format($ArregloCons[$i]['TOTAL'], 2, '.', ', ')."</td>".
                   "</tr>";

                    if ($ArregloCons[$i]['TIPO'] == 'C'){
                      $TotalFinal = $TotalFinal - $ArregloCons[$i]['TOTAL'];
                    }
                    else{
                      $TotalFinal = $TotalFinal + $ArregloCons[$i]['TOTAL'];
                    }
                }
                           
    $variable = $variable . "<tr style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>" .
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>" .
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td  style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td style='font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'></td>".
                       "<td class='numeric' style='TEXT-ALIGN: RIGHT; font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>TOTAL</td>".
                       "<td  style='TEXT-ALIGN: RIGHT;font-size: smaller; border: 0.5pt solid black; border-collapse: collapse;'>$ ".number_format( $TotalFinal, 2, '.', ', ')."</td>".
                   "</tr>";
               
    $variable = $variable . "</tbody></table></body></html>";

   //ECHO $variable;
  
  //echo "EXPORTAR PDF EN MANTENIMIENTO, FAVOR DE EXPORTAR EN EXCEL.";
  $dompdf->load_html(utf8_decode($variable));
  $dompdf->set_paper("letter", "landscape");
  $dompdf->render();
  $dompdf->stream("Reporte_Comisiones.pdf"); 

?>

