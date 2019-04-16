
<div class="container bs-docs-container" style="WIDTH: 95%;">
		<?php
			include_once("cons.php");

			
			echo '  <div class="">';


			$ArregloCons = TraerConsulta();
			$Filas = count($ArregloCons);

			echo '<div class="table-responsive">';
				echo '<table id="keywords" class="table table-bordered table-striped">';	
					echo "<caption>Reporte de comisiones Agente: Gisela Garzon (GLNG) Fecha: 28-Dic-2015 al 30-Dic-2015</caption>";
					echo "<thead>
							<tr>";
						echo "<th> <span>RBO </span></th> ".
			       			 "<th> <span>FRECIBO</span></th> " .
			       			 "<th> <span>AGTE</span></th>" .
			       			 "<th> <span>FACT</span></th> " .
			       			 "<th> <span>PEDIDO</span></th> " .
			       			 "<th> <span>NOMBRE</span></th> " .
			       			 "<th> <span>COBRANZA</span></th> " .
			       			 "<th> <span>ANTICIPO</span></th> " .
			       			 "<th> <span>INSTALACION</span></th> " .
			       			 "<th> <span>VIATICOS</span></th> " .
			       			 "<th> <span>IPCLIENTE</span></th> " .
			       			 "<th> <span>DESCTO</span></th> " .
			       			 "<th> <span>COMISIONABLE</span></th> " .
			       			 "<th> <span>CONT</span></th> " .
			       			 "<th> <span>SEG</span></th> " .
			       			 "<th> <span>CIERRE</span></th> " .
			       			 "<th> <span>ENT</span></th> " .
			       			 "<th> <span>VIST</span></th> " .
			       			 "<th> <span>TOTAL</span></th>";
					echo "	</tr>
						  </thead>";	

					echo " <tbody>";
				
					for( $i=0; $i<$Filas; $i++){
						echo "<tr>";
				       		echo "<td> ". $ArregloCons[$i]['RBO'] . "</td> " .
				       			 "<td> ". $ArregloCons[$i]['FRECIBO'] . "</td> " .
				       			 "<td> ". $ArregloCons[$i]['AGTE'] ."</td>" .
				       			 "<td> ". $ArregloCons[$i]['FACT'] . "</td> " .
				       			 "<td> ". $ArregloCons[$i]['PEDIDO'] . "</td> " .
				       			 "<td> ". $ArregloCons[$i]['NOMBRE'] . "</td> " .
				       			 "<td style='text-align: right;'> $". number_format($ArregloCons[$i]['COBRANZA'], 2, '.', ',') . "</td> " .
				       			 "<td style='text-align: right;'> $". number_format($ArregloCons[$i]['ANTICIPO'], 2, '.', ',') . "</td> " .
				       			 "<td style='text-align: right;'> $". number_format($ArregloCons[$i]['INSTALACION'], 2, '.', ',') . "</td> " .
				       			 "<td style='text-align: right;'> $". number_format($ArregloCons[$i]['VIATICOS'], 2, '.', ',') . "</td> " .
				       			 "<td> ". utf8_encode($ArregloCons[$i]['IPCLIENTE']) . "</td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['DESCTO'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> ". number_format($ArregloCons[$i]['COMISIONABLE'], 2, '.', ',')  . "</td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['CONT'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['SEG'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['CIERRE'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['ENT'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> ". round($ArregloCons[$i]['VIST'],3) . "% </td> " .
				       			 "<td style='text-align: right;'> $". number_format($ArregloCons[$i]['TOTAL'], 2, '.', ',') ."</td>";
				       	echo "</tr>";
					}
				echo " </tbody>";
				echo " <tfoot><tr></tr></tfoot>" . 
				"</table>";
			echo '</div>';

?>
	<div>
</body>


