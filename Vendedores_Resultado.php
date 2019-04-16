<?php

  if (isset($_POST['Agente']) and $_POST['Agente'] <> ""){ $Agente = $_POST['Agente']; } else { $Agente=""; }
  if (isset($_POST['Clave']) and $_POST['Clave'] <> ""){ $Clave = $_POST['Clave']; } else { $Clave=""; }

   include_once("cnx/cons.php");
   include_once("cnx/cnx_mysql.php");

?>


    <?php

    $sql = "select iniciales, nombre, apellido from agentes where iniciales = '".$Agente."' and password=md5('".$Clave."') and estatus=1";
    $result = mysqli_query($GLOBALS["db"], $sql);

    if (mysqli_num_rows($result) > 0) { 
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC); 

      $ArregloCons = TraerPorcentajeVendedores($Agente);
      $Filas = count($ArregloCons);

    ?>
    
      <section class="">
  		  	<div class="row mt">
  			  	<div class="col-lg-12">
               <div class="content-panel">
                  <h4><i class="fa fa-angle-right"></i>Porcentajes</h4>
                  <section id="unseen">
                     <table class="table table-bordered table-striped table-condensed">
                        <thead>
                           <tr>
                           	<th>VENDEDOR</th>
                              <th>CLIENTE</th>
                              <th>COMISION</th>
                              <!--<th>MODIFICAR</th>-->
                           </tr>
                        </thead>
                        <tbody>

                          <?php  
                              //$TotalFinal = 0;
                              if ($Filas > 0){
                                 for( $i=0; $i<$Filas; $i++){
                                  echo "<tr>";
                                	  echo "<td>".($ArregloCons[$i]['VENDEDOR'])."</td>";
                                    echo "<td>".$ArregloCons[$i]['CLIENTE']."</td>";
                                    echo "<td>".$ArregloCons[$i]['COMISION']." %</td>";
                                    $variable = $ArregloCons[$i]['CLIENTE']; //$code = $ArregloCons[$i]['CODE'];
                                    //echo "<td><a href='index.php?opcion=modificar&por=$variable&x=$code'>MODIFICAR</a></td>";
                                  echo "</tr>";
                                 } 
                              } 

                          ?>
                      
                        </tbody>
                     </table>
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
 