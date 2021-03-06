<style>
    .table-list tbody tr:hover{
        background-color: #CCE4E5;
    }
</style>
<table>
    <tr>
        <td style="text-align: right;">
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
            <!-- imagen -->
            <?php
            $imagen = array('class' => 'btn blue','name' => 'addImage', 'id' => 'addImage', 'type' => 'button', 'value' => 'Agrega nuevas imagenes');
            echo '<div style="float:right;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderAjax" id="loaderAjax" style="display: none; float: right;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <div style="clear: both;"></div>
<!--            <div id="contenedorImage">
                <?php //if (count($objMaestro->avatar) > 0) { ?>
                    <select id="listaImagenes"></select>
                <?php //} ?>
            </div>             -->
        </td>
    </tr>
</table>
<table class="table-list">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th style="width: 20%">Imagen</th>
            <th style="width: 10%">Tipo</th>
            <th style="width: 10%">tamaño</th>
            <th style="width: 10%">Fecha publicación</th>
            <th style="width: 5%">Existe imagen</th>
            <th style="width: 10%">estado</th>
            <th style="width: 20%">Acciones</th>
            <th style="width: 10%">Proceso</th>
            <th style="width: 5%">ID</th>
        </tr>
    </thead>
    <tbody id="divContenidoImagen">
        <?php
        if (count($imagenes) > 0):
            foreach ($imagenes as $puntero => $objImagen):
                ?>
                <tr>
                    <td style="width: 5%"><?php echo ($puntero + 1) ?>
                        <?php if($puntero==0) { echo '<input type="hidden" name="tipo_origen" id="tipo_origen" value="'.$objImagen->tipo.'" />';} ?>
                    </td>
                    <td id="tipo_<?php echo $objImagen->tipo_imagen_id; ?>" style="width: 20%"><img style="width:120px; height: 70px;" src="<?php echo $objImagen->imagen; ?>" /></td>
                    <td style="width: 10%"><?php echo $objImagen->tipo_imagen; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->tamanio; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->fecha_registro; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->existe; ?></td>
                    <td style="width: 10%"><?php echo lang('global:' . $objImagen->estado . '_estado'); ?></td>
                    <td id="accion_<?php echo $objImagen->tipo_imagen_id; ?>" style="width: 20%"><?php echo $objImagen->accion; ?></td>
                    <td id="proceso_<?php echo $objImagen->tipo_imagen_id; ?>" style="width: 10%"><?php echo $objImagen->progreso; ?></td>
                    <td id="codigo_<?php echo $objImagen->tipo_imagen_id; ?>" style="width: 5%"><?php echo $objImagen->id; ?></td>
                </tr>
                <?php
            endforeach;
            ?>

        <?php else: ?>
            <tr>
                <td colspan="8">No hay items</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8"></td>
        </tr>
    </tfoot>
</table>
<div id="divRestaurar"></div>
<script type="text/javascript">
    function restaurar_imagen(tipo_imagen, maestro_id) {
        $("#divRestaurar").dialog("open");
        //llenar el formulario
        var post_url = "/admin/videos/formulario_restaurar_imagen/" + maestro_id + "/" + tipo_imagen;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'html',
            data: 'tipo_origen='+$("#tipo_origen").val(),
            success: function(respuesta)
            {
                $("#divRestaurar").html(respuesta);
            } //end success
        }); //end AJAX

        $("#divRestaurar").dialog({
            title: "Restaurar imagen",
            autoOpen: true,
            height: 440,
            width: 540,
            modal: true
        });
    }

    function restaurar_imagen_grupo(imagen_id, tipo_imagen, maestro_id, tipo_origen) {
        //llenar el formulario
        var post_url = "/admin/videos/restaurar_imagen_grupo/" + imagen_id + "/" + tipo_imagen + "/" + maestro_id+"/"+tipo_origen;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            //data: serializedData,
            success: function(respuesta)
            {
                $("#divRestaurar").html(respuesta);
                //pintamos la nueva imagen
                var url_nueva_imagen = respuesta.url;
                $('#tipo_' + tipo_imagen).html('<img style="width:120px; height: 70px;" src="' + url_nueva_imagen + '" />');
                $('#codigo_' + tipo_imagen).html(imagen_id);
                $("#divRestaurar").dialog("close");
            } //end success
        }); //end AJAX    
    }

    $(document).ready(function() {
<?php
if (count($imagenes) > 0):
    foreach ($imagenes as $puntero => $objImagen):
    if($objImagen->tipo == 'video'){
        $objImagen->grupo_maestros_id = $objImagen->videos_id;
    }else{
        if($objImagen->tipo == 'canal'){
            $objImagen->grupo_maestros_id = $objImagen->canales_id;
        }
    }
        ?>
                $fub = $('#fine-uploader-basic_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>');
                $messages = $('#messages_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>');
                var uploader = new qq.FineUploaderBasic({
                    button: $fub[0],
                    request: {
                        endpoint: 'admin/videos/subir_imagen_grupo/<?php echo $objImagen->grupo_maestros_id; ?>/<?php echo $objImagen->tipo_imagen_id; ?>/<?php echo $objImagen->id; ?>/<?php echo $objImagen->tipo; ?>'
                    },
                    validation: {
                        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                        sizeLimit: 10240000//204800 // 200 kB = 200 * 1024 bytes
                    },
                    callbacks: {
                        onSubmit: function(id, fileName) {
                            $('#messages_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>').empty()
                            $('#messages_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>').append('<div id="file-<?php echo $objImagen->tipo_imagen_id; ?>" class="alert" style=" width:100px; margin: 20px 0 0"></div>');
                        },
                        onUpload: function(id, fileName) {
                            $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').empty();
                            $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').addClass('alert-info')
                                    .css('width', '100px')
                                    .html('<img src="client/loading.gif" alt="Initializing. Please hold."> ' +
                                    'Initializing ' +
                                    '“' + fileName + '”');
                        },
                        onProgress: function(id, fileName, loaded, total) {
                            if (loaded < total) {
                                progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').empty();
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .css('width', '100px')
                                        .html('<img src="client/loading.gif" alt="In progress. Please hold."> ' +
                                        'Uploading ' +
                                        '“' + fileName + '” ' +
                                        progress);
                            } else {
                                //$('#file-'+id)
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').empty();
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').addClass('alert-info')
                                        .css('width', '100px')
                                        .html('<img src="client/loading.gif" alt="Saving. Please hold."> ' +
                                        'Saving ' +
                                        '“' + fileName + '”');
                            }
                        },
                        onComplete: function(id, fileName, responseJSON) {
                            if (responseJSON.success) {
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').empty();
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .addClass('alert-success')
                                        .css('width', '100px')
                                        .html('<i class="icon-ok"></i> ' +
                                        'Successfully saved ' +
                                        '“' + fileName + '”' +
                                        '<br><img src="img/success.jpg" alt="' + fileName + '">');
                                //pintamos la nueva imagen
                                var url_nueva_imagen = responseJSON.url;
                                $('#tipo_<?php echo $objImagen->tipo_imagen_id; ?>').html('<img style="width:120px; height: 70px;" src="' + url_nueva_imagen + '" />');
                                $('codigo_<?php echo $objImagen->tipo_imagen_id; ?>').html(responseJSON.imagen_id);
                            } else {
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').empty();
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .addClass('alert-error')
                                        .css('width', '100px')
                                        .html('<i class="icon-exclamation-sign"></i> ' +
                                        'Error with ' +
                                        '“' + fileName + '”: ' +
                                        responseJSON.error);
                            }
                        }
                    }
                });

        <?php
    endforeach;
endif;
?>
    });
</script>