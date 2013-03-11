<?php

$url = basename($_SERVER['SCRIPT_FILENAME']);
//Get file upload progress information.
if(isset($_GET['progress_key'])) {
	$status = apc_fetch('upload_'.$_GET['progress_key']);
	echo $status['current']/$status['total']*100;
	die;
}
//

?>

<script src="http://local.adminmicanal.com/system/cms/themes/pyrocms/js/jquery/jquery.js" type="text/javascript"></script>
<style type="text/css">
/*iframe*/
#upload_frame {
	border:0px;
	height:40px;
	width:400px;
	display:none;
}

#progress_container {
	width: 300px; 
	height: 30px; 
	border: 1px solid #CCCCCC; 
	background-color:#EBEBEB;
	display: block; 
	margin:5px 0px -15px 0px;
}

#progress_bar {
	position: relative; 
	height: 30px; 
	background-color: #F3631C; 
	width: 0%; 
	z-index:10; 
}

#progress_completed {
	font-size:16px; 
	z-index:40; 
	line-height:30px; 
	padding-left:4px; 
	color:#FFFFFF;
}
</style>

<script>
$(document).ready(function() { 
//

	setInterval(function() 
		{
	$.get("<?php echo $url; ?>?progress_key=<?php echo $_GET['up_id']; ?>&randval="+ Math.random(), { 
		//get request to the current URL (upload_frame.php) which calls the code at the top of the page.  It checks the file's progress based on the file id "progress_key=" and returns the value with the function below:
	},
		function(data)	//return information back from jQuery's get request
			{
				$('#progress_container').fadeIn(100);	//fade in progress bar	
				$('#progress_bar').width(data +"%");	//set width of progress bar based on the $status value (set at the top of this page)
				$('#progress_completed').html(parseInt(data) +"%");	//display the % completed within the progress bar
			}
		)},500);	//Interval is set at 500 milliseconds (the progress bar will refresh every .5 seconds)

});


</script>

<body style="margin:0px">
<!--Progress bar divs-->
<div id="progress_container">
	<div id="progress_bar">
  		 <div id="progress_completed"></div>
	</div>
</div>
<!---->
</body>