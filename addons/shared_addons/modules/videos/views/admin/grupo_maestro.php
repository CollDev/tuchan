<section class="title">
    <?php
    if ($objMaestro->id > 0):
        $title_tab = 'Editar ' . $objMaestro->nombre;
        ?>
        <h4><?php echo 'Editar ' . $objMaestro->nombre; ?></h4>
        <?php
    else:
        $title_tab = 'Crear nuevo maestro ';
        ?>
        <h4><?php echo 'Agregar nuevo programa, colección o Lista' ?></h4>
    <?php endif; ?>
</section>
<section class="item">
    <?php
    // Canales_id       
    $hidden = array('canal_id' => $objCanal->id);
    $attributes = array('class' => 'frm', 'id' => 'formMaestro', 'name' => 'formMaestro');
    echo form_open_multipart('admin/videos/grupo_maestro/' . $objCanal->id, $attributes, $hidden);
    //echo form_open_multipart('file.php', $attributes, $hidden);
    ?>    
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1"><?php echo $title_tab; ?></a></li>
            <?php if ($objMaestro->id > 0): ?>
                <li><a href="#tabs-2">Imagenes</a></li>
                <li><a href="#tabs-3">Items</a></li>
            <?php endif; ?>  
        </ul>
        <div id="tabs-1"  style="width: 100%;">
            <div class="left_arm">
                <!-- titulo -->
                <label for="titulo"><?php echo lang('videos:title'); ?> <span class="required">*</span></label>
                <?php
                $titulo = array(
                    'name' => 'titulo',
                    'id' => 'titulo',
                    'value' => $objMaestro->nombre,
                    'maxlength' => '100',
                    'style' => 'width:556px;'
                        //'readonly'=>'readonly'
                );
                echo form_input($titulo);
                ?>
                <br /><br /><br />
                <!-- descripcion -->
                <label for="descripcion"><?php echo lang('videos:description'); ?><span class="required">*</span></label>
                <?php echo form_textarea(array('id' => 'descripcion', 'name' => 'descripcion', 'value' => $objMaestro->descripcion, 'rows' => 5, 'class' => 'wysiwyg-simple')); ?>        

                <?php if ($objMaestro->id > 0) { ?>
                    <!-- imagen -->
                    <label for="imagen"><?php echo lang('videos:avatar'); ?></label>
                    <?php
                    $imagen = array('name' => 'addImage', 'id' => 'addImage', 'type' => 'button', 'value' => 'Agrega nuevas imagenes a tu programa');
                    echo '<div style="float:left;">' . form_input($imagen) . '</div>';
                    ?>
                    <div  class="loaderAjax" id="loaderAjax" style="display: none; float: left;">
                        <img src="uploads/imagenes/loading.gif">
                    </div>
                    <div style="clear: both;"></div>
                    <div id="contenedorImage">
                        <?php if (count($objMaestro->avatar) > 0) { ?>
                            <select id="listaImagenes"></select>
                        <?php } ?>
                    </div>

                <?php } else { ?>
                    <!-- imagen -->
                    <label for="imagen"><?php echo lang('videos:avatar'); ?></label>
                    <?php
                    $imagen = array('name' => 'addImage', 'id' => 'addImage', 'type' => 'button', 'value' => 'Agrega nuevas imagenes a tu programa');
                    echo '<div style="float:left;">' . form_input($imagen) . '</div>';
                    ?>
                    <div  class="loaderAjax" id="loaderAjax" style="display: none; float: left;">
                        <img src="uploads/imagenes/loading.gif">
                    </div>
                    <div style="clear: both;"></div>
                    <div id="contenedorImage"></div>
                <?php } ?>        

            </div>
            <div class="right_arm">
                <!-- tipo -->
                <label for="tipo"><?php echo lang('videos:tipo_label'); ?></label>
                <?php echo form_error('tipo'); ?><br/>
                <?php echo form_dropdown('tipo', $tipo_maestros, $objMaestro->tipo_grupo_maestro_id, 'onchange="generarMaestro();return false;"'); ?>          
                <br/></br>
                <div id="divPrograma"></div>        
                <!-- tipo -->
                <label for="categoria"><?php echo lang('videos:category'); ?></label>
                <?php echo form_dropdown('categoria', $categorias, $objMaestro->categorias_id); ?>          
                <br/></br>        
                <label for="tematicas"><?php echo lang('videos:etiquetas_tematicas_label'); ?> <span class="required">*</span></label>
                <div class="input"><?php echo form_input('tematicas', $objMaestro->tematicas, 'id="tematicas"') ?></div>
                <br/></br>
                <!-- tags personajes -->
                <label for="personajes"><?php echo lang('videos:etiquetas_personajes_label'); ?><span class="required">*</span></label>
                <div class="input"><?php echo form_input('personajes', $objMaestro->personajes, 'id="personajes"') ?></div>        
            </div>
            <div class="main_opt">
                    <br /><br />
                    <input type="button" onclick="guardarMaestro();return false;" class="btn orange" name="btnGuardar" id="btnGuardar" value="<?php echo lang('buttons.save') ?>" />                            
            </div>

        </div>
        <?php if ($objMaestro->id > 0): ?>
            <div id="tabs-2">
                Tab en desarrollo
            </div>
            <div id="tabs-3">
                <div class="main_opt"  style="width: 100%;">
                    <?php if ($objMaestro->id > 0): ?>
                        <div id="filter-stage">
                            <?php template_partial('contenidos'); ?>
                        </div>            
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>     



    <input type="hidden" id="maestro_id" name="maestro_id" value="<?php echo $objMaestro->id; ?>" />
    <input type="hidden" id="imagen_maestro" name="imagen_maestro" value="" />
    <?php if ($objMaestro->id > 0): ?>
        <input type="hidden" id="tipo_id" name="tipo_id" value="<?php echo $objMaestro->tipo_grupo_maestro_id; ?>" />
    <?php else: ?>
        <input type="hidden" id="tipo_id" name="tipo_id" value="<?php echo $this->config->item('videos:programa'); ?>" />
    <?php endif; ?>    
    <?php echo form_close() ?>
</section>
<script type="text/javascript">
                        $(document).ready(function() {
                            $("#tabs").tabs();

<?php if ($objMaestro->id > 0): ?>
                                //Dropdown plugin data
                                var ddData = <?php echo json_encode($objMaestro->avatar) . ';'; ?>

                                $('#listaImagenes').ddslick({
                                    data: ddData,
                                    width: 300,
                                    imagePosition: "center",
                                    selectText: "Seleccione su imagen principal",
                                    onSelected: function(data) {
                                        //console.log(data['selectedData'].value);
                                        activeImageMaestro(data['selectedData'].value);
                                    }
                                });
<?php endif; ?>
                            //var ind = $("indiceImage").val();
                            //$('#listaImagenes').ddslick('select', {index: ind });
                            // needed so that Keywords can return empty JSON
                            $.ajaxSetup({
                                allowEmpty: true
                            });

                            $('#tematicas').tagsInput({
                                autocomplete_url: 'admin/videos/tematicas'
                            });

                            $('#personajes').tagsInput({
                                autocomplete_url: 'admin/videos/personajes'
                            });

                            var btn_firma = $('#addImage'), interval;
                            new AjaxUpload('#addImage', {
                                action: 'admin/videos/subir_imagen/' + '<?php echo $objMaestro->id; ?>',
                                onSubmit: function(file, ext) {
                                    if (!(ext && /^(jpg|png)$/.test(ext))) {
                                        // extensiones permitidas
                                        alert('Sólo se permiten Imagenes .jpg o .png');
                                        // cancela upload
                                        return false;
                                    } else {
                                        $('#loaderAjax').show();
                                        btn_firma.text('Espere por favor');
                                        this.disable();
                                    }
                                },
                                onComplete: function(file, response) {
                                    btn_firma.text('Cambiar Imagen');
                                    respuesta = $.parseJSON(response);
                                    if (respuesta.respuesta == 'done') {
<?php if ($objMaestro->id > 0): ?>
                                            saveImages(respuesta);
<?php else: ?>
                                            $('#loaderAjax').hide();
                                            $("#imagen_maestro").val(respuesta.fileName);
                                            var htmlImg = '<img src="uploads/temp/' + respuesta.fileName + '" title="' + respuesta.fileName + '" style="width:570px;" />';
                                            $("#contenedorImage").html(htmlImg);
<?php endif; ?>
                                        console.log(respuesta);
                                    }
                                    else {
                                        alert(respuesta.mensaje);
                                    }

                                    this.enable();
                                }
                            });

                        });

                        function saveImages(respuesta) {
                            var values = {};
                            $.each($('#formMaestro').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            var post_url = "/admin/videos/registrar_imagenes_maestro/" + values['maestro_id'];
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'json',
                                data: respuesta,
                                success: function(returnRespuesta) //we're calling the response json array 'cities'
                                {
                                    $('#loaderAjax').hide();
                                    //limpiar
                                    $('#listaImagenes').ddslick('destroy');
                                    $("#contenedorImage").empty();
                                    var htmlN = '<select id="listaImagenes">';
                                    $.each(returnRespuesta.imagenes, function(k, v) {
                                        if (v.estado == '1') {
                                            htmlN += '<option selected=\"selected\" value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
                                        } else {
                                            htmlN += '<option value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
                                        }
                                    });
                                    htmlN += '</select>';
                                    $("#contenedorImage").html(htmlN);
                                    $('#listaImagenes').ddslick({
                                        width: 300,
                                        imagePosition: "center",
                                        selectText: "Seleccione su imagen principal",
                                        onSelected: function(data) {
                                            //console.log(data);
                                            activeImageMaestro(data['selectedData'].value);
                                        }
                                    });

                                } //end success
                            }); //end AJAX        
                        }

                        function activeImageMaestro(imagen_id) {
                            var maestro_id = $("#maestro_id").val();
                            var post_url = "/admin/videos/active_imagen_maestro/" + maestro_id + "/" + imagen_id;
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                //dataType: 'json',
                                //data:imagen_id,
                                success: function(returnRespuesta) //we're calling the response json array 'cities'
                                {
                                    console.log(returnRespuesta);
                                } //end success
                            }); //end AJAX              
                        }

                        function guardarMaestro() {
                            var values = {};
                            $.each($('#formMaestro').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            var editorText = CKEDITOR.instances.descripcion.getData();
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'descripcion_updated',
                                name: 'descripcion_updated',
                                value: editorText
                            }).appendTo('#formMaestro');
<?php if ($objMaestro->id > 0): ?>
                                var nombre_imagen = 'aaa';
<?php else: ?>
                                var nombre_imagen = $.trim(values['imagen_maestro']);
<?php endif; ?>
                            var titulo = $.trim($("#titulo").val());
                            values['tematicas'] = $.trim(values['tematicas']);
                            values['personajes'] = $.trim(values['personajes']);
                            if (titulo.length > 0) {
                                //validamos el ckeditor
                                var editorText = CKEDITOR.instances.descripcion.getData();
                                editorText = $.trim(editorText);
                                var regex = /(<([^>]+)>)/ig;
                                var editorText2 = editorText.replace(regex, "");
                                editorText2 = $.trim(editorText2);
                                editorText2 = editorText2.replace(/(&nbsp;)*/g, "");
                                if (editorText.length > 0 && editorText2.length > 0) {
                                    if (values['tematicas'].length > 0) {
                                        if (values['personajes'].length > 0) {
                                            if (nombre_imagen.length > 0) {
                                                if (values['categoria'].length > 0) {
                                                    if (values['tipo'] == '1') {
                                                        var tipo = 'Lista de reproducción';
                                                    } else {
                                                        if (values['tipo'] == '2') {
                                                            var tipo = 'Colección';
                                                        } else {
                                                            var tipo = 'Programa';
                                                        }
                                                    }
<?php if ($objMaestro->id == 0): ?>
                                                        jConfirm("Seguro que desea crear un maestro de tipo: " + tipo, "Crear Maestro", function(r) {
                                                            if (r) {
<?php endif; ?>
                                                            var serializedData = $('#formMaestro').serialize();
<?php if ($objMaestro->id == 0): ?>
                                                                loading('#loading');
<?php endif; ?>
                                                            //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                                                            var post_url = "/admin/videos/guardar_maestro/";
                                                            $.ajax({
                                                                type: "POST",
                                                                url: post_url,
                                                                dataType: 'json',
                                                                data: serializedData,
                                                                success: function(respuesta)
                                                                {
                                                                    if (respuesta.value == 0) {
<?php if ($objMaestro->id == 0): ?>
                                                                            var url = 'admin/videos/grupo_maestro/' + respuesta.canal_id + "/" + respuesta.maestro_id;
                                                                            $(location).attr('href', '<?php echo BASE_URL; ?>' + url);
<?php else: ?>
                                                                            showMessage('exit', '<?php echo lang('videos:edit_video_success') ?>', 2000, '');
<?php endif; ?>
                                                                    } else {
                                                                        showMessage('error', '<?php echo lang('maestros:maestro_existe') ?>', 2000, '');
                                                                    }
                                                                } //end success
                                                            }); //end AJAX
<?php if ($objMaestro->id == 0): ?>
                                                            }
                                                        });
<?php endif; ?>
                                                } else {
                                                    showMessage('error', '<?php echo lang('videos:require_category') ?>', 2000, '');
                                                }
                                            } else {
                                                showMessage('error', '<?php echo lang('videos:require_images') ?>', 2000, '');
                                            }
                                        } else {
                                            showMessage('error', '<?php echo lang('videos:require_personajes') ?>', 2000, '');
                                        }
                                    } else {
                                        showMessage('error', '<?php echo lang('videos:require_tematicas') ?>', 2000, '');
                                    }
                                } else {
                                    showMessage('error', '<?php echo lang('videos:require_description') ?>', 2000, '');
                                }
                            } else {
                                showMessage('error', '<?php echo lang('videos:require_title') ?>', 2000, '');
                            }
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
                        function generarMaestro() {
                            var values = {};
                            $.each($('#formMaestro').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            if ($("#tipo_id").val() != values['tipo']) {
                                $("#tipo_id").val(values['tipo']);
                                if ($("#tipo_id").val() == '3') {
                                    $("#divPrograma").empty();
                                } else {
                                    generar_programa();
                                }
                            }
                        }

                        function generar_programa() {
                            var serializedData = $('#formMaestro').serialize();
                            var post_url = "/admin/videos/generar_programa/";
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'html',
                                data: serializedData,
                                success: function(respuesta)
                                {
                                    $("#divPrograma").html(respuesta);
                                    //$("#divResultado").html(respuesta);
                                    /*$('#black').smartpaginator({
                                        totalrecords: $("#total").val(),
                                        recordsperpage: 3,
                                        theme: 'black',
                                        onchange: function(newPage) {
                                            //$('#r').html('Page # ' + newPage);
                                            paginarItems(newPage);
                                        }
                                    });*/
                                } //end success
                            }); //end AJAX           
                        }

                        function generar_coleccion() {
                            var values = {};
                            $.each($('#formMaestro').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            if (values['programa'] == '0') {
                                $("#divColeccion").empty();
                            } else {
                                var serializedData = $('#formMaestro').serialize();
                                var post_url = "/admin/videos/generar_coleccion/";
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    dataType: 'html',
                                    data: serializedData,
                                    success: function(respuesta)
                                    {
                                        $("#divColeccion").html(respuesta);
                                    } //end success
                                }); //end AJAX                  
                            }
                        }

                        function loading(id) {
                            var winH = $(window).height();
                            var winW = $(window).width();

                            var dialog = $(id);
                            //console.log(dialog);
                            var maxheight = dialog.css("max-height");
                            var maxwidth = dialog.css("max-width");

                            var dialogheight = dialog.height();
                            var dialogwidth = dialog.width();

                            if (maxheight != "none") {
                                dialogheight = Number(maxheight.replace("px", ""));
                            }
                            if (maxwidth != "none") {
                                dialogwidth = Number(maxwidth.replace("px", ""));
                            }

                            dialog.css('top', winH / 2 - dialogheight / 2);
                            dialog.css('left', winW / 2 - dialogwidth / 2);
                            dialog.css('display', 'block');
                            $("#imgLoading").css('z-index', 400);
                            dialog.css('z-index', '399');
                            $("#loadingModal").css('position', 'absolute');
                            $("#loadingModal").css('height', $(document).height());
                            $("#loadingModal").css('width', winW);
                            $("#loadingModal").css('z-index', '388');
                        }

                        function listar_para_lista(numero_pagina) {
                            var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/listar_para_lista/" + numero_pagina;
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
                                        recordsperpage: $("#cantidad_mostrar").val(),
                                        theme: 'black',
                                        onchange: function(newPage) {
                                            //$('#r').html('Page # ' + newPage);
                                            paginarLista(newPage);
                                        }
                                    });
                                } //end success
                            }); //end AJAX              
                        }
                        function listar_para_programa(numero_pagina) {
                            var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/listar_para_programa/" + numero_pagina;
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
                                        recordsperpage: $("#cantidad_mostrar").val(),
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
                            var post_url = "/admin/videos/listar_para_programa/" + newPage + "/1";
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
                        
                        /**
                         * método para imprimir html de acuerdo al numero de pagina enviado como parametro
                         * @param int newPage
                         * @returns html         
                         * */
                        function paginarColeccion(newPage) {
                            var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/listar_para_coleccion/" + newPage + "/1";
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
                        
                        function paginarLista(newPage){
                            var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/listar_para_lista/" + newPage + "/1";
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
                        
                        function listar_para_coleccion(numero_pagina){
                            var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/listar_para_coleccion/" + numero_pagina;
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
                                        recordsperpage: $("#cantidad_mostrar").val(),
                                        theme: 'black',
                                        onchange: function(newPage) {
                                            //$('#r').html('Page # ' + newPage);
                                            paginarColeccion(newPage);
                                        }
                                    });
                                } //end success
                            }); //end AJAX                         
                        }

                        function agregarMaestroAMaestro(maestro_id, parent_maestro) {
                            var post_url = "/admin/videos/agregarMaestroAMaestro/" + maestro_id + '/' + parent_maestro;
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'html',
                                data: 'maestro_id=' + maestro_id + '&parent_maestro=' + parent_maestro,
                                success: function(respuesta)
                                {
                                    $("#divContenido").html(respuesta);
                                    var maestro_agregado = $("#maestro_agregado").val();
                                    $("#div_" + maestro_agregado).empty();
                                    var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                                    $("#div_" + maestro_agregado).html(htmlAgregado);
                                    
                                } //end success
                            }); //end AJAX              
                        }

                        function quitarGrupoMaestro(grupo_detalle_id, parent_maestro) {
                            //var serializedData = $('#frmBuscar').serialize();
                            var post_url = "/admin/videos/quitar_grupo_maestro/";
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'html',
                                data: 'grupo_detalle_id=' + grupo_detalle_id+'&parent_maestro='+parent_maestro,
                                success: function(respuesta)
                                {
                                    //location.reload();
                                    $("#divContenido").html(respuesta);
                                } //end success
                            }); //end AJAX              
                        }
                        
                        function agregarVideoAMaestro(video_id, maestro_id){
                            var post_url = "/admin/videos/agregarVideoAMaestro/";
                            $.ajax({
                                type: "POST",
                                url: post_url,
                                dataType: 'html',
                                data: 'video_id=' + video_id + '&maestro_id=' + maestro_id,
                                success: function(respuesta)
                                {
                                    $("#divContenido").html(respuesta);
                                    $("#div_" + video_id).empty();
                                    var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                                    $("#div_" + video_id).html(htmlAgregado);
                                    
                                } //end success
                            }); //end AJAX                          
                        }
</script>