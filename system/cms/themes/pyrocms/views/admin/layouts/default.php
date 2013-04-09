<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en"> 		   <![endif]-->

<head>
    <meta charset="utf-8">

    <!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php echo lang('cp_admin_title') . ' - ' . $template['title']; ?></title>

    <base href="<?php echo base_url(); ?>" />

    <!-- Mobile viewport optimized -->
    <meta name="viewport" content="width=device-width,user-scalable=no">

    <!-- CSS. No need to specify the media attribute unless specifically targeting a media type, leaving blank implies media=all -->
    <?php echo Asset::css('plugins.css'); ?>
    <?php echo Asset::css('workless/workless.css'); ?>
    <?php echo Asset::css('workless/application.css'); ?>
    <?php echo Asset::css('workless/responsive.css'); ?>
    <!-- End CSS-->

    <!-- Load up some favicons -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png">
    <link rel="apple-touch-icon" href="apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" href="apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" href="apple-touch-icon-114x114-precomposed.png">

    <!-- metadata needs to load before some stuff -->
    <?php file_partial('metadata'); ?>


    <!--BEGIN FRONTEND CODE-->

    <link rel="stylesheet" media="all" type="text/css" href="<?php echo BASE_URL . APPPATH ?>themes/pyrocms/css/jquery/ui-lightness/jquery-ui.css" />

    <style>
        .ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
        .ui-timepicker-div dl{ text-align: left; }
        .ui-timepicker-div dl dt{ height: 25px; }
        .ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
        .ui-timepicker-div td { font-size: 90%; }
    </style>

<!--<script src="<?php //echo BASE_URL.APPPATH   ?>themes/pyrocms/js/ckeditor/ckeditor.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="<?php //echo BASE_URL.APPPATH   ?>themes/pyrocms/js/ckeditor/skins/kama/editor.css?t=D08H" />-->
    <script src="<?php echo BASE_URL . APPPATH ?>themes/pyrocms/js/jquery-ui-timepicker.js"></script>


<!--		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>-->
<!--		<script type="text/javascript" src="../../../js/google_dynamic_map.js"></script>-->
    <script type="text/javascript" >
        /* jQuery(function($) {
         $('textarea[name="descripcion"]').ckeditor({
         toolbar: [
         ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
         ],
         width: '99%',
         height: 100,
         dialog_backgroundCoverColor: '#000',
         defaultLanguage: 'en',
         language: 'es'
         });
         });*/
        // GOOGLE MAPS
        /*function initialize() 
         {
         
         geocoder = new google.maps.Geocoder();
         var latlng = new google.maps.LatLng(37.7699298, -122.4469157);
         
         var myOptions = {
         zoom: 16,
         center: latlng,
         panControl: true,
         zoomControl: true,
         scaleControl: true,
         mapTypeId: google.maps.MapTypeId.ROADMAP
         }
         
         map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
         marker = new google.maps.Marker({
         map: map,
         draggable:true,	
         position: latlng
         });
         
         
         google.maps.event.addListener(marker, 'dragend', function() {
         
         
         var point = marker.getPosition();
         map.panTo(point);
         document.getElementById('txt_latlng').value=point.lat()+", "+point.lng();
         });
         }
         */

    </script>

    <style>
        .frm_content{ font-family:Arial, Helvetica, sans-serif;}
        .left_arm{
            width:46%;
            float:left;
            padding:2%;
        }
        .right_arm{
            width:46%;
            float:left;
            padding:2%;
        }
        .row_arm{
            width:92%;
            float:left;
            padding:2%;
        }


        label{
            width:100%;
            display:block;

        }

        .h_text{ display:none;}	
        .plus_item{}
        .main_opt{ width:100%;clear: both;}
        #loading
        {
            text-align: center;
            position:fixed;
            background-color: #ccc;
            width:120px;
            height:100px;
            padding:10px;
            display: none;
            -moz-border-radius: 20px; /* Firefox */
            -webkit-border-radius: 20px; /* Google Chrome y Safari */
            border-radius: 20px; /* CSS3 (Opera 10.5, IE 9 y estándar a ser soportado por todos los futuros navegadores) */    
        }
        #loadingModal{
            position: absolute;
            background-color: #000;
            filter: alpha(opacity=50); /* internet explorer */
            -khtml-opacity: 0.5;      /* khtml, version anterior de safari */
            -moz-opacity: 0.5;       /* firefox, mozilla, netscape */
            opacity: 0.5;           /* safari, chrome, opera */
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; /*internet explorer 8 - gracias Andres */     
        }
    </style>
    <script type="text/javascript" src="<?php echo BASE_URL . APPPATH . 'themes/pyrocms/js/jNotify/jquery/jNotify.jquery.js' ?>"></script>   
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL . APPPATH . 'themes/pyrocms/js/jNotify/jquery/jNotify.jquery.css' ?>" />
</head>

<body>
    <div id="loadingModal"></div>
    <div id="loading">
        <p><img id="imgLoading" src="uploads/imagenes/loading.gif" /></p><p> Espere por favor</p>
    </div>
    <div class="bajada2">
        <span class="boxgrid captionfull">	
            <div class="cover boxcaption">

                <div class="toggle-icon-finder">
                    <a href="#" class="linker">EXPANDER</a>
                    <a href="#" class="linker2 hide">CONTRAER</a>

                    <a href="#" class="plus_view"></a>	
                </div>
                <div class="search-box">
<!--                <span class="view_mc">BUSQUEDA</span>                          	  
                         <div class="frm-input">
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text" placeholder="Titulo">   
                               </div>
                               <br />
                               <br />
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text" placeholder="Categoria">
                               </div>
                               <br />
                               <br />
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text"  placeholder="Tipo">
                               </div>
                               <br />
                               <br />
                               <a href="#" id="s" name="s" class="btn blue">
                                   <span class="st">Buscar</span>
                               
                               </a>
                    </div>-->
                </div>   


            </div>
        </span>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            var altura = $(document).height();
            $(".bajada2").css('height', altura);
            $(".cover").css("width", "2%");
            $('.linker').live("click", function(event) {
                event.preventDefault();
                $(".cover").stop().animate({width: "100%"}, 130);
                $(".bajada2").stop().animate({width: "35%"}, 130);
                $('.linker2').removeClass("hide");
                $('.linker').addClass("hide");
                //cargamos el formulario mediando ajax dependiendo de la ubicacion del cms
                var pathArray = window.location.pathname.split( '/' );
                //pathArray ="'"+pathArray+"'";
                console.log(escape(pathArray));
                //var dato = pathArray.serialize();
                var post_url = "<?php echo BASE_URL;?>admin/canales/formulario_busqueda/"+escape(pathArray);
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'html',
                    //data: escape(pathArray),
                    success: function(respuesta)
                    {
                       $(".search-box").html(respuesta);
                    } //end success
                }); //end AJAX 

                $(".search-box").fadeIn("slow", function() {
                    $(".plus_view").fadeIn("slow", function() {
                    });
                });
            });
            $('.linker2').live("click", function(event) {
                event.preventDefault();
                $(".plus_view").fadeOut("slow", function() {

                });
                $(".search-box").fadeOut("slow", function() {
                    $(".cover").stop().animate({width: "2%"}, 130);
                    $(".bajada2").stop().animate({width: "2%"}, 130);
                    $('.linker').removeClass("hide");
                    $('.linker2').addClass("hide");
                });
            });
            $('.plus_view').live("click", function(event) {
                event.preventDefault();

                $(".bajada2").stop().animate({width: "100%"}, 130);
                $(".cover").stop().animate({width: "100%"}, "fast", function() {
                    $(".plus_view").fadeOut("fast", function() {

                    });
                });
            });
            $(".plus_item").each(function() {
                $(this).click(function(event) {
                    event.preventDefault();/* alert();*/
                    $(this).parent().find($(".h_text")).fadeIn("slow", function() {
                    });
                });
            });
        });
    </script>

    <!--END CODE FRONTEND-->              

    <div id="container">

        <section id="content">

            <?php file_partial('header'); ?>

            <div id="content-body">

                <?php file_partial('notices'); ?>
                <?php echo $template['body']; ?>


            </div>

        </section>

    </div>

    <footer>
        <div class="wrapper">
<!--			<p>Copyright &copy; 2009 - <?php //echo date('Y');   ?> PyroCMS &nbsp; -- &nbsp; Version <?php //echo CMS_VERSION.' '.CMS_EDITION;   ?> &nbsp; -- &nbsp; Rendered in {elapsed_time} sec. using {memory_usage}.</p>-->
            <span id="logo2">El Comercio</span>
            <p>Copyright &copy; <?php echo date('Y'); ?>  &nbsp; El Comercio  -- &nbsp; 
                <!--Version--> <?php /* echo CMS_VERSION.' '.CMS_EDITION; */ ?> &nbsp;  &nbsp;<!-- Rendered in {elapsed_time} sec. using {memory_usage}.--></p>

            <!--
            <ul id="lang">
                <form action="<?php //echo current_url(); ?>" id="change_language" method="get">
                    <select class="chzn" name="lang" onchange="this.form.submit();">
                        <?php //foreach ($this->config->item('supported_languages') as $key => $lang): ?>
                            <option value="<?php //echo $key; ?>" <?php //echo CURRENT_LANGUAGE == $key ? 'selected="selected"' : ''; ?>>
                                <?php //echo $lang['name']; ?>
                            </option>
                        <?php //endforeach; ?>
                    </select>
                </form>
            </ul>-->
        </div>
    </footer>

    <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
    <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
    <![endif]-->
</body>
</html>