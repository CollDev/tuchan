<div class="alert success" style="margin-top: 25px">    
    <p><?php echo $this->session->flashdata('msg_success'); ?></p>
</div>

<section class="title">
    <h4><?php echo $module_details['name']; ?> para <?php echo $usuario ?></h4>
</section>

<section class="item">
    <!-- Acceso a canales -->
    <div id="filter-stage">
        <?php template_partial('canales'); ?>
    </div>
</section>

<!--<script>
    $("input:checkbox:checked").each(function(){
	//cada elemento seleccionado	
        alert('check: ' + $(this).val());
        $("input:radio").each(function() {            
            alert('radio: ' + $(this).val());
        });
    });
</script>
-->
