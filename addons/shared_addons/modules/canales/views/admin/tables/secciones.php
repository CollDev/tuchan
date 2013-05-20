<style>
    .table-list tbody tr:hover{
        background-color: #CCE4E5;
    }
    .bajar{
        cursor:pointer;
    }
    .subir{
        cursor:pointer;
    }
</style>
<p>
    &nbsp;
</p>
<table id="table-1" class="table-list">
    <thead>
        <tr class="nodrag">
            <th>#</th>
            <th>Imagen</th>
            <th>nombre</th>
            <th>Descripcion</th>
            <th>Tipo</th>
            <th>Posición</th>
            <th>Acción</th>
            <th>ID</th>
        </tr>
    </thead>
    <tbody id="contenido">
        <?php
        $agregar_descripcion = FALSE;
        if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:destacado')) {
            $agregar_descripcion = TRUE;
        }
        $coleccionDetalle = $objSeccion->detalle;
        if (count($coleccionDetalle) > 0):
            foreach ($coleccionDetalle as $index => $objDetalleSeccion):
                if ($primer->peso == $objDetalleSeccion->peso):
                    //$img = '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png" onclick="bajar(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<img class="bajar" onclick="bajar(' . $objDetalleSeccion->id . ');return false;" title="Bajar" src="' . $this->config->item('url:default_imagen') . 'down.png" />';
                elseif ($ultimo->peso == $objDetalleSeccion->peso):
                    //$img = '<img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" onclick="subir(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<img onclick="subir(' . $objDetalleSeccion->id . ');return false;" class="subir" title="Subir" src="' . $this->config->item('url:default_imagen') . 'up.png" />';
                else:
                    //$img = '<img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" onclick="subir(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />' . '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png"  onclick="bajar(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<img onclick="subir(' . $objDetalleSeccion->id . ');return false;" class="subir" title="Subir" src="' . $this->config->item('url:default_imagen') . 'up.png" />' . '<img  onclick="bajar(' . $objDetalleSeccion->id . ');return false;" class="bajar" title="Bajar" src="' . $this->config->item('url:default_imagen') . 'down.png" />';
                endif;
                ?>
                <?php if ($index == 0 && $objSeccion->tipo_secciones_id == $this->config->item('seccion:coleccion')) { ?>
                    <tr  class="nodrag" id="<?php echo $objDetalleSeccion->id . '_' . $objDetalleSeccion->peso; ?>">
                    <?php } else { ?>
                    <tr id="<?php echo $objDetalleSeccion->id . '_' . $objDetalleSeccion->peso; ?>">
                    <?php } ?>
                    <td><?php echo ($index + 1); ?></td>
                    <td><div id="imagen_<?php echo $objDetalleSeccion->id; ?>"><img style="width:100px;" src="<?php echo $objDetalleSeccion->imagen; ?>" /></div></td>
                    <td><div id="nombre_<?php echo $objDetalleSeccion->id ?>"><?php echo $objDetalleSeccion->nombre; ?></div></td>
                    <td>
                        <?php if ($agregar_descripcion): ?>
                            <div id="descripcion_<?php echo $objDetalleSeccion->id; ?>">
                                <?php if (strlen(trim($objDetalleSeccion->descripcion_item)) > 0): ?>
                                    <?php echo $objDetalleSeccion->descripcion_item; ?>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <?php echo $objDetalleSeccion->descripcion_item; ?>
                        <?php endif; ?>

                    </td>
                    <td><?php echo $objDetalleSeccion->tipo; ?></td>
                    <td><div style="float: left;"><input onkeypress="return ordenar_lista_detalle_secciones(event);"  type="text" name="peso_<?php echo $objDetalleSeccion->id; ?>" id="peso_<?php echo $objDetalleSeccion->id; ?>" size="2" value="<?php echo $objDetalleSeccion->peso; ?>" /></div><div style="float: left;" id="img_<?php echo $objDetalleSeccion->id; ?>"><?php echo $img; ?></div></td>
                    <td>
                        <div style="float: left;">
                            <a href="#" class="btn red" onclick="quitarDetalleSeccion(<?php echo $objDetalleSeccion->id; ?>, <?php echo $canal_id; ?>, <?php echo $objSeccion->id; ?>);
                                    return false;">Quitar</a>
                        </div>
                        <?php if ($agregar_descripcion): ?>
                            <div style="float:left;" id="boton_<?php echo $objDetalleSeccion->id ?>">
                                <a href="#" class="btn blue" onclick="agregar_descripcion(<?php echo $objDetalleSeccion->id ?>);
                                        return false;">Agregar descripción</a>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $objDetalleSeccion->id; ?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr class="nodrag">
                <td colspan="8">No hay data</td>
            </tr>
        <?php
        endif;
        ?>
    </tbody>
    <tfoot>
        <tr class="nodrag">
            <td colspan="7">
                <div class="inner"  id="paginacion_secciones"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
        </tr>
    </tfoot>

</table>
<input type="hidden" name="ultimo" id="ultimo" value="<?php echo $ultimo->id; ?>" />
<input type="hidden" name="primer" id="primer" value="<?php echo $primer->id; ?>" />
<p>
    &nbsp;
</p>

<script type="text/javascript">
                        $(document).ready(function() {
                            //<span class="comment">// Initialise the table</span>
                            $("#table-1").tableDnD({
                                onDrop: function(table, row) {
                                    //console.log($.tableDnD.serialize());
                                    ordenarLista($.tableDnD.serialize());
                                }
                            });

                            //validar solo numero en el peso
                            $(".numeric").keydown(function(event) {
                                // permitimos el ingreso del Enter, Backspace,
                                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13) {
                                    var peso = $(".numeric").val();
                                    if (event.keyCode == 13 && peso.length > 0) {
                                        ordenar_lista_detalle_secciones();
                                    }
                                }
                                else {
                                    // Ensure that it is a number and stop the keypress
                                    if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            });
                        });
                        /**
                         * Método para guardar los datos de cabecera de la seccion
                         * @returns html
                         */
                        function guardarSeccion() {
                            var values = {};
                            $.each($('#frmSeccion').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            var serializedData = $('#frmSeccion').serialize();
                            var post_url = "/admin/canales/actualizar_seccion/";
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                data: serializedData,
                                success: function(respuesta)
                                {
                                    console.log(respuesta);
                                    if (respuesta.value == '1') {
                                        //location.reload();
                                        showMessage('exit', '<?php echo lang('seccion:success_saved') ?>', 2000, '')
                                    }
                                } //end success
                            }); //end AJAX  
                        }

                        /**
                         * ordena la lista de acuerdo a la posicion que se ingresa en la caja de texto
                         * @returns html
                         */
                        function ordenarLista(indexOrder) {
                            var values = {};
                            $.each($('#frmSeccion').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            var post_url = "/admin/canales/reordenar/" + values['seccion_id'];
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                data: indexOrder,
                                success: function(respuesta)
                                {
                                    var cont = 0;
                                    var htmlImg = '';
                                    $("#ultimo").val(respuesta.ultimo);
                                    $("#primer").val(respuesta.primer);
                                    var ultimo = $("#ultimo").val();
                                    var primero = $("#primer").val();
                                    $.each(respuesta.orden, function(id, peso) {
                                        if (primero == id) {
                                            //htmlImg = '<img title="Bajar" onclick="bajar(' + id + ',' + (cont + 1) + ', ' + peso + ')" src="./uploads/imagenes/down.png" />';
                                            htmlImg = '<img title="Bajar" id="bajar" src="./uploads/imagenes/down.png" />';
                                        } else {
                                            if (ultimo == id) {
                                                //htmlImg = '<img title="Subir" onclick="subir(' + id + ',' + (cont + 1) + ', ' + peso + ')" src="./uploads/imagenes/up.png" />';
                                                htmlImg = '<img title="Subir" id="subir" src="./uploads/imagenes/up.png" />';
                                            } else {
                                                htmlImg = '<img title="Subir" id="subir" src="./uploads/imagenes/up.png" /><img id="bajar" title="Bajar" src="./uploads/imagenes/down.png" />';
                                            }
                                        }
                                        $("#peso_" + id).val(peso);
                                        $("#img_" + id).empty();
                                        $("#img_" + id).html(htmlImg);
                                        cont++;
                                    });
                                } //end success
                            }); //end AJAX          
                        }
                        /**
                         * metodo para bajar una posicion al item
                         * @returns html     
                         * */
                        function bajar(detalle_seccion_id) {
                            var post_url = "/admin/canales/bajar_detalle_seccion/" + detalle_seccion_id;
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                //data: serializedData,
                                success: function(respuesta)
                                {
                                    if (respuesta.error == 0) {
                                        var canal_id = $("#canal_id").val();
                                        mostrar_lista_detalle_seccion(canal_id, respuesta.seccion_id)
                                    }
                                } //end success
                            }); //end AJAX 
                        }
                        /**
                         * metodo para subir una posicion al item en la lista
                         * @param int detalle_seccion_id
                         * @param int indexOrder
                         * @param int peso
                         * @returns html             
                         * */
                        function subir(detalle_seccion_id) {
                            var post_url = "/admin/canales/subir_detalle_seccion/" + detalle_seccion_id;
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                //data: serializedData,
                                success: function(respuesta)
                                {
                                    if (respuesta.error == 0) {
                                        var canal_id = $("#canal_id").val();
                                        mostrar_lista_detalle_seccion(canal_id, respuesta.seccion_id)
                                    }
                                } //end success
                            }); //end AJAX               
                        }

                        function ordenar_lista_detalle_secciones(e) {
                            var code;
                            if (!e)
                                var e = window.event;
                            if (e.keyCode)
                                code = e.keyCode;
                            else if (e.which)
                                code = e.which;
                            var character = String.fromCharCode(code);
                            //alert('Character was ' + character);
                            //alert(code);
                            //if (code == 8) return true;
                            //var AllowRegex = /^[\ba-zA-Z\s-]$/;
                            if (code == 32 || code == 8 || code == 27 || code == 13) {
                                if (code == 13) {
                                    //guardar la descripcion
                                    enviar_nueva_orden();
                                } else {
                                    return true;
                                }

                            } else {
                                var AllowRegex = /^[0-9A-Za-z]+$/;
                                if (AllowRegex.test(character))
                                    return true;
                            }
                            return false;
                        }

                        function enviar_nueva_orden() {
                            var serializedData = $('#frmSeccion').serialize();
                            var post_url = "/admin/canales/ordenar_lista_detalle_secciones/";
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                data: serializedData,
                                success: function(respuesta)
                                {
                                    mostrar_lista_detalle_seccion(respuesta.canal_id, respuesta.seccion_id);
                                } //end success
                            }); //end AJAX 
                        }
</script>