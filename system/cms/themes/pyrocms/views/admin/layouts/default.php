<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en"> 		   <![endif]-->

<head>
	<meta charset="utf-8">

	<!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo lang('cp_admin_title').' - '.$template['title'];?></title>

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
<!--	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
	<script type="text/javascript" src="jquery.dragsort-0.5.1.js"></script>
<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
	<!--<script src="../ckeditor.js"></script><style>.cke{visibility:hidden;}</style>-->
    
<!--<script type="text/javascript" src="file:///D:/micanal_demo/BACK-END/ckeditor_4.0.1_basic/ckeditor/config.js?t=D08E"></script><link rel="stylesheet" type="text/css" href="file:///D:/micanal_demo/BACK-END/ckeditor_4.0.1_basic/ckeditor/skins/moono/editor.css?t=D08E"><script type="text/javascript" src="file:///D:/micanal_demo/BACK-END/ckeditor_4.0.1_basic/ckeditor/lang/es.js?t=D08E"></script>-->
		<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.0/themes/smoothness/jquery-ui.css" />
<!--		<link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />-->
		<link rel="stylesheet" type="text/css" href="../../../css/jquery-ui-timepicker-addon.css">
		
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.0/jquery-ui.min.js"></script>
<!--		<script type="text/javascript" src="jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript" src="jquery-ui-sliderAccess.js"></script>-->
		<script src="../../../js/jquery-ui-sliderAccess.js"></script>
		<script src="../../../js/jquery-ui-timepicker-addon.js"></script>
		<script type="text/javascript">
			
			$(function(){
				$('#tabs').tabs();
				$('.example-container > pre').each(function(i){
					eval($(this).text());
				});
			});
			
		</script>

		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<!--		<script type="text/javascript" src="../../../js/google_dynamic_map.js"></script>-->
<script type="text/javascript" >
$(document).ready(function() {
	
$(".plus_item").each(function() {
  		$(this).click(function(event){
			  event.preventDefault();/* alert();*/
			 				$(this).parent().find($(".h_text")).fadeIn("slow", function(){
							});	

		});
});
			

});






// my functions map
function initialize() 
{

	geocoder = new google.maps.Geocoder();
	// Set static latitude, longitude value
	var latlng = new google.maps.LatLng(37.7699298, -122.4469157);
	// Set map options
	var myOptions = {
		zoom: 16,
		center: latlng,
		panControl: true,
		zoomControl: true,
		scaleControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	// Create and set the marker
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

label{
	width:100%;
	display:block;
	
	}
	
.h_text{ display:none;}	
.plus_item{}
.main_opt{ width:100%;}
</style>


</head>





</head>

<body onLoad="initialize();">

                   <div class="bajada2">
                                    <span class="boxgrid captionfull">	
                                             <div class="cover boxcaption">
                                             
                                                <div class="toggle-icon-finder">
                                                <a href="#" class="linker">EXPANDER</a>
                                                <a href="#" class="linker2 hide">CONTRAER</a>
                                                
                                                <a href="#" class="plus_view"></a>	
                                                </div>
                                                 <div class="search-box">
                                                          <span class="view_mc">BUSQUEDA</span>                          	  
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
                                                          </div>
                                                </div>   
                                                                          
                                                
                                            </div>
                                    </span>
                    </div>
              
<!--<script src="jquery-1.7.1.min.js" type="text/javascript"></script>-->
<script type="text/javascript">
				$(document).ready(function() {
						$(".cover").css("width", "2%");


						$('.linker').live("click",function(event){
							event.preventDefault();
							$(".cover").stop().animate({width: "100%"},130);
							$(".bajada2").stop().animate({width: "15%"},130);
							$('.linker2').removeClass("hide");
							$('.linker').addClass("hide");
							
							$(".search-box").fadeIn("slow", function(){
										$(".plus_view").fadeIn("slow", function(){
										});
								});
						});
						
						$('.linker2').live("click",function(event){
							event.preventDefault();
							$(".plus_view").fadeOut("slow", function(){
							
							});							
							$(".search-box").fadeOut("slow", function(){
										$(".cover").stop().animate({width: "2%"},130);
										$(".bajada2").stop().animate({width: "2%"},130);
										$('.linker').removeClass("hide");
										$('.linker2').addClass("hide");								

								});
						});
						
						
						$('.plus_view').live("click",function(event){
							event.preventDefault();

							    $(".bajada2").stop().animate({width: "100%"},130);
								$(".cover").stop().animate({width: "100%"},"fast", function(){
										$(".plus_view").fadeOut("fast", function(){
										
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
<!--			<p>Copyright &copy; 2009 - <?php //echo date('Y'); ?> PyroCMS &nbsp; -- &nbsp; Version <?php //echo CMS_VERSION.' '.CMS_EDITION; ?> &nbsp; -- &nbsp; Rendered in {elapsed_time} sec. using {memory_usage}.</p>-->
            
			<p>Copyright &copy; <?php echo date('Y'); ?>  &nbsp; El Comercio  -- &nbsp; <img src="../../../img/backend_code/logo_elcomercio.png" width="273" height="32">
		    <!--Version--> <?php /*echo CMS_VERSION.' '.CMS_EDITION;*/ ?> &nbsp;  &nbsp;<!-- Rendered in {elapsed_time} sec. using {memory_usage}.--></p>
            

			<ul id="lang">
				<form action="<?php echo current_url(); ?>" id="change_language" method="get">
					<select class="chzn" name="lang" onchange="this.form.submit();">
						<?php foreach($this->config->item('supported_languages') as $key => $lang): ?>
						<option value="<?php echo $key; ?>" <?php echo CURRENT_LANGUAGE == $key ? 'selected="selected"' : ''; ?>>
								<?php echo $lang['name']; ?>
							</option>
					<?php endforeach; ?>
				</select>
				</form>
			</ul>
		</div>
	</footer>

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
</body>
</html>