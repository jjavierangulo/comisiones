<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="">

    <title>Reporte de Comisiones Área de Ventas</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">    
    
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <link href="assets/css/datepicker3.css" rel="stylesheet">
    <link href="assets/css/load.css" rel="stylesheet">


    <script src="assets/js/chart-master/Chart.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>DUES</b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
         
                <!--  notification end -->
            </div>
            <!--<div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="login.html">Logout</a></li>
            	</ul>
            </div>-->
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
              	  <p class="centered"><a ><img src="http://192.168.1.20/registromedidas/dues/img/logo_dues.png" class="img-responsive" width="70%" style="display: -webkit-inline-box;"></a></p>
              	  <h5 class="centered">Reporte de Comisiones</h5>
              	  	
                  <!--<li class="mt">
                      <a class="active" href="index.php?opcion=a">
                          <i class="fa fa-dashboard"></i>
                          <span>Inicio</span>
                      </a>
                  </li>-->

                  <li class="mt">
                      <a class="active" href="index.php?opcion=reporte" >
                          <i class="fa fa-th"></i>
                          <span>Comisiones</span>
                      </a>
                  </li>

                  <li class="mt">
                      <a class="" href="index.php?opcion=porcentaje" >
                          <i class="fa fa-align-left"></i>
                          <span>Porcentajes</span>
                      </a>
                  </li>

                  <li class="mt">
                      <a class="" href="index.php?opcion=menuConta" >
                          <i class="fa fa-money"></i>
                          <span>Contabilidad</span>
                      </a>
                  </li>


                  <li class="mt">
                      <a class="" href="index.php?opcion=ModificarPswd" >
                          <i class="fa fa-key"></i>
                          <span>Modificar Contraseña</span>
                      </a>
                  </li>
                 
                 

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <?php

          if (isset($_GET['opcion'])){
            
            switch ($_GET['opcion']) {

              case 'reporte':
                  include("ReporteComisiones_Filtros.php");
                  break;

              case 'porcentaje':
                  include("Vendedores_Filtros.php");
                  break;


              case 'contabilidad':
                  include("ReporteContabilidad_Filtros.php");
                  break;


              case 'ModificarPswd':
                  include("Modificapswd.php");
                  break;


              case 'PruebasCons':
                  include("Vendedores_Modificacion.php");
                  break;

              case 'dh':
                  include("ReporteDH_Filtros.php");
                  break;

              case 'menuConta':
                  include("MenuContabilidad.php");
                  break;

              case 'Cien':
                  include("ReporteCien_Filtros.php");
                  break;


              default:
                 // include("contenido.php");
              include("ReporteComisiones_Filtros.php");

            }

        }else{
          //include("contenido.php");
          include("ReporteComisiones_Filtros.php");
        }

          ?>


      </section>

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2016 - Dues
              <a href="index.html#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-1.8.3.min.js"></script>
    <!--<script src="/assets/js/libs/jquery/jquery-1.11.2.min.js"></script>-->

    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>

    <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
            // When the document is ready
            //$.noConflict();
            jQuery( document ).ready(function( $ ) {
                
                $('#FechaInicio').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es",
                    autoclose: true
                });  

                  $('#FechaFinal').datepicker({
                    format: "yyyy-mm-dd",
                    language: "es",
                    autoclose: true
                });  
            
            });

            
        </script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>
    
    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>

    <!--script for this page-->
    <script src="assets/js/sparkline-chart.js"></script>    
	  <script src="assets/js/zabuto_calendar.js"></script>	

    <!--custom switch-->
  <script src="assets/js/bootstrap-switch.js"></script>
  <!--custom tagsinput-->
  <script src="assets/js/jquery.tagsinput.js"></script>
  <!-- amm  -->
  <script type="text/javascript" src="assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
  <!--<script src="assets/js/form-component.js"></script> -->


  <script>
      //custom select box

      /*$(function() {
          $('select.styled').customSelect();
      });*/


      function MostrarContenido() {

         Agente = $("#Agente").val();
         FechaInicio = $("#FechaInicio").val();
         FechaFin = $("#FechaFinal").val();
         Clave = $("#Clave").val();
         
         $("#mostrarContenido").css("display", "none");
         $("#contenedor").css("display", "inline");

         if (typeof Agente == "undefined" || Agente == null ||  Agente == ""  ||
            typeof FechaInicio == "undefined" || FechaInicio == null  ||  FechaInicio == ""  ||
            typeof FechaFin == "undefined" || FechaFin == null || FechaFin == "" ||
            typeof Clave == "undefined" || Clave == null || Clave == "" ) {
                  
            $("#modalcorreo").modal(); 
            $("#contenedor").css("display", "none");
                 //alert("Favor de indicar los parametros de busqueda (Agente, Fecha inicio, Fecha Fin) ");
            return false;

         } else {

            $.post("ReporteComisiones_Resultado.php", { Agente : Agente, FechaInicio : FechaInicio, FechaFin : FechaFin, Clave : Clave }, function(data){
                   $("#mostrarContenido").html(data);
                   $("#mostrarContenido").show();
                  });

           $("#contenedor").css("display", "none");
         }

      }



      function MostrarContabilidad() {

         Agente = $("#Agente").val();
         FechaInicio = $("#FechaInicio").val();
         FechaFin = $("#FechaFinal").val();
         Clave = $("#Clave").val();
         Usuario = $("#Usuario").val();
         
         $("#mostrarContenido").css("display", "none");
         $("#contenedor").css("display", "inline");

         if (typeof Agente == "undefined" || Agente == null ||  Agente == ""  ||
            typeof FechaInicio == "undefined" || FechaInicio == null  ||  FechaInicio == ""  ||
            typeof FechaFin == "undefined" || FechaFin == null || FechaFin == "" ||
            typeof Clave == "undefined" || Clave == null || Clave == "" ||
            typeof Usuario == "undefined" || Usuario == null || Usuario == "" ) {
                  
            $("#modalcorreo").modal(); 
            $("#contenedor").css("display", "none");
                 //alert("Favor de indicar los parametros de busqueda (Agente, Fecha inicio, Fecha Fin) ");
            return false;

         } else {

            $.post("ReporteContabilidad_Resultado.php", { Agente : Agente, FechaInicio : FechaInicio, FechaFin : FechaFin, Clave : Clave, Usuario : Usuario }, function(data){
                   $("#mostrarContenido").html(data);
                   $("#mostrarContenido").show();
                  });

           $("#contenedor").css("display", "none");
         }

      }


      function MostrarContenidoDH() {

        Agente = $("#Agente").val();
        FechaInicio = $("#FechaInicio").val();
        FechaFin = $("#FechaFinal").val();
        Clave = $("#Clave").val();

        var myCheckboxes = new Array();
        $('input[name=myCheckboxes]:checked').each(function() {
          //alert("Valor myCheckboxes" + $(this).val());
          myCheckboxes.push($(this).val());
        });


        var Agentes = new Array();
        $('input[name=Agentes]:checked').each(function() {
          //alert("Valor Agentes " + $(this).val());
          Agentes.push($(this).val());
        });

        //alert(Agentes);
        
         
         $("#mostrarContenido").css("display", "none");
         $("#contenedor").css("display", "inline");

         if (typeof Agente == "undefined" || Agente == null ||  Agente == ""  ||
            typeof FechaInicio == "undefined" || FechaInicio == null  ||  FechaInicio == ""  ||
            typeof FechaFin == "undefined" || FechaFin == null || FechaFin == "" ||
            typeof Clave == "undefined" || Clave == null || Clave == "" ) {
                  
            $("#modalcorreo").modal(); 
            $("#contenedor").css("display", "none");
                 //alert("Favor de indicar los parametros de busqueda (Agente, Fecha inicio, Fecha Fin) ");
            return false;

         } else {

            $.post("ReporteDH_Resultado.php", { Agente : Agente, FechaInicio : FechaInicio, FechaFin : FechaFin, Clave : Clave, myCheckboxes:myCheckboxes, Agentes : Agentes }, function(data){ //, myCheckboxes:myCheckboxes, Agentes : Agentes
                   $("#mostrarContenido").html(data);
                   $("#mostrarContenido").show();
                  });

           $("#contenedor").css("display", "none");
         }

      }


      function soloLetras(e) {
         key = e.keyCode || e.which;
         tecla = String.fromCharCode(key).toLowerCase();
         letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
         especiales = "8-37-39-46";

         tecla_especial = false
         for(var i in especiales) {
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
         }

         if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
         }
      }


      function MostrarContenidoCien() {

        //Agente = $("#Agente").val();
        Usuario = $("#Usuario").val();
        FechaInicio = $("#FechaInicio").val();
        FechaFin = $("#FechaFinal").val();
        Clave = $("#Clave").val();

        var Clientes = new Array();
        $('input[name=Cliente]:checked').each(function() {
          Clientes.push($(this).val());
        });

        var Agente = new Array();
        $('input[name=Agentes]:checked').each(function() {
          Agente.push($(this).val());
        });
   
        $("#mostrarContenido").css("display", "none");
        $("#contenedor").css("display", "inline");

        if ( Agente.length <= 0  || FechaInicio.length <= 0  || FechaFin.length <= 0 || Clave.length <= 0 || Usuario.length <= 0 || Clientes.length === 0) {
                  
          $("#modalcorreo").modal(); 
          $("#contenedor").css("display", "none");
          return false;

        } else {
            $.post("ReporteCien_Resultado.php", { Agente : Agente, FechaInicio : FechaInicio, FechaFin : FechaFin, Clave : Clave, Clientes:Clientes, Usuario : Usuario }, function(data){ //, myCheckboxes:myCheckboxes, Agentes : Agentes
                   $("#mostrarContenido").html(data);
                   $("#mostrarContenido").show();
                  });

           $("#contenedor").css("display", "none");
         }

      }

      function MostrarPorcentajes(){

            Agente = $("#Agente").val();
            Clave = $("#Clave").val();
            /*FechaInicio = $("#FechaInicio").val();
            FechaFin = $("#FechaFinal").val();*/

            $("#mostrarContenido").css("display", "none");
            $("#contenedor").css("display", "inline");

            if (typeof Agente == "undefined" || Agente == null ||  Agente == "" ||
                typeof Clave == "undefined" || Clave == null || Clave == ""  ) {
                  
                    $("#modalcorreo").modal(); 
                    $("#contenedor").css("display", "none");
                    //alert("Favor de indicar los parametros de busqueda (Agente, Fecha inicio, Fecha Fin) ");
                    return false;

            }else{

                     $.post("Vendedores_Resultado.php", { Agente : Agente,  Clave : Clave}, function(data){
                            $("#mostrarContenido").html(data);
                            $("#mostrarContenido").show();
                           });

                    $("#contenedor").css("display", "none");
            }

      }


    function Modificar() {

      var Agente = $('#Agente').val();
      var Clave = $('#pass_anterior').val();
      
      var pass = $('#pass_1').val();
      var pass2 = $('#pass_2').val();

      var Actualizar = "false";

      if(pass != pass2 && pass2 != ''){ 
        alert("Contraseñas no coinciden");
        return;
      }
     
     
      if (typeof pass == "undefined" || pass == null  ||  pass == ""  ||
          typeof Agente == "undefined" || Agente == null || Agente == "" ||
          typeof Clave == "undefined" || Clave == null || Clave == "" ||
          typeof pass2 == "undefined" || pass2 == null  ||  pass2 == "" ) {


        $("#modalcorreo").modal(); 
        //alert("Favor de capturar todos los datos");

      } else {


        $.post("ModificapswdUpd.php", { Agente : Agente, pass : pass, Clave : Clave }, function(data){
                 $("#mostrarContenido").html(data);
                 
                 $("#MensajeRespuesta").html(data);
                 
                 $("#modalRespuesta").modal();
                 $("#mostrarContenido").show();
                 $("#form")[0].reset();

                 //$("#MensajeRespuesta").empty();
                });

      }

   
    }

  </script>


  <script>
      $(function(){
         $('#Agente').keyup(function(){
            var _this = $('#Agente');
            var _user = $('#Agente').val();
            _this.attr('style', 'background:white');
            if(_user.indexOf(' ') >= 0){
               _this.attr('style', 'background:#FF4A4A');
            }

            if(_user.indexOf("'") >= 0){
               _this.attr('style', 'background:#FF4A4A');
            }

            if(_user == ''){
               _this.attr('style', 'background:#FF4A4A');
            }
            
         });
         
         
         $('#pass_1').keyup(function(){
            var _this = $('#pass_1');
            var pass_1 = $('#pass_1').val();
            _this.attr('style', 'background:white');
            if(pass_1.charAt(0) == ' '){
               _this.attr('style', 'background:#FF4A4A');
            }
      
            if(_this.val() == ''){
               _this.attr('style', 'background:#FF4A4A');
            }
         });
      
         $('#pass_2').keyup(function(){
            var pass_1 = $('#pass_1').val();
            var pass_2 = $('#pass_2').val();
            var _this = $('#pass_2');
            _this.attr('style', 'background:white');
            if(pass_1 != pass_2 && pass_2 != ''){
               _this.attr('style', 'background:#FF4A4A');
            }
         });


          $('.button-checkbox').each(function () {
          // Settings
          var $widget = $(this),
              $button = $widget.find('button'),
              $checkbox = $widget.find('input:checkbox'),
              color = $button.data('color'),
              settings = {
                  on: {
                      icon: 'glyphicon glyphicon-check'
                  },
                  off: {
                      icon: 'fa fa-square-o'
                  }
              };

          // Event Handlers
          $button.on('click', function () {
              $checkbox.prop('checked', !$checkbox.is(':checked'));
              $checkbox.triggerHandler('change');
              updateDisplay();
          });
          $checkbox.on('change', function () {
              updateDisplay();
          });

          // Actions
          function updateDisplay() {
              var isChecked = $checkbox.is(':checked');

              // Set the button's state
              $button.data('state', (isChecked) ? "on" : "off");

              // Set the button's icon
              $button.find('.state-icon')
                  .removeClass()
                  .addClass('state-icon ' + settings[$button.data('state')].icon);

              // Update the button's color
              if (isChecked) {
                  $button
                      .removeClass('btn-default')
                      .addClass('btn-' + color + ' active');
              }
              else {
                  $button
                      .removeClass('btn-' + color + ' active')
                      .addClass('btn-default');
              }
          }

          // Initialization
          function init() {

              updateDisplay();

              // Inject the icon if applicable
              if ($button.find('.state-icon').length == 0) {
                  $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
              }
          }
          init();
        });






        $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
          $(this).parent().parent().find('div.product-chooser-item').removeClass('selected');
          $(this).addClass('selected');
          $(this).find('input[type="radio"]').prop("checked", true);
    
        });
            
      });

   

   </script>
	

  </body>
</html>
