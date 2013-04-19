<section class="title">
    <h4><?php echo lang('maestros:organizar_videos') ?></h4>
</section>
<section class="bar" style="width: 100%;">
    <div style="text-align: right;" >
        <?php echo anchor('/admin/videos/grupo_maestro/'.$canal_id.'/0', 'Nuevo programa / coleccion', 'class="btn blue"') ?>
    </div>
</section>
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('maestros'); ?>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var vista = 'organizar_videos';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal_id; ?>/"+vista;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'html',
            //data:imagen_id,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                $(".subbar > .wrapper").html(respuesta);
            } //end success
        }); //end AJAX         
    });
</script>
