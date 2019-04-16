<?php
if (isset($_GET['por']) and $_GET['por'] <> ""){
      $por = $_GET['por'];
  }else{
    $por="";
  }

  if (isset($_GET['x']) and $_GET['x'] <> ""){
      $code = $_GET['x'];
  }else{
    $code="";
  }
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title></title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!--<link rel="stylesheet" type="text/css" href="assets/js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-daterangepicker/daterangepicker.css" />-->
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link href="assets/css/datepicker3.css" rel="stylesheet">
    <link href="assets/css/load.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

   <section class="wrapper">
          	<h3><i class="fa fa-angle-right"></i> Porcentaje de comisiones por vendedor</h3>
          	
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
          		<div class="col-lg-12">
                  <div class="form-panel">
                  	  <h4 class="mb"><i class="fa fa-angle-right"></i> Modificar </h4>
                      <form class="form-horizontal style-form" method="get">
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Porcentaje <?php echo $por; ?></label>
                              <div class="col-sm-10">
                                  <input type="number" class="form-control" id="Agente" pattern="" maxlength="6"  required="">
                              </div>
                          </div>
                          
                          <button type="button" class="btn btn-primary" onclick="">Guardar</button>
                      </form>
                  </div>
          		</div><!-- col-lg-12-->      	
          	</div><!-- /row -->
          	
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
                    <p>Favor de indicar las iniciales del Agente</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" value="no" data-dismiss="modal">Cerrar</button>                               
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
     

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>

	<!--custom switch-->
	<script src="assets/js/bootstrap-switch.js"></script>
	
	<!--custom tagsinput-->
	<script src="assets/js/jquery.tagsinput.js"></script>
	
	<script type="text/javascript" src="assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
	
	<script src="assets/js/form-component.js"></script>    
    
    
  <script>
      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });


        function MostrarContenido(){

            Agente = $("#Agente").val();
            /*FechaInicio = $("#FechaInicio").val();
            FechaFin = $("#FechaFinal").val();*/

            $("#mostrarContenido").css("display", "none");
            $("#contenedor").css("display", "inline");

            if (typeof Agente == "undefined" || Agente == null ||  Agente == ""  ) {
                  
                    $("#modalcorreo").modal(); 
                    $("#contenedor").css("display", "none");
                    //alert("Favor de indicar los parametros de busqueda (Agente, Fecha inicio, Fecha Fin) ");
                    return false;

            }else{

                     $.post("Vendedores_Resultado.php", { Agente : Agente}, function(data){
                            $("#mostrarContenido").html(data);
                            $("#mostrarContenido").show();
                           });

                    $("#contenedor").css("display", "none");
            }

        }


      /*function soloLetras(e){
         key = e.keyCode || e.which;
         tecla = String.fromCharCode(key).toLowerCase();
         letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
         especiales = "8-37-39-46";

         tecla_especial = false
         for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
         }

         if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
         }
      }*/

  </script>

