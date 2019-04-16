<?php

  if (isset($_POST['Agente']) and $_POST['Agente'] <> ""){ $Agente = $_POST['Agente']; } else { $Agente=""; }
  if (isset($_POST['FechaInicio']) and $_POST['FechaInicio'] <> ""){ $FechaInicio = $_POST['FechaInicio']; } else { $FechaInicio =""; }
  if (isset($_POST['FechaFin']) and $_POST['FechaFin'] <> ""){ $FechaFin = $_POST['FechaFin']; } else { $FechaFin=""; }
  if (isset($_POST['Clave']) and $_POST['Clave'] <> ""){ $Clave = $_POST['Clave']; } else { $Clave=""; }

  include_once("cnx/cons_DH.php");
  include_once("cnx/tipocliente.php");
  include_once("cnx/cnx_mysql.php");

?>
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->

<section class="">
   <div class="row mt">
      <div class="col-lg-12">
         <div class="content-panel">
            <h4><i class="fa fa-angle-right"></i>Comisiones DUES HOME </h4>
         </div>
      </div>
   </div>
</section>

<?php

   $sql = "select iniciales, nombre, apellido from agentes where iniciales = '".$Agente."' and password=md5('".$Clave."') and estatus=1 and tipo = 0";
   $result = mysqli_query($GLOBALS["db"], $sql);

   if (mysqli_num_rows($result) > 0) { 

      $Tipocliente = array();
      $Tipocliente = (isset($_POST['myCheckboxes'])) ? $_POST['myCheckboxes']  : ""; 

      foreach ($Tipocliente as $cliente) {
            $VarCliente.=  "". $cliente . ",";
            $GananciaAux =  MostrarGanancia($cliente);
            $Ganancia .= "" . $GananciaAux .",";
      }
      $VarCliente = trim($VarCliente, ',');
      $Ganancia = trim($Ganancia, ',');

      foreach ($Tipocliente as $Nombrecliente) {
         
         $Agentes = array();  
         $VarAgente = "";
         $VarAgenteAux ="";
         $Agentes = (isset($_POST['Agentes'])) ? $_POST['Agentes'] : ""; 

         foreach ($Agentes as $iniciales) {
            $VarAgente.=  "'". $iniciales . "',";
            $VarAgenteAux.=  "". $iniciales . ",";
         }  
         $VarAgente = trim($VarAgente, ',');
         $VarAgenteAux = trim($VarAgenteAux, ',');
       
         $ArregloCons = TraerConsulta($FechaInicio,$FechaFin,$Nombrecliente,$VarAgente);
         $Filas = count($ArregloCons);

         //print_r($VarAgenteAux);

?>
      
         <section class="">
  		  	   <div class="row mt">
  			  	   <div class="col-lg-12">
                  <div class="content-panel">
                     <h4><i class="fa fa-angle-right"></i><?php echo $Nombrecliente; ?></h4>
                     <?php
                     if ($Filas > 0) {
                        echo "<div class='alert alert-success'><a href='descargarExcel_DH.php?FechaInicio=".$FechaInicio."&FechaFin=".$FechaFin."&TipoCliente=".$VarCliente."&Agente=".$VarAgenteAux."&Ganancia=".$Ganancia."'>Exportar a Excel</a></div>";
                     }
                     ?>
                     <section id="unseen">
                        <div class="table-responsive">
                           <table class="table table-bordered table-striped table-condensed">
                              <thead>
                                 <tr>
                                    <th>RBO</th>
                                    <th>FRECIBO</th>
                                    <th class="">AGTE</th>
                                    <th class="">FACT</th>
                                    <th class="numeric">PEDIDO</th>
                                    <th class="">NOMBRE</th>
                                    <th class="numeric">COBRANZA</th>
                                    <th class="numeric">ANTICIPO</th>
                                    <th class="numeric">INSTALACION</th>
                                    <th class="numeric">VIATICOS</th>
                                    <th class="">IPCLIENTE</th>
                                    <th class="numeric">DESCTO</th>
                                    <th class="numeric">VENTA</th>
                                    <th class="numeric">DESCTO2</th>
                                    <th class="numeric">COMISIONABLE</th>
                                    <th class="numeric">CONT</th>
                                    <th class="numeric">SEG</th>
                                    <th class="numeric">CIERRE</th>
                                    <th class="numeric">ENT</th>
                                    <th class="numeric">VIST</th>
                                    <th class="numeric">TOTAL</th>
                                    <th class="numeric">COMISION</th>
                                 </tr>
                              </thead>
                          
                              <tbody>
                              <?php
                              $VendedorAux ="";
                              $TotalFinal = 0;
                              for( $i=0; $i<$Filas; $i++) {
                               
                                 if ($VendedorAux<>$ArregloCons[$i]['VENDEDORCOMISION']) 
                                 {
                                    echo "<tr><td><h4><b>".$ArregloCons[$i]['VENDEDORCOMISION']."</b></h4></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                          <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td style='TEXT-ALIGN: RIGHT;'><h4><td></td></h4></td></tr>";
                                 }
                                 echo "<tr>";
                                    echo "<td class='numeric'>".$ArregloCons[$i]['RBO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['FRECIBO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['AGTE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['FACT']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['PEDIDO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['NOMBRE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".$ArregloCons[$i]['COBRANZA']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".$ArregloCons[$i]['ANTICIPO']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".$ArregloCons[$i]['INSTALACION']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".$ArregloCons[$i]['VIATICOS']."</td>";
                                    echo "<td>".$ArregloCons[$i]['IPCLIENTE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['DESCTO']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['VENTA']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['DESCTO2']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['COMISIONABLE'], 2, '.', ', ')."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['CONT']."%</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['SEG']."%</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['CIERRE']."%</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['ENT']."%</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['VIST']."%</td>";
                                    if ($ArregloCons[$i]['TIPO'] == 'C'){
                                      echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>- $ ".number_format($ArregloCons[$i]['TOTAL'], 2, '.', ', ')."</td>";
                                    }
                                    else{
                                      echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['TOTAL'], 2, '.', ', ')."</td>";
                                    }
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['VENDEDORCOMISION']."</td>";
                                echo "</tr>";

                                /*if ($ArregloCons[$i]['TIPO'] == 'C') { $TotalFinal = $TotalFinal - $ArregloCons[$i]['TOTAL']; }
                                else{ $TotalFinal = $TotalFinal + $ArregloCons[$i]['TOTAL']; }*/

                                 $TotalFinal = ($ArregloCons[$i]['TIPO'] == 'C') ?   $TotalFinal - $ArregloCons[$i]['TOTAL'] : $TotalFinal + $ArregloCons[$i]['TOTAL'];
                                 $VendedorAux = $ArregloCons[$i]['VENDEDORCOMISION'];

                              }

                              echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total:</td><td style='TEXT-ALIGN: RIGHT;'><h4><b>$ ". number_format($TotalFinal, 2, '.', ', ') ."</b><td></td></h4></td></tr>";                              
                              ?>
                              </tbody>
                           </table>
                        </div>
                     </section>
                  </div><!-- /content-panel -->
               </div><!-- /col-lg-4 -->			
  		  	   </div><!-- /row -->
  		   </section><!--/wrapper -->

      <?php
         }
      } else {      ?>

         <section class="">
            <div class="row mt">
               <div class="col-lg-12">
                  <div class="content-panel">
                     <h4 style="color: red;"><i class="fa fa-angle-right"></i> El usuario o la contraseña es incorrecta. Favor de verificar</h4>
                  </div>  
               </div>
            </div>
         </section>

<?php    
      } 
?>
   

      <!--main content end-->
    

