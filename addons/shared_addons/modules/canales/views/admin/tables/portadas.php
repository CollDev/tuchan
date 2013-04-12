<style>
    .ui-accordion-header .ui-icon{
        left: 2.5em !important;
    }
    .ui-accordion-content-active{
        height: auto !important;
    }
</style>
<?php if ($portadas) : ?>
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
                        <td style="width: 30%;"><?php echo $post->nombre; ?></td>
                        <td style="width: 30%;"><?php echo $post->descripcion; ?></td>
                        <td style="width: 5%;"><div id="portada_<?php echo $post->id; ?>"><?php echo lang('global:' . $post->estado . '_estado'); ?></div></td>
                        <?php
                        switch ($post->estado):
                            case $this->config->item('estado:borrador'):
                                $link = '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="btn modal-large">' . lang('global:preview') . '</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="publicar_portada(' . $post->id . ',\'portada\');return false;">Publicar</a>';
                                $link.='<a href="#" class="link_portada btn">Editar</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="eliminar_portada(' . $post->id . ',\'portada\');return false;">Eliminar</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                            case $this->config->item('estado:publicado'):
                                $link = '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="btn modal-large">' . lang('global:preview') . '</a>';
                                $link.='<a href="#" class="link_portada btn">Editar</a>';
                                $link.='<a href="#" class="link_portada btn"  onclick="eliminar_portada(' . $post->id . ',\'portada\');return false;">Eliminar</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                            case $this->config->item('estado:eliminado'):
                                $link = '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="btn modal-large">' . lang('global:preview') . '</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="restablecer_portada(' . $post->id . ',\'portada\');return false;">Restablecer</a>';
                                $link.='<a href="#" class="link_portada btn" onclick="agregar_seccion(' . $post->id . ');return false;">Añadir sección</a>';
                                break;
                        endswitch;
                        ?>
                        <td style="width: 30%;"><div id="portada_boton_<?php echo $post->id; ?>"><?php echo $link; ?></div><!--<span onclick="agregar_seccion(<?php //echo $post->id;      ?>);
                                        return false;" > <?php //echo $objCanal->tipo_canales_id == $this->config->item('canal:mi_canal') ? 'Añadir seccion' : '';      ?></span>--></td>
                    </tr>
                </table>
            </h3>
            <div id="<?php echo $post->id; ?>">
                <?php
                $coleccion_seccion = $post->secciones;
                if (count($coleccion_seccion) > 0):
                    foreach ($coleccion_seccion as $indice => $objSeccion):
                        switch ($objSeccion->estado) {
                            case 0:$estado = 'Borrador';
                                $acciones = 'Previsualizar | Publicar | <a title="Editar" href="admin/canales/seccion/' . $post->canales_id . '/' . $objSeccion->id . '">Editar</a> | Eliminar';
                                break;
                            case 1: $estado = 'Publicado';
                                $acciones = 'Ver | <a title="Editar" href="admin/canales/seccion/' . $post->canales_id . '/' . $objSeccion->id . '">Editar</a> | Eliminar';
                                break;
                            case 2 : $estado = 'Eliminado';
                                $acciones = 'Previsualizar |Restablecer';
                                break;
                        }
                        ?>
                        <table>
                            <tr>
                                <td style="width: 5%;"><?php echo $indice + 1; ?></td>
                                <td style="width: 28%;"><?php echo $objSeccion->nombre; ?></td>
                                <td style="width: 30%;"><?php echo $objSeccion->descripcion; ?></td>
                                <td style="width: 10%;"><?php echo $estado; ?></td>
                                <td style="width: 25%;"><?php echo $acciones; ?></td>
                            </tr>
                        </table>                    
                        <?php
                    endforeach;
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
                                htmlButton += '<a href="#" onclick="restablecer_portada(' + portada_id + ',\'portada\');return false;" class="link_portada btn">Restablecer</a>';
                                htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="btn modal-large">Previsualizar</a>';
                                htmlButton += '<a href="#" class="link_portada btn" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
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
                                htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="modal-large btn">Previsualizar</a>';
                                htmlButton += '<a href="#" onclick="publicar_portada(' + portada_id + ', \'portada\');return false;" class="link_portada btn">Publicar</a>';
                                htmlButton += '<a href="#" class="link_portada btn">Editar</a>';
                                htmlButton += '<a href="#" class="link_portada btn" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
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
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            if (respuesta.value == 1) {
                                //location.reload();
                                $("#" + tipo + "_" + portada_id).empty();
                                $("#" + tipo + "_" + portada_id).html('Publicado');
                                var htmlButton = '';
                                htmlButton += '<a href="/admin/canales/previsualizar_portada/" target ="_blank" class="modal-large btn">Previsualizar</a>';
                                htmlButton += '<a href="#" class="link_portada btn">Editar</a>';
                                htmlButton += '<a href="#" onclick="eliminar_portada(' + portada_id + ');return false;" class="link_portada btn">Publicar</a>';
                                htmlButton += '<a href="#" class="link_portada btn" onclick="agregar_seccion(' + portada_id + ');return false;">Añadir sección</a>';
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
    </script>
<?php endif; ?>
    