<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<section class="title">
    <h4><?php echo $title; ?></h4>
</section>
<section class="item">
    <?php
    $attributes = array('class' => 'frm', 'id' => 'frmSeccion', 'name' => 'frmSeccion');
    echo form_open_multipart('admin/portadas/guardar_seccion/' . $objSeccion->id, $attributes);
    ?>
    <div class="main_opt">
        <!-- titulo -->
        <label for="titulo"><?php echo lang('canales:nombre_label'); ?> <span class="required">*</span></label>
        <?php
        $titulo = array(
            'name' => 'nombre',
            'id' => 'nombre',
            'value' => $objSeccion->nombre,
            'maxlength' => '100',
            'style' => 'width:556px;'
                //'readonly'=>'readonly'
        );
        echo form_input($titulo);
        ?>
        <!-- titulo -->
        <label for="descripcion"><?php echo lang('canales:descripcion_label'); ?> <span class="required">*</span></label>
        <?php
        $descripcion = array(
            'name' => 'descripcion',
            'id' => 'descripcion',
            'value' => $objSeccion->descripcion,
            'maxlength' => '100',
            'style' => 'width:556px;'
        );
        echo form_input($descripcion);
        ?>
        <?php
        echo '<div id="filter-stage">';
        template_partial('secciones');
        echo '</div>';
        ?>
        <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
        <input type="hidden" name="portada_id" id="portada_id" value="<?php echo $objSeccion->portadas_id; ?>" />
        <input type="hidden" name="seccion_id" id="seccion_id" value="<?php echo $objSeccion->id; ?>" />
    </div>
    <?php echo form_close() ?>
    <script type="text/javascript">
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
        /**
         * Método para agregar un item de tipo maestro a la seccion
         * @param int maestro_id
         * @param int seccion_id
         * @returns boolean         
         * */
        function agregarItemMaestro(maestro_id, seccion_id) {
            //var serializedData = $('#frmBuscar').serialize(); 
            var post_url = "/admin/canales/agregar_item_maestro/";
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'json',
                data: 'maestro_id=' + maestro_id + '&seccion_id=' + seccion_id,
                success: function(respuesta)
                {
                    if (respuesta.error > 0) {
                        switch (respuesta.error) {
                            case 1 :
                                showMessage('error', '<?php echo lang('seccion:not_found_item'); ?>', 2000, '');
                                break;
                            case 2:
                                showMessage('error', '<?php echo lang('seccion:not_found_small_image'); ?>', 2000, '');
                                break;
                            case 3:
                                showMessage('error', '<?php echo lang('video:video_codificando'); ?>', 2000, '');
                                break;
                            case 4:
                                showMessage('error', '<?php echo lang('video:coleccion_tiene_registro'); ?>', 2000, '');
                                break;
                            case 5:
                                showMessage('error', '<?php echo lang('video:coleccion_no_imagen_large'); ?>', 2000, '');
                                break;
                            case 6:
                                showMessage('error', '<?php echo lang('video:coleccion_sin_lista'); ?>', 2000, '');
                                break;
                            case 7:
                                showMessage('error', '<?php echo lang('video:listas_sin_imagenes'); ?>', 2000, '');
                                break;
                        }
                    } else {
                        $("#div_" + maestro_id).empty();
                        var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + maestro_id).html(htmlAgregado);
                    }
                } //end success
            }); //end AJAX             
        }

        function agregarItemVideo(video_id, seccion_id) {
            var post_url = "/admin/canales/agregar_item_video/";
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'json',
                data: 'video_id=' + video_id + '&seccion_id=' + seccion_id,
                success: function(respuesta)
                {
                    if (respuesta.error > 0) {
                        switch (respuesta.error) {
                            case 1 :
                                showMessage('error', '<?php echo lang('seccion:not_found_item'); ?>', 2000, '');
                                break;
                            case 2:
                                showMessage('error', '<?php echo lang('seccion:not_found_small_image'); ?>', 2000, '');
                                break;
                            case 3:
                                showMessage('error', '<?php echo lang('video:video_codificando'); ?>', 2000, '');
                                break;
                            case 4:
                                showMessage('error', '<?php echo lang('video:coleccion_tiene_registro'); ?>', 2000, '');
                                break;
                            case 5:
                                showMessage('error', '<?php echo lang('video:coleccion_no_imagen_large'); ?>', 2000, '');
                                break;
                            case 6:
                                showMessage('error', '<?php echo lang('video:coleccion_sin_lista'); ?>', 2000, '');
                                break;
                            case 7:
                                showMessage('error', '<?php echo lang('video:listas_sin_imagenes'); ?>', 2000, '');
                                break;
                        }
                    } else {
                        $("#div_" + video_id).empty();
                        var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + video_id).html(htmlAgregado);
                    }
                } //end success
            }); //end AJAX             
        }

        function agregarItem(maestro_id, seccion_id) {
            var post_url = "/admin/canales/agregar_item/";
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'json',
                data: 'maestro_id=' + maestro_id + '&seccion_id=' + seccion_id,
                success: function(respuesta)
                {
                    if (respuesta.error > 0) {
                        switch (respuesta.error) {
                            case 1 :
                                showMessage('error', '<?php echo lang('seccion:not_found_item'); ?>', 2000, '');
                                break;
                            case 2:
                                showMessage('error', '<?php echo lang('seccion:not_found_small_image'); ?>', 2000, '');
                                break;
                            case 3:
                                showMessage('error', '<?php echo lang('video:video_codificando'); ?>', 2000, '');
                                break;
                            case 4:
                                showMessage('error', '<?php echo lang('video:coleccion_tiene_registro'); ?>', 2000, '');
                                break;
                            case 5:
                                showMessage('error', '<?php echo lang('video:coleccion_no_imagen_large'); ?>', 2000, '');
                                break;
                            case 6:
                                showMessage('error', '<?php echo lang('video:coleccion_sin_lista'); ?>', 2000, '');
                                break;
                            case 7:
                                showMessage('error', '<?php echo lang('video:listas_sin_imagenes'); ?>', 2000, '');
                                break;
                            case 8:
                                showMessage('error', '<?php echo lang('video:no_coleccion'); ?>', 2000, '');
                                break;
                        }
                    } else {
                        $("#div_" + maestro_id).empty();
                        var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + maestro_id).html(htmlAgregado);
                    }
                } //end success
            }); //end AJAX             
        }

        function buscar() {
            var serializedData = $('#frmBuscar').serialize();
            var post_url = "/admin/canales/buscar_seccion/";
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'html',
                data: serializedData,
                success: function(respuesta)
                {
                    $("#divResultado").html(respuesta);
                    $('#black').smartpaginator({
                        totalrecords: $("#total").val(),
                        recordsperpage: 7,
                        theme: 'black',
                        onchange: function(newPage) {
                            //$('#r').html('Page # ' + newPage);
                            paginarItems(newPage);
                        }
                    });
                } //end success
            }); //end AJAX 
        }
        /**
         * método para imprimir html de acuerdo al numero de pagina enviado como parametro
         * @param int newPage
         * @returns html         
         * */
        function paginarItems(newPage) {
            var serializedData = $('#frmBuscar').serialize();
            var post_url = "/admin/canales/obtener_lista_paginado/" + newPage;
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'html',
                data: serializedData,
                success: function(respuesta)
                {
                    $("#resultado").html(respuesta);
                } //end success
            }); //end AJAX         
        }
    </script>
</section>
