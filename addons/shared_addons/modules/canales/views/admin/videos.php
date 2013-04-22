<section class="title"> 
    <?php
    echo anchor('admin/videos/carga_unitaria/' . $canal->id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    /*    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
      echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
    echo anchor('admin/videos/maestro/' . $canal->id, 'Organizar videos', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo anchor('admin/canales/portada/' . $canal->id, 'Portadas', array('class' => ''));
    ?>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php var_dump(template_partial('users')); ?>
    </div>     
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var vista = 'Videos';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal->id; ?>/" + vista;
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
        //funcion reloj para verificar los estados en la DB
        var id = setInterval("verificar_estado_video()", <?php echo $this->config->item('video:segundos') ?>);
        //setTimeout("clearInterval(" + id + ")", 15000);
    });

    function verificar_estado_video() {
        var serializedData = $('#formListaVideo').serialize();
        var post_url = "/admin/videos/verificar_estado_video/";
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            data: serializedData,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                if (respuesta.error == 0) {
                    $.each(respuesta.videos, function(index, value) {
                        if (value == 0) {
                            var estado = 'Codificando';
                        }
                        if (value == 1) {
                            var estado = 'Borrador';
                        }
                        if (value == 2) {
                            var estado = 'Publicado';
                        }
                        if (value == 3) {
                            var estado = 'Eliminado';
                        }
                        $("#video_" + index).html(estado);
                    });
                }
            } //end success
        }); //end AJAX         

    }
</script>
<!--<script src="<?php //echo base_url("system/cms/themes/pyrocms/js/fix_channels.js")     ?>"></script>-->