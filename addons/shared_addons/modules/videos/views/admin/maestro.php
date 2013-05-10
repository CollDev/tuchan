<section class="title"> 
    <div style ="float: left;">
        <?php
        echo anchor('admin/videos/carga_unitaria/' . $canal_id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        /*    echo anchor('admin/videos/carga_masiva/' . $canal_id, 'Carga masiva', array('class' => ''));
          echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
        echo anchor('admin/videos/maestro/' . $canal_id, 'Organizar videos', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/canales/portada/' . $canal_id, 'Portadas', array('class' => ''));
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $canal_id, 'Papelera', array('class' => '')); ?>
    </div>     
</section>
<!--<section class="bar" style="width: 100%;">
    <div style="text-align: right;" >
<?php //echo anchor('/admin/videos/grupo_maestro/'.$canal_id.'/0', 'Nuevo programa / coleccion', 'class="btn blue"') ?>
    </div>
</section>-->
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('maestros'); ?>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var vista = 'organizar_videos';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal_id; ?>/" + vista;
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
    function showMessage(type, message, duration, pathurl) {
        if (type == 'error') {
            jError(
                    message,
                    {
                        autoHide: true, // added in v2.0
                        TimeShown: duration,
                        HorizontalPosition: 'center',
                        VerticalPosition: 'top',
                        onCompleted: function() { // added in v2.0
                            //alert('jNofity is completed !');
                        }
                    }
            );
        } else {
            if (type == 'exit') {
                jSuccess(
                        message,
                        {
                            autoHide: true, // added in v2.0
                            TimeShown: duration,
                            HorizontalPosition: 'center',
                            VerticalPosition: 'top',
                            onCompleted: function() { // added in v2.0
                                if (pathurl.length > 0) {
                                    $(location).attr('href', '<?php echo BASE_URL; ?>' + pathurl);
                                    //window.location('<?php echo BASE_URL; ?>'+pathurl);
                                }
                            }
                        }
                );
            }
        }
    }
</script>
