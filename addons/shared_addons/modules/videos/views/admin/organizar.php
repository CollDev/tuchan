<section class="title"> 
    <div style ="float: left;">
        <?php
        echo anchor('admin/videos/carga_unitaria/' . $canal_id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/videos/organizar/' . $canal_id, 'Organizar videos', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/canales/portada/' . $canal_id, 'Portadas', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('/admin/videos/grupo_maestro/' . $canal_id, 'Crear programas', array('class' => ''));        
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $canal_id, 'Papelera', array('class' => '')); ?>
    </div>     
</section>
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('organizar_videos'); ?>
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

    function eliminar(maestro_id, tipo) {
        jConfirm("Seguro que deseas eliminar este Item?", "Organizar videos", function(r) {
            if (r) {
                var tipo_item = tipo;
                if (tipo == 'v') {
                    tipo = 'video';
                } else {
                    tipo = 'maestro';
                }
                if (tipo == 'video') {
                    var post_url = "/admin/canales/eliminar_video/" + maestro_id;
                } else {
                    var post_url = "/admin/canales/eliminar_maestro/" + maestro_id;
                }
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            //location.reload();
                            $("#item_" + tipo_item + "_" + maestro_id).empty();
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }

    function publicar(maestro_id, tipo) {
        jConfirm("Seguro que deseas publicar este Item?", "Organizar videos", function(r) {
            if (r) {
                var tipo_item = tipo;
                if (tipo == 'v') {
                    tipo = 'video';
                } else {
                    tipo = 'maestro';
                }
                if (tipo == 'video') {
                    var post_url = "/admin/videos/publicar_video/" + maestro_id;
                } else {
                    var post_url = "/admin/videos/publicar_maestro/" + maestro_id;
                }
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            //location.reload();
                            $("#estado_" + tipo_item + "_" + maestro_id).empty();
                            $("#estado_" + tipo_item + "_" + maestro_id).html('Publicado');
                            var htmlButton = '';
                            if (tipo_item == 'v') {
                                var url = 'admin/videos/carga_unitaria/<?php echo $canal_id ?>/' + maestro_id;
                            } else {
                                var url = 'admin/videos/grupo_maestro/<?php echo $canal_id ?>/' + maestro_id;
                            }
                            htmlButton += '<a href="#" class="mode mode_edit" onclick="editar(\'' + url + '\');return false;">Editar</a>';
                            htmlButton += '<a href="#" class="mode mode_delete" onclick="eliminar(' + maestro_id + ', \'' + tipo_item + '\');return false;">Eliminar</a>';
                            $("#acciones_" + tipo_item + "_" + maestro_id).html(htmlButton);
                        } else {
                            showMessage('error', 'No es posible publicar. No tiene videos publicados', 2000, '');
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
</script>
