<?php

  if (isset($_POST['Agente']) and $_POST['Agente'] <> ""){ $Agente = $_POST['Agente']; } else { $Agente=""; }
  if (isset($_POST['FechaInicio']) and $_POST['FechaInicio'] <> ""){ $FechaInicio = $_POST['FechaInicio']; } else { $FechaInicio =""; }
  if (isset($_POST['FechaFin']) and $_POST['FechaFin'] <> ""){ $FechaFin = $_POST['FechaFin']; } else { $FechaFin=""; }
  if (isset($_POST['Clave']) and $_POST['Clave'] <> ""){ $Clave = $_POST['Clave']; } else { $Clave=""; }
  if (isset($_POST['Usuario']) and $_POST['Usuario'] <> ""){ $Usuario = $_POST['Usuario']; } else { $Usuario=""; }

  include_once("cnx/cons.php");
  include_once("cnx/cnx_mysql.php");

?>
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->

      <?php

      $sql = "select iniciales, nombre, apellido from agentes where iniciales = '".$Usuario."' and estatus=1 and password=md5('".$Clave."') and tipo=0";
      $result = mysqli_query($GLOBALS["db"], $sql);
      
      if (mysqli_num_rows($result) > 0) { 
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC); 
         
        $ArregloCons = TraerConsulta($Agente,$FechaInicio,$FechaFin);
        $Filas = count($ArregloCons);

      ?>
      
          <section class="">
  		  	  <div class="row mt">
  			  	  <div class="col-lg-12">
                <div class="content-panel">
                  <h4><i class="fa fa-angle-right"></i>Comisiones </h4>
                    <?php
                      if ($Filas > 0 )
                      {
                        echo "<div class='alert alert-success'><a href='descargarExcel.php?Agente=".$Agente."&FechaInicio=".$FechaInicio."&FechaFin=".$FechaFin."'>Exportar a Excel</a></div>";
                        echo "<div class='alert alert-success'><a href='descargarPdf.php?Agente=".$Agente."&FechaInicio=".$FechaInicio."&FechaFin=".$FechaFin."'>Exportar a PDF</a></div>";
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
                              <th class="numeric">FLETES</th>
                              <th class="">IPCLIENTE</th>
                              <th class="numeric">DESCTO</th>
                              <th class="numeric">COMISIONABLE</th>
                              <th class="numeric">CONT</th>
                              <th class="numeric">SEG</th>
                              <th class="numeric">CIERRE</th>
                              <th class="numeric">ENT</th>
                              <th class="numeric">VIST</th>
                              <th class="numeric">TOTAL</th>
                            </tr>
                          </thead>
                          

                          <tbody>

                          <?php  
                              $TotalFinal = 0;
                              for( $i=0; $i<$Filas; $i++)
                              {

                                echo "<tr>";
                                    echo "<td class='numeric'>".$ArregloCons[$i]['RBO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['FRECIBO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['AGTE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['FACT']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['PEDIDO']."</td>";
                                    echo "<td>".$ArregloCons[$i]['NOMBRE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['COBRANZA'], 2, '.', ', ')."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['ANTICIPO'], 2, '.', ', ')."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['INSTALACION'], 2, '.', ', ')."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['VIATICOS'], 2, '.', ', ')."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>$ ".number_format($ArregloCons[$i]['FLETES'], 2, '.', ', ')."</td>";
                                    echo "<td>".$ArregloCons[$i]['IPCLIENTE']."</td>";
                                    echo "<td class='numeric' style='TEXT-ALIGN: RIGHT;'>".$ArregloCons[$i]['DESCTO']."</td>";
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
                                echo "</tr>";

                                if ($ArregloCons[$i]['TIPO'] == 'C')
                                {
                                  $TotalFinal = $TotalFinal - $ArregloCons[$i]['TOTAL'];
                                }
                                else
                                {
                                  $TotalFinal = $TotalFinal + $ArregloCons[$i]['TOTAL']; 
                                }

                               }

                               echo "<tr><td></td><td></td><td></td><td></td><td></td><td><td></td></td><td></td><td></td><td></td><td></td>
                                      <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total:</td><td style='TEXT-ALIGN: RIGHT;'><h4><b>$ ". number_format($TotalFinal, 2, '.', ', ') ."</b></h4></td></tr>"
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
      } else {

      ?>


      <section class="">
            <div class="row mt">
              <div class="col-lg-12">
                <div class="content-panel">
                  <h4 style="color: red;"><i class="fa fa-angle-right"></i> El usuario o la contraseña es incorrecta. Favor de verificar</h4>
                </div>
              </div>
            </div>
          </div>
        </section>


      <?php


      }

      ?>
   

      <!--main content end-->
    

