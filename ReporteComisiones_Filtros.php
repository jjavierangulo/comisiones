
         <section class="wrapper">
          	<h3><i class="fa fa-angle-right"></i> Reporte de comisiones Ventas</h3>
          	
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
          		<div class="col-lg-12">
                  <div class="form-panel">
                  	<h4 class="mb"><i class="fa fa-angle-right"></i> Favor de poner los datos correspondientes</h4>
                     <form class="form-horizontal style-form" method="get">
                        <div class="form-group">
                           <label class="col-sm-2 col-sm-2 control-label">Agente de Venta (iniciales)</label>
                           <div class="col-sm-10">
                              <input type="text" class="form-control" id="Agente" pattern="[A-Z]+" maxlength="6"  required="" onkeyup = "this.value=this.value.toUpperCase()" onkeypress="return soloLetras(event)">
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-2 col-sm-2 control-label">Contraseña*</label>
                           <div class="col-sm-10">
                               <input type="password" class="form-control" id="Clave" >
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-2 col-sm-2 control-label">Fecha inicio</label>
                           <div class="col-sm-10">
                              <input type="text" class="form-control" id="FechaInicio">   
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-2 col-sm-2 control-label">Fecha final</label>
                           <div class="col-sm-10">
                              <input type="text" class="form-control" id="FechaFinal">
                           </div>
                        </div>


                        <button type="button" class="btn btn-primary" onclick="MostrarContenido()">Mostrar Reporte</button>
                     </form>
                  </div>   
               </div>
          	</div><!-- col-lg-12-->      	

          	<!-- INLINE FORM ELELEMNTS -->
          	
          
            <div id="contenedor" style="display:none;">
                <div  class="loader" id="loader">Loading...</div>
            </div>

          	<div id="mostrarContenido"></div>
          
          	
          	
		   </section><!--/wrapper -->



         <div class="modal fade" id="modalcorreo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="simpleModalLabel">Información</h4>
                  </div>
                  <div class="modal-body">
                     <p>Favor de indicar los parametros de busqueda <b>(Agente, Clave, Fecha inicio, Fecha Fin)</b></p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" value="no" data-dismiss="modal">Cerrar</button>                               
                  </div>
               </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->
     
   
    
    
  

