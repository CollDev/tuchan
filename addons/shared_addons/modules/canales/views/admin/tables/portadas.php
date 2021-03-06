<style>
    .ui-accordion-header .ui-icon{
        left: 2.5em !important;
    }
    .ui-accordion-content-active{
        height: auto !important;
    }
</style>
<?php if ($portadas) : ?>
    <table>
        <tr>
            <td>
                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
            <?php if ($objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')): ?>
                <td>    <div style="text-align: right;" >
                        <a href="#" onclick="agregar_portada();
                                        return false;" class="link_portada btn blue" title="<?php echo lang('portada:add_portada'); ?>"><?php echo lang('portada:add_portada'); ?></a>
                    </div>
                </td> 
            <?php endif; ?>
        </tr>
    </table>
    <?php echo form_open(''); ?>
    <table>
        <tr>
            <td style="width: 5%;">#</td>
            <td style="width: 30%;">Portadas</td>
            <td style="width: 30%;">Detalle</td>
            <td style="width: 5%;">Estado</td>
            <td style="width: 30%;">Acciones</td>
        </tr>
    </table>
    <div id="accordion">
        <?php
        foreach ($portadas as $index => $post):
            ?>
            <h3>
                <table>
                    <tr>
                        <td style="width: 5%;"><?php echo $index + 1; ?></td>
                        <td style="width: 30%;"><div id="nombre_<?php echo $post->id ?>"><?php echo $post->nombre; ?></div></td>
                        <td style="width: 30%;"><div id="descripcion_<?php echo $post->id ?>"><?php echo strip_tags($post->descripcion) ?></div></td>
                        <td style="width: 5%;"><div id="portada_<?php echo $post->id; ?>"><?php echo lang('global:' . $post->estado . '_estado'); ?></div></td>
                        <?php
                        switch ($post->estado):
                            case $this->config->item('estado:borrador'):
                                if ($post->tipo_portadas_id == $this->config->item('portada:canal')) {
                                    $link = anchor('/admin/canales/previsualizar_canal/' . $post->canales_id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                                } else {
                                    $link = '<a href="/admin/canales/previsualizar_portada/' . $post->id . '" target ="_blank" class="mode_preview modal-large">' . lang('global:preview') . '</a>';
                                }
                                $link.='<a href="#" class="link_portada mode_publish" onclick="publicar_portada(' . $post->id . ',\'portada\');return false;">Publicar</a>';
                                $link.='<a href="#" onclick="editar_portada(' . $post->id . ');return false;" class="link_portada mode_edit">Editar</a>';
                                //$link.='<a href="#" class="link_portada mode_delete" onclick="eliminar_portada(' . $post->id . ',\'portada\');return false;">Eliminar</a>';
                                $link.='<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                            case $this->config->item('estado:publicado'):
                                if ($post->tipo_portadas_id == $this->config->item('portada:canal')) {
                                    $link = anchor('/admin/canales/previsualizar_canal/' . $post->canales_id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                                } else {
                                    $link = '<a href="/admin/canales/previsualizar_portada/' . $post->id . '" target ="_blank" class="mode_preview modal-large">' . lang('global:preview') . '</a>';
                                }
                                $link.='<a href="#" onclick="editar_portada(' . $post->id . ');return false;" class="link_portada mode_edit">Editar</a>';
                                //$link.='<a href="#" class="link_portada mode_delete"  onclick="eliminar_portada(' . $post->id . ',\'portada\');return false;">Eliminar</a>';
                                $link.='<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                            case $this->config->item('estado:eliminado'):
                                if ($post->tipo_portadas_id == $this->config->item('portada:canal')) {
                                    $link = anchor('/admin/canales/previsualizar_canal/' . $post->canales_id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                                } else {
                                    $link = '<a href="/admin/canales/previsualizar_portada/' . $post->id . '" target ="_blank" class="mode_preview modal-large">' . lang('global:preview') . '</a>';
                                }
                                $link.='<a href="#" class="link_portada mode_restore" onclick="restablecer_portada(' . $post->id . ',\'portada\');return false;">Restablecer</a>';
                                $link.='<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                        endswitch;
                        ?>
                        <td style="width: 30%;"><div id="portada_boton_<?php echo $post->id; ?>"><?php echo $link; ?></div><!--<span onclick="agregar_seccion(<?php //echo $post->id;                ?>);
                                        return false;" > <?php //echo $objCanal->tipo_canales_id == $this->config->item('canal:mi_canal') ? 'Añadir seccion' : '';                ?></span>--></td>
                    </tr>
                </table>
            </h3>
            <div id="<?php echo $post->id; ?>">
                <?php
                $coleccion_seccion = $post->secciones;
                if (count($coleccion_seccion) > 0):
                    ?>
                    <table id="table-<?php echo $post->id ?>">
                        <?php
                        foreach ($coleccion_seccion as $indice => $objSeccion):
                            switch ($objSeccion->estado) {
                                case $this->config->item('estado:borrador'):
                                    $acciones = '<a href="/admin/canales/previsualizar_seccion/' . $objSeccion->id . '" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                                    $acciones.= '<a href="#" onclick="publicar_seccion(' . $objSeccion->id . ', \'seccion\');return false;" class="mode_publish">Publicar</a>';
                                    $acciones.= '<a title="Editar" href="/admin/canales/seccion/' . $post->canales_id . '/' . $objSeccion->id . '" class="mode_edit">Editar</a>';
                                    $acciones.= '<a href="#" onclick="eliminar_seccion(' . $objSeccion->id . ', \'seccion\');return false;" class="mode_delete">Eliminar</a>';
                                    break;
                                case $this->config->item('estado:publicado'):
                                    $acciones = '<a href="/admin/canales/previsualizar_seccion/' . $objSeccion->id . '" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                    $acciones.= '<a title="Editar" href="/admin/canales/seccion/' . $post->canales_id . '/' . $objSeccion->id . '" class="mode_edit">Editar</a>';
                                    $acciones.= '<a href="#" onclick="eliminar_seccion(' . $objSeccion->id . ', \'seccion\');return false;" class="mode_delete">Eliminar</a>';
                                    break;
                                case $this->config->item('estado:eliminado') :
                                    $acciones = '<a href="/admin/canales/previsualizar_seccion/' . $objSeccion->id . '" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                    $acciones.= '<a href="#" onclick="restablecer_seccion(' . $objSeccion->id . ', \'seccion\');return false;" class="mode_restore">Restablecer</a>';
                                    break;
                            }
                            ?>
                            <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:destacado')): ?>
                                <tr class="nodrag" id="<?php echo $objSeccion->id ?>">
                                <?php else: ?>
                                <tr id="<?php echo $objSeccion->id ?>">
                                <?php endif; ?>

                                <td style="width: 5%;"><?php echo $indice + 1; ?></td>
                                <td style="width: 28%;"><?php echo $objSeccion->nombre; ?></td>
                                <td style="width: 30%;"><?php echo strip_tags($objSeccion->descripcion); ?></td>
                                <td style="width: 10%;"><div id="seccion_<?php echo $objSeccion->id; ?>"><?php echo lang('global:' . $objSeccion->estado . '_estado'); ?></div></td>
                                <td style="width: 25%;"><div id="seccion_boton_<?php echo $objSeccion->id; ?>"><?php echo $acciones; ?></div></td>
                            </tr>

                        <?php endforeach; ?>
                    </table> 
                    <?php
                endif;
                ?>
            </div>
            <?php
        endforeach;
        ?>
    </div>
    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
    <?php echo form_close(); ?>
    <script>
                            $(document).ready(function() {
                                //iteremos el numero de items paginado para arrancar el drag & drop
    <?php foreach ($portadas as $index => $post): ?>
                                    $("#table-<?php echo $post->id ?>").tableDnD({
                                        onDrop: function(table, row) {
                                            ordenarListaSecciones($.tableDnD.serialize());
                                        }
                                    });
    <?php endforeach; ?>
                                //$(function() {
                                $("#accordion").accordion({
                                    active: false,
                                    autoHeight: false,
                                    collapsible: true
                                });
                                var altura = $(document).height();
                                $(".bajada2").css('height', '800');
                                $('.link_portada').click(function(e) {
                                    e.stopPropagation();
                                    //Your Code here(For example a call to your function)
                                });



                            });

                            function ordenarListaSecciones(dato) {
                                var post_url = "/admin/canales/ordenarListaSecciones/";
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    dataType: 'json',
                                    data: dato,
                                    success: function(respuesta)
                                    {

                                    } //end success
                                }); //end AJAX                                 
                            }
                            function eliminar_portada(portada_id, tipo) {
                                jConfirm("Seguro que deseas eliminar este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/eliminar_portada/" + portada_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            //data: indexOrder,
                                            success: function(respuesta)
                                            {
                                                if (respuesta.value == 1) {
                                                    //location.reload();
                                                    $("#" + tipo + "_" + portada_id).empty();
                                                    $("#" + tipo + "_" + portada_id).html('Eliminado');
                                                    var htmlButton = '';
                                                    htmlButton += '<a href="#" onclick="restablecer_portada(' + portada_id + ',\'portada\');return false;" class="link_portada mode_restore">Restablecer</a>';
                                                    htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="mode_preview modal-large">Previsualizar</a>';
                                                    htmlButton += '<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
                                                    $("#" + tipo + "_boton_" + portada_id).html(htmlButton);
                                                    $('.link_portada').click(function(e) {
                                                        e.stopPropagation();
                                                        //Your Code here(For example a call to your function)
                                                    });
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
                            }

                            function restablecer_portada(portada_id, tipo) {
                                jConfirm("Seguro que deseas restablecer este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/restablecer_portada/" + portada_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            //data: indexOrder,
                                            success: function(respuesta)
                                            {
                                                if (respuesta.value == 1) {
                                                    //location.reload();
                                                    $("#" + tipo + "_" + portada_id).empty();
                                                    $("#" + tipo + "_" + portada_id).html('Borrador');
                                                    var htmlButton = '';
                                                    htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                                    htmlButton += '<a href="#" onclick="publicar_portada(' + portada_id + ', \'portada\');return false;" class="link_portada mode_publish">Publicar</a>';
                                                    htmlButton += '<a href="#" class="link_portada mode_edit" onclick="return false;">Editar</a>';
                                                    //htmlButton += '<a href="#" class="link_portada mode_delete" onclick="eliminar_portada(' + portada_id + ',\'portada\');return false;">Eliminar</a>';
                                                    htmlButton += '<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
                                                    $("#" + tipo + "_boton_" + portada_id).html(htmlButton);
                                                    $('.link_portada').click(function(e) {
                                                        e.stopPropagation();
                                                        //Your Code here(For example a call to your function)
                                                    });
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
                            }

                            function publicar_portada(portada_id, tipo) {
                                jConfirm("Seguro que deseas publicar este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/publicar_portada/" + portada_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            success: function(respuesta)
                                            {
                                                switch (respuesta.value) {
                                                    case "1" :
                                                        $("#" + tipo + "_" + portada_id).empty();
                                                        $("#" + tipo + "_" + portada_id).html('Publicado');
                                                        var htmlButton = '';
                                                        htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                                        htmlButton += '<a href="#" onclick="return false;" class="link_portada mode_edit">Editar</a>';
                                                        htmlButton += '<a href="#" class="link_portada mode_add" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
                                                        $("#" + tipo + "_boton_" + portada_id).html(htmlButton);
                                                        $('.link_portada').click(function(e) {
                                                            e.stopPropagation();
                                                        });
                                                        break;
                                                    case "2":
                                                        showMessage('error', 'No se puede publicar. No tiene videos publicados', 2000, '');
                                                        break;
                                                    case "3":
                                                        showMessage('error', 'No se puede publicar. No tiene sección destacado publicado', 2000, '');
                                                        break;
                                                    case "4":
                                                        showMessage('error', 'No se puede publicar. No tiene secciones publicadas', 2000, '');
                                                        break;
                                                    case "5":
                                                        showMessage('error', 'No se puede publicar. El canal no está publicado', 2000, '');
                                                        break;
                                                    case "6":
                                                        showMessage('error', 'No se puede publicar. No se encontró la portada', 2000, '');
                                                        break;
                                                    default :
                                                        showMessage('error', 'No se puede publicar. Ocurrio un error inesperado', 2000, '');
                                                        break;
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
                            }

                            function eliminar_seccion(seccion_id, tipo) {
                                jConfirm("Seguro que deseas eliminar este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/eliminar_seccion/" + seccion_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            //data: indexOrder,
                                            success: function(respuesta)
                                            {
                                                if (respuesta.value == 1) {
                                                    //location.reload();
                                                    $("#" + tipo + "_" + seccion_id).empty();
                                                    $("#" + tipo + "_" + seccion_id).html('Eliminado');
                                                    var htmlButton = '<a href="/admin/canales/previsualizar_seccion/" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                                    htmlButton += '<a href="#" onclick="restablecer_seccion(' + seccion_id + ', \'seccion\');return false;" class="mode_restore">Restablecer</a>';
                                                    $("#" + tipo + "_boton_" + seccion_id).html(htmlButton);
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
                            }

                            function restablecer_seccion(seccion_id, tipo) {
                                jConfirm("Seguro que deseas restablecer este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/restablecer_seccion/" + seccion_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            //data: indexOrder,
                                            success: function(respuesta)
                                            {
                                                if (respuesta.value == 1) {
                                                    //location.reload();
                                                    var canal_id = '<?php echo $canal_id; ?>';
                                                    $("#" + tipo + "_" + seccion_id).empty();
                                                    $("#" + tipo + "_" + seccion_id).html('Borrador');
                                                    var htmlButton = '<a href="/admin/canales/previsualizar_seccion/" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                                    htmlButton += '<a href="#" onclick="publicar_seccion(' + seccion_id + ', \'seccion\');return false;" class="mode_publish">Publicar</a>';
                                                    htmlButton += '<a title="Editar" href="admin/canales/seccion/' + canal_id + '/' + seccion_id + '" class="mode_edit">Editar</a>';
                                                    htmlButton += '<a href="#" onclick="eliminar_seccion(' + seccion_id + ', \'seccion\');return false;" class="mode_delete">Eliminar</a>';
                                                    $("#" + tipo + "_boton_" + seccion_id).html(htmlButton);
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
                            }

                            function publicar_seccion(seccion_id, tipo) {
                                jConfirm("Seguro que deseas publicar este Item?", "Portada", function(r) {
                                    if (r) {
                                        var post_url = "/admin/canales/publicar_seccion/" + seccion_id;
                                        $.ajax({
                                            type: "POST",
                                            url: post_url,
                                            dataType: 'json',
                                            //data: indexOrder,
                                            success: function(respuesta)
                                            {
                                                if (respuesta.value == 1) {
                                                    var canal_id = '<?php echo $canal_id; ?>';
                                                    $("#" + tipo + "_" + seccion_id).empty().html('Publicado');
                                                    var htmlButton = '<a href="/admin/canales/previsualizar_seccion/" target ="_blank" class="modal-large mode_preview">Previsualizar</a>';
                                                    htmlButton += '<a title="Editar" href="admin/canales/seccion/' + canal_id + '/' + seccion_id + '" class="mode_edit">Editar</a>';
                                                    htmlButton += '<a href="#" onclick="eliminar_seccion(' + seccion_id + ', \'seccion\');return false;" class="mode_delete">Eliminar</a>';
                                                    $("#" + tipo + "_boton_" + seccion_id).html(htmlButton);
                                                    showMessage('exit', 'Publicado satisfactoriamente.', 2000, '');
                                                } else if (respuesta.value == 2) {
                                                    showMessage('error', 'No se puede publicar. No se encontró elementos activos en su detalle', 2000, '');
                                                }
                                            } //end success
                                        }); //end AJAX   
                                    }
                                });
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
    </script>
<?php endif; ?>
    