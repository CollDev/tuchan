<style>
    .table-list tbody tr:hover{
        background-color: #CCE4E5;
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
        $coleccionDetalle = $objSeccion->detalle;
        if (count($coleccionDetalle) > 0):
            foreach ($coleccionDetalle as $index => $objDetalleSeccion):
                if ($primer->peso == $objDetalleSeccion->peso):
                    //$img = '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png" onclick="bajar(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png" class="bajar"  />';
                elseif ($ultimo->peso == $objDetalleSeccion->peso):
                    //$img = '<img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" onclick="subir(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" class="subir"  />';
                else:
                    //$img = '<img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" onclick="subir(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />' . '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png"  onclick="bajar(' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ')" />';
                    $img = '<a href="#" onclick="subir($(this).closest(\'tr\'),' . $objDetalleSeccion->id . ', ' . ($index + 1) . ', ' . $objDetalleSeccion->peso . ');return false;"><img title="Subir" src="' . UPLOAD_IMAGENES_VIDEOS . 'up.png" class="subir" /></a>' . '<img title="Bajar" src="' . UPLOAD_IMAGENES_VIDEOS . 'down.png"  class="bajar" />';
                endif;
                ?>
                <?php if ($index == 0 && $objSeccion->tipo_secciones_id == $this->config->item('seccion:coleccion')) { ?>
                    <tr  class="nodrag" id="<?php echo $objDetalleSeccion->id . '_' . $objDetalleSeccion->peso; ?>">
                    <?php } else { ?>
                    <tr id="<?php echo $objDetalleSeccion->id . '_' . $objDetalleSeccion->peso; ?>">
                    <?php } ?>
                    <td><?php echo ($index + 1); ?></td>
                    <td><img style="width:100px;" src="<?php echo $objDetalleSeccion->imagen; ?>" /></td>
                    <td><?php echo $objDetalleSeccion->nombre; ?></td>
                    <td><?php echo $objDetalleSeccion->descripcion_item; ?></td>
                    <td><?php echo $objDetalleSeccion->tipo; ?></td>
                    <td><div style="float: left;"><input class="numeric" type="text" name="peso_<?php echo $objDetalleSeccion->id; ?>" id="peso_<?php echo $objDetalleSeccion->id; ?>" size="2" value="<?php echo $objDetalleSeccion->peso; ?>" /></div><div style="float: left;" id="img_<?php echo $objDetalleSeccion->id; ?>"><?php echo $img; ?></div></td>
                    <td><a href="#" class="btn red" onclick="quitarDetalleSeccion(<?php echo $objDetalleSeccion->id; ?>, <?php echo $canal_id; ?>, <?php echo $objSeccion->id; ?>);
                            return false;">Quitar</a></td>
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
<div style="width:100%;">
    <div  style=" float: left; width: 50%; text-align: left;">
        <a href="#" onclick="guardarSeccion();
                    return false;" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>    
           <?php echo anchor('/admin/canales/vista_previa/', lang('buttons.preview'), array('target' => '_blank', 'class' => 'btn orange modal-large')); ?>
    </div>
    <div style="float: right; width: 50%; text-align: right;">
        <?php echo form_dropdown('template', $templates, $objSeccion->templates_id); ?>            
    </div>
    <div style="clear: both;"></div>
</div>
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
                                ordenarLista();
                            }
                        }
                        else {
                            // Ensure that it is a number and stop the keypress
                            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                event.preventDefault();
                            }
                        }
                    });
                    // Setup the "Up" links
                    $(".subird").click(function() {
                        var row = $(this).closest("tr");
                        subir(row);
//                        console.log(row);
//                        var previous = row.prev();
//                        if (previous.is("tr")) {
//                            row.detach();
//                            previous.before(row);
//                            row.fadeOut();
//                            row.fadeIn();
//                            $("#peso_" + respuesta.subir.id).val(respuesta.subir.peso);
//                            $("#peso_" + respuesta.bajar.id).val(respuesta.bajar.peso);
//                        }
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
                function bajar(detalle_seccion_id, indexOrder, peso) {
                    var post_url = "/admin/canales/bajar/" + detalle_seccion_id + "/" + indexOrder;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            $("#ultimo").val(respuesta.ultimo);
                            $("#primer").val(respuesta.primer);
                            var row = $("#" + detalle_seccion_id + "_" + peso).closest("tr");
                            var previous = row.next();
                            if (previous.is("tr")) {
                                row.detach();
                                previous.after(row);
                                row.fadeOut();
                                row.fadeIn();
                                //modificamos los pesos en las cajas de texto
                                $("#peso_" + respuesta.subir.id).val(respuesta.subir.peso);
                                $("#peso_" + respuesta.bajar.id).val(respuesta.bajar.peso);
                                //reubicamos las flechas de subir y bajar
                                var primerItem = $("#primer").val();
                                if (primerItem == respuesta.subir.id) {
                                    var htmlImgPrimero = '<img title="Bajar" onclick="bajar(' + respuesta.subir.id + ',' + respuesta.subir.index + ', ' + respuesta.subir.peso + ')" src="./uploads/imagenes/down.png" />';
                                    var htmlImgSegundo = '<img title="Subir" onclick="subir(' + respuesta.bajar.id + ',' + respuesta.bajar.index + ', ' + respuesta.bajar.peso + ')" src="./uploads/imagenes/up.png" /><img  onclick="bajar(' + respuesta.bajar.id + ',' + respuesta.bajar.index + ', ' + respuesta.bajar.peso + ')" title="Bajar" src="./uploads/imagenes/down.png" />';
                                    $("#img_" + respuesta.subir.id).empty();
                                    $("#img_" + respuesta.subir.id).html(htmlImgPrimero);
                                    $("#img_" + respuesta.bajar.id).empty();
                                    $("#img_" + respuesta.bajar.id).html(htmlImgSegundo);
                                }
                            } else {
                                //removemos la ultima fila de la tabla
//                        $("#"+respuesta.bajar.id+"_"+respuesta.bajar.peso).remove();
                                $('#table-1 > tbody > tr:last').remove();
                                //creamos un registro del siguiente item que está en la otra paginación
                                var htmlImgSegundo = '<div style="float: left;"><input id="peso_' + respuesta.subir.id + '" class="numeric" type="text" value="6" size="2" name="peso_' + respuesta.subir.id + '"></div><div id="img_' + respuesta.subir.id + '" style="float: left;"><img title="Subir" onclick="subir(' + respuesta.subir.id + ',' + respuesta.subir.index + ', ' + respuesta.subir.peso + ')" src="./uploads/imagenes/up.png" /><img  onclick="bajar(' + respuesta.subir.id + ',' + respuesta.subir.index + ', ' + respuesta.subir.peso + ')" title="Bajar" src="./uploads/imagenes/down.png" /></div>';
                                var htmlRow = '<tr id="' + respuesta.subir.id + '_' + respuesta.subir.peso + '">';
                                htmlRow += '<td>' + respuesta.subir.index + '</td>';
                                htmlRow += '<td><img style="width:100px;" src="' + respuesta.subir.imagen + '" /></td>';
                                htmlRow += '<td>' + respuesta.subir.nombre + '</td>';
                                htmlRow += '<td>' + respuesta.subir.descripcion + '</td>';
                                htmlRow += '<td>' + respuesta.subir.tipo + '</td>';
                                htmlRow += '<td>' + htmlImgSegundo + '</td>';
                                htmlRow += '<td>' + respuesta.subir.id + '</td>';
                                htmlRow += '</tr>';
                                $('#table-1 > tbody:last').append(htmlRow);
                                $("#table-1").tableDnD({
                                    onDrop: function(table, row) {
                                        ordenarLista($.tableDnD.serialize());
                                    }
                                });
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
                function subir(row,detalle_seccion_id, indexOrder, peso) {
                    console.log(row);return true;
                    var post_url = "/admin/canales/subir/" + detalle_seccion_id + "/" + indexOrder;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        //data: indexOrder,
                        success: function(respuesta)
                        {
                            var primer_ultimo = $("#ultimo").val();
                            $("#ultimo").val(respuesta.ultimo);
                            $("#primer").val(respuesta.primer);
                            //var row = $("#" + detalle_seccion_id + "_" + peso).closest("tr");
                            //console.log(row);
                            var previous = row.prev();
                            if (previous.is("tr")) {
                                row.detach();
                                previous.before(row);
                                row.fadeOut();
                                row.fadeIn();
                                //modificamos los pesos en las cajas de texto
                                $("#peso_" + respuesta.subir.id).val(respuesta.subir.peso);
                                $("#peso_" + respuesta.bajar.id).val(respuesta.bajar.peso);
                                //reubicamos las flechas de subir y bajar
                                //var ultimoItem = $("#ultimo").val();
                                console.log(primer_ultimo);
                                console.log(respuesta.subir.id);
                                console.log(respuesta.bajar.id);
                                if (primer_ultimo == respuesta.subir.id) {
                                    var htmlImgUltimo = '<img title="Subir" onclick="subir(' + respuesta.bajar.id + ',' + respuesta.bajar.index + ', ' + respuesta.bajar.peso + ')" src="./uploads/imagenes/up.png" />';
                                    var htmlImgArriba = '<img title="Subir" onclick="subir(' + respuesta.subir.id + ',' + respuesta.subir.index + ', ' + respuesta.subir.peso + ')" src="./uploads/imagenes/up.png" /><img  onclick="bajar(' + respuesta.subir.id + ',' + respuesta.subir.index + ', ' + respuesta.subir.peso + ')" title="Bajar" src="./uploads/imagenes/down.png" />';
                                    $("#img_" + respuesta.bajar.id).empty();
                                    $("#img_" + respuesta.bajar.id).html(htmlImgUltimo);
                                    $("#img_" + respuesta.subir.id).empty();
                                    $("#img_" + respuesta.subir.id).html(htmlImgArriba);
                                }
                            } else {
                                console.log("c");
                            }

                        } //end success
                    }); //end AJAX                  
                }
</script>