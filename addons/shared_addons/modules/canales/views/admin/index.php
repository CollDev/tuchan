<section class="title">
    <div style ="float: left;">
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/', 'Papelera', array('class' => '')); ?>
    </div> 
</section>
<?php if ($this->session->userdata['group'] == 'admin'): ?>
            <!--<section class="menu"><?php //echo anchor('/admin/canales/canal/', lang('canales:new'))   ?></section>-->
<?php endif; ?>
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
                                $("#canal_" + canal_id).empty();
//                                $("#" + tipo + "_" + canal_id).empty();
//                                $("#" + tipo + "_" + canal_id).html('Eliminado');
//                                var htmlButton = '';
//                                htmlButton += '<a href="#" onclick="restablecer(' + canal_id + ',\'canal\');return false;" class="mode_restore">Restablecer</a>';
//                                htmlButton += '<a href="/admin/canales/previsualizar_canal/' + canal_id + '" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
//                                htmlButton += '<a href="/admin/canales/portada/' + canal_id + '" class="mode_front">Portada</a>';
//                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
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
                                htmlButton += '<a href="/admin/canales/previsualizar_canal/' + canal_id + '" target ="_blank" class="mode_preview modal-large">V.Previa</a>';
                                htmlButton += '<a href="#" onclick="publicar(' + canal_id + ',\'canal\');return false;" class="mode_publish">Publicar</a>';
                                htmlButton += '<a href="#" onclick="eliminar(' + canal_id + ',\'canal\');return false;" class="mode_delete">Eliminar</a>';
                                htmlButton += '<a href="/admin/canales/portada/' + canal_id + '" class="mode_front">Portada</a>';
                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
                            }
                        } //end success
                    }); //end AJAX   
                }
            });
        }

        function publicar(canal_id, tipo) {
            jConfirm("Seguro que deseas publicar este Item?", "Maestros", function(r) {
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
                                htmlButton += '<a href="/admin/canales/previsualizar_canal/' + canal_id + '" target ="_blank" class="mode_preview modal-large">V.Previa</a>';
                                htmlButton += '<a href="#" onclick="eliminar(' + canal_id + ',\'canal\');return false;" class="mode_delete">Eliminar</a>';
                                htmlButton += '<a href="/admin/canales/portada/' + canal_id + '" class="mode_front">Portada</a>';
                                $("#" + tipo + "_boton_" + canal_id).html(htmlButton);
                            } else {
                                if (respuesta.value == 2) {
                                    showMessage('error', 'No se puede publicar. No tiene la sección destacado publicado', 2000, '');
                                } else {
                                    if (respuesta.value == 3) {
                                        showMessage('error', 'No se puede publicar. No tiene secciones publicadas', 2000, '');
                                    } else {
                                        showMessage('error', 'No se puede publicar. No tiene videos publicados', 2000, '');
                                    }
                                }
                            }
                        } //end success
                    }); //end AJAX   
                }
            });
        }
    </script> 
</section>

