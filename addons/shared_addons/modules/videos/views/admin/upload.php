<section class="title">
	<h4>upload</h4>
</section>
<section class="item">
	<div>
	<?php 
	//get unique id
	$up_id = uniqid(); 
	?>
  <?php if (isset($_GET['success'])) { ?>
  <span class="notice">Your file has been uploaded.</span>
  <?php } ?>
  <form action="/admin/videos/upload/" method="post" enctype="multipart/form-data" name="form1" id="form1">
    Name<br />
    <input name="name" type="text" id="name"/>
    <br />
    <br />
    Your email address <br />
    <input name="email" type="text" id="email" size="35" />
    <br />
    <br />
    Choose a file to upload<br />

<!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
<!---->

    <input name="video" type="file" id="file" size="30"/>

<!--Include the iframe-->
    <br />
    <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
    <br />
<!---->

    <input name="Submit" type="submit" id="submit" value="Submit" />
  </form>
  <!--Progress Bar and iframe Styling-->

<!--display bar only if file is chosen-->
<script>

$(document).ready(function() { 
//

//show the progress bar only if a file field was clicked
	var show_bar = 0;
    $('input[type="file"]').click(function(){
		show_bar = 1;
    });

//show iframe on form submit
    $("#form1").submit(function(){

		if (show_bar === 1) { 
			$('#upload_frame').show();
			function set () {
				$('#upload_frame').attr('src','upload_frame.php?up_id=<?php echo $up_id; ?>');
			}
			setTimeout(set);
		}
    });
//

});

</script>
  </div>
</section>