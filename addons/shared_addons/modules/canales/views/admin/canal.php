<section class="title"> 
    <div style ="float: left;">
        <?php
        if ($objCanal->id > 0):
            echo anchor('admin/videos/carga_unitaria/' . $objCanal->id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
            echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
            /*    echo anchor('admin/videos/carga_masiva/' . $canal_id, 'Carga masiva', array('class' => ''));
              echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
            echo anchor('admin/videos/organizar/' . $objCanal->id, 'Organizar videos', array('class' => ''));
            echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
            echo anchor('admin/canales/portada/' . $objCanal->id, 'Portadas', array('class' => ''));
        endif;
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $objCanal->id, 'Papelera', array('class' => '')); ?>
    </div>     
</section>
<section class="item">

    <?php
    $hidden = array('canal_id' => $objCanal->id);
    $attributes = array('class' => 'frm', 'id' => 'frmCanal', 'name' => 'frmCanal');
    echo form_open_multipart('admin/canales/canal/' . $objCanal->id, $attributes, $hidden);
    ?>
    <div class="left_arm">

        <!-- titulo -->
        <label for="txtNombre"><?php echo lang('canales:nombre_label'); ?> <span class="required">*</span></label>
        <?php
        $titulo = array(
            'name' => 'nombre',
            'id' => 'nombre',
            'value' => $objCanal->nombre,
            'maxlength' => '100',
            'style' => 'width:556px;',
            'onkeypress' => 'return textonly(event)'
        );
        echo form_input($titulo);
        ?>
        <!-- fuente -->
        <br/>
        <label for="Tipo"><?php echo lang('canales:tipo_canales'); ?><span class="required">*</span></label>
        <?php
        echo form_dropdown('tipo_canal', $tipo_canales, $objCanal->tipo_canales_id);
        ?>
        <br /><br />
        <?php if ($objCanal->id == 0) { ?>
            <!-- imagen -->
            <label for="imagen"><?php echo lang('videos:avatar'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addImagen', 'id' => 'addImagen', 'type' => 'button', 'value' => 'Agrega una imagen a tu portada');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderAjax" id="loaderAjax" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <br /><br /><br />
            <div id="previewImagen" style="width: 570px; height: 440px; border:1px dotted #660033; text-align: center; vertical-align: middle;"><span style="font-size: 50px; color:#f1f1f1;"><?php echo lang('canales:not_found_image'); ?></span></div>
            <?php
        } else {
            ?>
            <!-- imagen -->
            <label for="imagen"><?php echo lang('videos:avatar'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addImagen', 'id' => 'addImagen', 'type' => 'button', 'value' => 'Agrega una imagen a tu portada');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderAjax" id="loaderAjax" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <div id="contenedorImage" style="clear: both;">
                <?php if (count($objCanal->imagen_portada) > 0) { ?>
                    <select id="listaImagenes"></select>
                <?php } ?>
            </div>
            <?php
        }
        ?>        

    </div>
<!--    <span onclick="loading('#loading');">loading</span>-->
    <div class="right_arm">
        <?php if ($objCanal->id == 0) { ?>
            <!-- imagen -->
            <label for="logotipo"><?php echo lang('canales:logo'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addLogo', 'id' => 'addLogo', 'type' => 'button', 'value' => 'Agrega un logo a tu canal');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderLogo" id="loaderLogo" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <br /><br /><br />
            <div id="previewLogo" style="width: 260px; height: 140px; border:1px dotted #660033; text-align: center; vertical-align: middle;"><span style="font-size: 40px; color:#f1f1f1;"><?php echo lang('canales:not_found_logo'); ?></span></div>
            <?php
        } else {
            ?>
            <!-- imagen -->
            <label for="logotipo"><?php echo lang('canales:logo'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addLogo', 'id' => 'addLogo', 'type' => 'button', 'value' => 'Agrega un logo a tu canal');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderLogo" id="loaderLogo" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <br /><br /><br />
            <div id="previewLogo" style="width: 260px; height: 140px; border:1px dotted #660033; text-align: center; vertical-align: middle;"><span style="font-size: 40px; color:#f1f1f1;">
                    <?php
                    if (strlen(trim($objCanal->imagen_logotipo)) > 0) {
                        echo '<img src="' . $objCanal->imagen_logotipo . '" />';
                    }
                    ?>             
            </div>            
            <?php
        }
        ?>
        <br /><br /><br />
        <?php if ($objCanal->id == 0) { ?>
            <!-- imagen -->
            <label for="logotipo"><?php echo lang('canales:isotipo'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addIsotipo', 'id' => 'addIsotipo', 'type' => 'button', 'value' => 'Agrega un logo a tu canal');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderIsotipo" id="loaderIsotipo" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <br /><br /><br />
            <div id="previewIsotipo" style="width: 70px; height: 70px; border:1px dotted #660033; text-align: center; vertical-align: middle;">
            </div>
            <?php
        } else {
            ?>
            <!-- imagen -->
            <label for="logotipo"><?php echo lang('canales:isotipo'); ?><span class="required">*</span></label>
            <?php
            $imagen = array('name' => 'addIsotipo', 'id' => 'addIsotipo', 'type' => 'button', 'value' => 'Agrega un logo a tu canal');
            echo '<div style="float:left;">' . form_input($imagen) . '</div>';
            ?>
            <div  class="loaderIsotipo" id="loaderIsotipo" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <br /><br /><br />
            <div id="previewIsotipo" style="width: 70px; height: 70px; border:1px dotted #660033; text-align: center; vertical-align: middle;">
                <?php
                if (strlen(trim($objCanal->imagen_isotipo)) > 0) {
                    echo '<img src="' . $objCanal->imagen_isotipo . '" />';
                }
                ?>
            </div>            
            <?php
        }
        ?>         
        <br /><br /><br />
        <!-- descripcion -->
        <label for="descripcion"><?php echo lang('canales:descripcion_label'); ?><span class="required">*</span></label>
        <?php echo form_textarea(array('id' => 'descripcion', 'name' => 'descripcion', 'value' => $objCanal->descripcion, 'rows' => 5, 'class' => 'wysiwyg-simple')); ?>        
        <br /><br />
    </div>
    <div class="main_opt">
        <?php
        //if ($this->session->userdata['group'] == 'admin' && $objCanal->id > 0):
        if ($this->session->userdata['group'] == 'admin'):
            ?>
            <div style="float: left;">
                <label for="apikey"><?php echo lang('canales:apikey'); ?> <span class="required">*</span></label>
                <?php
                $apikey = array(
                    'name' => 'apikey',
                    'id' => 'apikey',
                    'value' => $objCanal->apikey,
                    'maxlength' => '100',
                    'style' => 'width:580px;'
                        //'readonly'=>'readonly'
                );
                echo form_input($apikey);
                ?>
            </div>
            <div style="float: left; padding-left: 30px;">
                <label for="playerkey"><?php echo lang('canales:playerkey'); ?> <span class="required">*</span></label>
                <?php
                $playerkey = array(
                    'name' => 'playerkey',
                    'id' => 'playerkey',
                    'value' => $objCanal->playerkey,
                    'maxlength' => '100',
                    'style' => 'width:580px;'
                        //'readonly'=>'readonly'
                );
                echo form_input($playerkey);
                ?>
                <input type="hidden" id="apikey_original" name="apikey_original" value="<?php echo $objCanal->apikey; ?>" />
                <input type="hidden" id="playerkey_original" name="playerkey_original" value="<?php echo $objCanal->playerkey; ?>" />                
            </div>
            <?php
        else:
            ?>
            <input type="hidden" id="apikey" name="apikey" value="<?php echo $objCanal->apikey; ?>" />
            <input type="hidden" id="playerkey" name="playerkey" value="<?php echo $objCanal->playerkey; ?>" />
        <?php
        endif;
        ?>
        <br /><br /><br /><br /><br />
        <a href="javascript:guardar();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>
        <br /><br /><br />
    </div>
    <script type="text/javascript">
        function mostrar_titulo() {
            var vista = 'canal';
            var post_url = "/admin/canales/mostrar_titulo/<?php echo $objCanal->id; ?>/" + vista;
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: 'html',
                //data:imagen_id,
                success: function(respuesta) //we're calling the response json array 'cities'
                {
                    $(".subbar > .wrapper").html(respuesta);
                } //end success
            }); //end AJAX              
        }
        function guardar() {
            var values = {};
            $.each($('#frmCanal').serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });
            var canal_id = $("#canal_id").val();
            if (canal_id > 0) {
                var apikey = $("#apikey").val();
                var playerkey = $("#playerkey").val();
                var apikey_original = $("#apikey_original").val();
                var playerkey_original = $("#playerkey_original").val();
            } else {
                var apikey = $("#apikey").val();
                var playerkey = $("#playerkey").val();
                var apikey_original = $("#apikey_original").val();
                var playerkey_original = $("#playerkey_original").val();
            }
            //vemos si x lo menos uno de los keys fue modificado
            var api_modificado = 0;
            if (canal_id > 0) {
                if (apikey != apikey_original && playerkey != playerkey_original) {
                    api_modificado = 1; // ambos fueron modificados
                } else {
                    if (apikey == apikey_original && playerkey != playerkey_original) {
                        api_modificado = 2; //solo se modificó el playerkey
                    } else {
                        if (apikey != apikey_original && playerkey == playerkey_original) {
                            api_modificado = 3; // solo se modificó el apikey
                        }
                    }
                }
            }
            if (canal_id > 0) {
                var mensaje = 'Seguro que deseas guardar los cambios del canal?'
                if (api_modificado > 0) {
                    mensaje = 'Seguro deseas guardar los cambios en los APIKEY';
                }
            } else {
                var mensaje = 'Seguro que deseas registrar nuevo canal?'
            }

            var editorText = CKEDITOR.instances.descripcion.getData();
            $('<input>').attr({
                type: 'hidden',
                id: 'descripcion_updated',
                name: 'descripcion_updated',
                value: editorText
            }).appendTo('#frmCanal');
            //validamos el nombre del canal
            var titulo = $.trim($("#nombre").val());
            //if(api_modificado > 0){
            jConfirm(mensaje, "Editar canal", function(r) {
                if (r) {
                    // }
                    if (titulo.length > 0) {
                        //validamos el ckeditor
                        var editorText = CKEDITOR.instances.descripcion.getData();
                        editorText = $.trim(editorText);
                        var regex = /(<([^>]+)>)/ig;
                        var editorText2 = editorText.replace(regex, "");
                        editorText2 = $.trim(editorText2);
                        editorText2 = editorText2.replace(/(&nbsp;)*/g, "");
                        if (editorText.length > 0 && editorText2.length > 0) {
                            //validamos si selecciono una imagen de portada
                            if (canal_id > 0) {
                                var imagen_portada = '--';
                            } else {
                                var imagen_portada = $.trim($("#imagen_portada").val());
                            }
                            if (imagen_portada.length > 0) {
                                //validamo si selecciono un logotipo
                                var imagen_logotipo = $.trim($("#imagen_logotipo").val());
                                if (imagen_logotipo.length > 0) {
                                    //validamos si selecciono una imagen iso
                                    var imagen_isotipo = $.trim($("#imagen_isotipo").val());
                                    if (imagen_isotipo.length > 0) {
                                        //vaidamos el apikey
                                        if (apikey.length > 0) {
                                            if (values['tipo_canal'] > 0) {
                                                //validamos el playerkey
                                                if (playerkey.length > 0) {
                                                    //$('#loader').show();
                                                    loading('#loading');
                                                    var serializedData = $('#frmCanal').serialize();
                                                    //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                                                    var post_url = "/admin/canales/registrar_canal/" + values['canal_id'];
                                                    $.ajax({
                                                        type: "POST",
                                                        url: post_url,
                                                        dataType: 'json',
                                                        data: serializedData,
                                                        success: function(respuesta) //we're calling the response json array 'cities'
                                                        {
                                                            //$('#loader').hide();
                                                            if (respuesta.value == '1') {
                                                                showMessage('error', '<?php echo lang('canales:not_found_imagen_in_server') ?>', 2000, '');//no se encontro la imagen portada en el servidor
                                                            } else {
                                                                if (respuesta.value == '2') {
                                                                    showMessage('error', '<?php echo lang('canales:not_found_logo_in_server') ?>', 2000, '');//no se encontro el logotipo en el servidor
                                                                } else {
                                                                    if (respuesta.value == '3') {
                                                                        showMessage('error', '<?php echo lang('canales:not_found_iso_in_server') ?>', 2000, '');//no se encontro el logotipo en el servidor
                                                                    } else {
                                                                        if (respuesta.value == '4') {
                                                                            showMessage('error', '<?php echo lang('canales:exist_canal') ?>', 2000, '');//no se encontro el logotipo en el servidor 
                                                                        } else {
                                                                            var url = "admin/canales";
                                                                            //showMessage('exit', '<?php //echo lang('canales:success_saved')      ?>', 2000, '');//no se encontro el logotipo en el servidor 
                                                                            $(location).attr('href', '<?php echo BASE_URL; ?>' + url);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        } //end success
                                                    }); //end AJAX                                        
                                                } else {
                                                    showMessage('error', '<?php echo lang('canales:require_playerkey') ?>', 2000, '');
                                                }
                                            } else {
                                                showMessage('error', '<?php echo lang('canales:require_tipo_canal') ?>', 2000, '');
                                            }
                                        } else {
                                            showMessage('error', '<?php echo lang('canales:require_apikey') ?>', 2000, '');
                                        }
                                    } else {
                                        showMessage('error', '<?php echo lang('canales:require_iso') ?>', 2000, '');
                                    }
                                } else {
                                    showMessage('error', '<?php echo lang('canales:require_logo') ?>', 2000, '');
                                }
                            } else {
                                showMessage('error', '<?php echo lang('canales:require_image_portada') ?>', 2000, '');
                            }
                        } else {
                            showMessage('error', '<?php echo lang('canales:require_description') ?>', 2000, '');
                        }
                    } else {
                        showMessage('error', '<?php echo lang('videos:require_title') ?>', 2000, '');
                    }
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

        function loading(id) {
            var winH = $(window).height();
            var winW = $(window).width();

            var dialog = $(id);
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

        function textonly(e) {
            var code;
            if (!e)
                var e = window.event;
            if (e.keyCode)
                code = e.keyCode;
            else if (e.which)
                code = e.which;
            var character = String.fromCharCode(code);
            if (code == 32 || code == 8) {
                return true;
            } else {
                var AllowRegex = /^[0-9A-Za-z]+$/;
                if (AllowRegex.test(character))
                    return true;
            }
            return false;
        }

        function saveImages(respuesta) {
            var values = {};
            $.each($('#frmCanal').serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });
            var serializedData = $('#frmCanal').serialize();
            var post_url = "/admin/canales/registrar_portada/" + values['canal_id'];
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
                        htmlN += '<option value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
                    });
                    htmlN += '</select>';
                    $("#contenedorImage").html(htmlN);
                    $('#listaImagenes').ddslick({
                        width: 300,
                        imagePosition: "center",
                        selectText: "Seleccione su imagen principal",
                        onSelected: function(data) {
                            activeImageVideo(data['selectedData'].value);
                        }
                    });

                } //end success
            }); //end AJAX        
        }

        function activeImageVideo(imagen_id) {
            var canal_id = $("#canal_id").val();
            var post_url = "/admin/canales/active_portada/" + imagen_id + "/" + canal_id;
            $.ajax({
                type: "POST",
                url: post_url,
                //dataType: 'json',
                //data:imagen_id,
                success: function(returnRespuesta) //we're calling the response json array 'cities'
                {
                    //limpiar
//                    $('#listaImagenes').ddslick('destroy');
//                    $("#contenedorImage").empty();
//                    var htmlN = '<select id="listaImagenes">';
//                    $.each(returnRespuesta.imagenes, function(k, v) {
//                        if (v.estado == '1') {
//                            htmlN += '<option selected=\"selected\" value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
//                        } else {
//                            htmlN += '<option value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
//                        }
//                    });
//                    htmlN += '</select>';
//                    $("#contenedorImage").html(htmlN);
//                    $('#listaImagenes').ddslick({
//                        width: 300,
//                        imagePosition: "center",
//                        selectText: "Seleccione su imagen principal",
//                        onSelected: function(data) {
//                            //console.log(data);
//                            activeImageMaestro(data['selectedData'].value);
//                        }
//                    });
                } //end success
            }); //end AJAX              
        }

        $(document).ready(function() {
<?php if ($objCanal->id > 0): ?>
                mostrar_titulo();
<?php endif; ?>
            //upload de la imagen de portada
            var btn_firma = $('#addImagen'), interval;
            new AjaxUpload('#addImagen', {
                action: 'admin/canales/subir_imagen/portada',
                onSubmit: function(file, ext) {
                    if (!(ext && /^(jpg|png)$/.test(ext))) {
                        // extensiones permitidas
                        //alert('Solo se permiten Imagenes .jpg o .png');
                        showMessage('error', 'Solo se permiten Imagenes .jpg o .png', 2000, '');
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
                    respuestas = $.parseJSON(response);
                    if (respuestas.respuesta == 'done') {
                        //saveImages(respuesta);
                        if ($("#canal_id").val() == 0) {
                            $('#loaderAjax').hide();
                            $("#imagen_portada").val(respuestas.image);
                            var htmlImg = '<img src="<?php echo $this->config->item('url:temp') ?>' + respuestas.image + '" title="' + respuestas.image + '" style="width:570px;" />';
                            $("#previewImagen").html(htmlImg);
                        } else {
                            saveImages(respuestas);
                        }
                    }
                    else {
                        showMessage('error', respuestas.mensaje, 2000, '');
                        $('#loaderAjax').hide();
                    }
                    this.enable();
                }
            });
            //upload del logo
            var btn_firma = $('#addLogo'), interval;
            new AjaxUpload('#addLogo', {
                action: 'admin/canales/subir_imagen/logo',
                onSubmit: function(file, ext) {
                    if (!(ext && /^(jpg|png)$/.test(ext))) {
                        // extensiones permitidas
                        //alert('Solo se permiten Imagenes .jpg o .png');
                        showMessage('error', 'Solo se permiten Imagenes .jpg o .png', 2000, '');
                        // cancela upload
                        return false;
                    } else {
                        $('#loaderLogo').show();
                        btn_firma.text('Espere por favor');
                        this.disable();
                    }
                },
                onComplete: function(file, response) {
                    btn_firma.text('Cambiar Imagen');
                    respuesta = $.parseJSON(response);
                    if (respuesta.respuesta == 'done') {

                        $("#imagen_logotipo").val(respuesta.image);
                        var htmlImg = '<img src="uploads/temp/' + respuesta.image + '" title="' + respuesta.image + '" style="width:260px;" />';
                        $("#previewLogo").html(htmlImg);
                        if ($("#canal_id").val() > 0) {
                            $("#update_logotipo").val("1");
                        }
                        $('#loaderLogo').hide();
                    }
                    else {
                        showMessage('error', respuesta.mensaje, 2000, '');
                        $('#loaderLogo').hide();
                    }
                    this.enable();
                }
            });
            //upload iso
            var btn_firma = $('#addIsotipo'), interval;
            new AjaxUpload('#addIsotipo', {
                action: 'admin/canales/subir_imagen/iso',
                onSubmit: function(file, ext) {
                    if (!(ext && /^(jpg|png)$/.test(ext))) {
                        // extensiones permitidas
                        //alert('Solo se permiten Imagenes .jpg o .png');
                        showMessage('error', 'Solo se permiten Imagenes .jpg o .png', 2000, '');
                        // cancela upload
                        return false;
                    } else {
                        $('#loaderIsotipo').show();
                        btn_firma.text('Espere por favor');
                        this.disable();
                    }
                },
                onComplete: function(file, response) {
                    btn_firma.text('Cambiar Imagen');
                    respuesta = $.parseJSON(response);
                    if (respuesta.respuesta == 'done') {
                        //saveImages(respuesta);
                        $("#imagen_isotipo").val(respuesta.image);
                        var htmlImg = '<img src="uploads/temp/' + respuesta.image + '" title="' + respuesta.image + '" style="width:70px;" />';
                        $("#previewIsotipo").html(htmlImg);
                        if ($("#canal_id").val() > 0) {
                            $("#update_isotipo").val("1");
                        }
                        $('#loaderIsotipo').hide();
                    }
                    else {
                        showMessage('error', respuesta.mensaje, 2000, '');
                        $('#loaderIsotipo').hide();
                    }
                    this.enable();
                }
            });

            //funcion para la lista de imagenes de portada
<?php if ($objCanal->id > 0) { ?>
                //Dropdown plugin data
                var ddData = <?php echo json_encode($objCanal->imagen_portada) . ';'; ?>

                $('#listaImagenes').ddslick({
                    data: ddData,
                    width: 300,
                    imagePosition: "center",
                    selectText: "Seleccione su imagen principal",
                    onSelected: function(data) {
                        activeImageVideo(data['selectedData'].value);
                    }
                });
                //var ind = $("indiceImage").val();
                //$('#listaImagenes').ddslick('select', {index: ind });
<?php } ?>
        });
    </script>
    <input type="hidden" name="imagen_portada" id="imagen_portada" value="<?php //echo $objCanal->imgen_portada;      ?>" />
    <input type="hidden" name="imagen_logotipo" id="imagen_logotipo" value="<?php echo $objCanal->imagen_logotipo; ?>" />
    <input type="hidden" name="update_logotipo" id="update_logotipo" value="0" />
    <input type="hidden" name="imagen_isotipo" id="imagen_isotipo" value="<?php echo $objCanal->imagen_isotipo; ?>" />
    <input type="hidden" name="update_isotipo" id="update_isotipo" value="0" />
    <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $objCanal->id; ?>" />
    <?php echo form_close() ?>
</section>