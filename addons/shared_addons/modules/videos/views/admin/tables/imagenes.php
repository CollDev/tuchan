<style>
    .table-list tbody tr:hover{
        background-color: #CCE4E5;
    }
</style>
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
                    <td style="width: 5%"><?php echo ($puntero + 1) ?></td>
                    <td style="width: 20%"><div id="tipo_<?php echo $objImagen->tipo_imagen_id; ?>"><img style="width:120px; height: 70px;" src="<?php echo $objImagen->imagen; ?>" /></div></td>
                    <td style="width: 10%"><?php echo $objImagen->tipo_imagen; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->tamanio; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->fecha_registro; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->existe; ?></td>
                    <td style="width: 10%"><?php echo lang('global:' . $objImagen->estado . '_estado'); ?></td>
                    <td style="width: 20%"><?php echo $objImagen->accion; ?></td>
                    <td style="width: 10%"><?php echo $objImagen->progreso; ?></td>
                    <td style="width: 5%"><?php echo $objImagen->id; ?></td>
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
<script type="text/javascript">
    $(document).ready(function() {
<?php
if (count($imagenes) > 0):
    foreach ($imagenes as $puntero => $objImagen):
        ?>

                $fub = $('#fine-uploader-basic_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>');
                console.log($fub);
                $messages = $('#messages_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>');

                var uploader = new qq.FineUploaderBasic({
                    button: $fub[0],
                    request: {
                        endpoint: 'admin/videos/subir_imagen_grupo/<?php echo $objImagen->grupo_maestros_id; ?>/<?php echo $objImagen->tipo_imagen_id; ?>/<?php echo $objImagen->id; ?>'
                    },
                    validation: {
                        allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                        sizeLimit: 10240000//204800 // 200 kB = 200 * 1024 bytes
                    },
                    callbacks: {
                        onSubmit: function(id, fileName) {
                            $('#messages_<?php echo $objImagen->tipo_imagen_id; ?>_<?php echo $objImagen->id; ?>').append('<div id="file-<?php echo $objImagen->tipo_imagen_id; ?>" class="alert" style="margin: 20px 0 0"></div>');
                        },
                        onUpload: function(id, fileName) {
                            $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').addClass('alert-info')
                                    .css('width','100px')
                                    .html('<img src="client/loading.gif" alt="Initializing. Please hold."> ' +
                                    'Initializing ' +
                                    '“' + fileName + '”');
                        },
                        onProgress: function(id, fileName, loaded, total) {
                            if (loaded < total) {
                                progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .css('width','100px')
                                        .html('<img src="client/loading.gif" alt="In progress. Please hold."> ' +
                                        'Uploading ' +
                                        '“' + fileName + '” ' +
                                        progress);
                            } else {
                                //$('#file-'+id)
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').addClass('alert-info')
                                        .css('width','100px')
                                        .html('<img src="client/loading.gif" alt="Saving. Please hold."> ' +
                                        'Saving ' +
                                        '“' + fileName + '”');
                            }
                        },
                        onComplete: function(id, fileName, responseJSON) {
                            if (responseJSON.success) {
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .addClass('alert-success')
                                        .css('width','100px')
                                        .html('<i class="icon-ok"></i> ' +
                                        'Successfully saved ' +
                                        '“' + fileName + '”' +
                                        '<br><img src="img/success.jpg" alt="' + fileName + '">');
                                //pintamos la nueva imagen
                                var url_nueva_imagen = responseJSON.url;
                                $('#tipo_<?php echo $objImagen->tipo_imagen_id; ?>').html('<img style="width:120px; height: 70px;" src="'+url_nueva_imagen+'" />');                                
                            } else {
                                $('#file-<?php echo $objImagen->tipo_imagen_id; ?>').removeClass('alert-info')
                                        .addClass('alert-error')
                                        .css('width','100px')
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