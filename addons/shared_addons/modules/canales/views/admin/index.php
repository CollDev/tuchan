<section class="title">
    <h4><?php echo $module_details['name']; ?></h4>
</section>
<section class="menu"><?php echo anchor('/admin/canales/canal/', lang('canales:new')) ?></section>
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('canales'); ?>
    </div>
    <script type="text/javascript">
        function dispatch(canal_id) {
            var post_url = "/admin/canales/dispatch/" + canal_id;
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'json',
                //data:imagen_id,
                success: function(returnRespuesta) //we're calling the response json array 'cities'
                {
                    if (returnRespuesta.value == '0') {
                        showMessage('error', 'El canal ya tiene una portada', 2000, '');
                    } else {
                        showMessage('exit', 'Secrearon las portadas en forma satisfactoria', 2000, '');
                    }
                } //end success
            }); //end AJAX             
        }

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

        function eliminar(canal_id, tipo) {
            jConfirm("Seguro que deseas eliminar este Item?", "Maestros", function(r) {
                if (r) {
                    var post_url = "/admin/canales/eliminar_canal/" + canal_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            if (respuesta.value == 1) {
                                //location.reload();
                                $("#" + tipo + "_" + canal_id).empty();
                                $("#" + tipo + "_" + canal_id).html('Eliminado');
                                var htmlButton = '';
                                htmlButton += '<a href="#" onclick="restablecer(' + canal_id + ',\'canal\');return false;" class="btn green">Restablecer</a>';
                                htmlButton += '<a href="/admin/canales/previsualizar_canal/" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
                            }
                        } //end success
                    }); //end AJAX   
                }
            });
        }

        function restablecer(canal_id, tipo) {
            jConfirm("Seguro que deseas restablecer este Item?", "Maestros", function(r) {
                if (r) {
                    var post_url = "/admin/canales/restablecer_canal/" + canal_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            if (respuesta.value == 1) {
                                //location.reload();
                                $("#" + tipo + "_" + canal_id).empty();
                                $("#" + tipo + "_" + canal_id).html('Borrador');
                                var htmlButton = '';
                                htmlButton += '<a href="/admin/canales/canal/' + canal_id + '"  class="mode_edit">Editar</a>';
                                htmlButton += '<a href="/admin/canales/previsualizar_canal/" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                                htmlButton += '<a href="#" onclick="publicar(' + canal_id + ',\'canal\');return false;" class="btn blue">Publicar</a>';
                                htmlButton += '<a href="#" onclick="eliminar(' + canal_id + ',\'canal\');return false;" class="mode_delete">Eliminar</a>';
                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
                            }
                        } //end success
                    }); //end AJAX   
                }
            });
        }

        function publicar(canal_id, tipo) {
            jConfirm("Seguro que deseas restablecer este Item?", "Maestros", function(r) {
                if (r) {
                    var post_url = "/admin/canales/publicar_canal/" + canal_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            if (respuesta.value == 1) {
                                //location.reload();
                                $("#" + tipo + "_" + canal_id).empty();
                                $("#" + tipo + "_" + canal_id).html('Publicado');
                                var htmlButton = '';
                                htmlButton += '<a href="/admin/canales/canal/' + canal_id + '"  class="mode_edit">Editar</a>';
                                htmlButton += '<a href="/admin/canales/previsualizar_canal/" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                                htmlButton += '<a href="#" onclick="eliminar(' + canal_id + ',\'canal\');return false;" class="mode_delete">Eliminar</a>';
                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
                            }
                        } //end success
                    }); //end AJAX   
                }
            });
        }
    </script> 
</section>

