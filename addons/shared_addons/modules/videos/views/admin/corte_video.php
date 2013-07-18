<section class="title">
    <div>
        <ul class="main_menu">
        <?php
        if ($canal->tipo_canales_id != $this->config->item('canal:mi_canal')) {
        ?>
            <li>
        <?php echo anchor('admin/videos/carga_unitaria/' . $canal->id, $this->config->item('submenu:carga_unitaria'), array('class' => '')); ?>
            </li>
            <li>
        <?php echo anchor('admin/videos/carga_youtube/' . $canal->id, $this->config->item('submenu:carga_youtube'), array('class' => '')); ?>
            </li>
        <?php
        }
        ?>
            <li>
        <?php echo anchor('admin/videos/organizar/' . $canal->id, 'Organizar videos', array('class' => '')); ?>
            </li>
            <li>
        <?php echo anchor('admin/canales/portada/' . $canal->id, 'Portadas', array('class' => '')); ?>
            </li>
            <li>
        <?php echo anchor('/admin/videos/grupo_maestro/' . $canal->id, 'Crear programas', array('class' => '')); ?>
            </li>
            <li class="active">
        <?php echo anchor('#', 'Corte video', array('class' => '')); ?>
            </li>
            <li class="alast"></li>
            <li class="last">
        <?php echo anchor('admin/canales/papelera/' . $canal->id, 'Papelera', array('class' => '')); ?>
            </li>
        </ul>
</section>

<section class="item">
    <?php
    //get unique id
    $up_id = uniqid();
    ?>
    <?php if ($objBeanForm->error) { ?>
        <div> <?php echo $objBeanForm->message; ?></div>
    <?php } ?>

    <!--FORM CORTE VIDEO        -->
    <?php
    // Canales_id       
    $hidden = array('canal_id' => $canal->id);

    $attributes = array('class' => 'frm', 'id' => 'frm', 'name' => 'frm');
    echo form_open_multipart('admin/videos/insertCorteVideo/' . $canal->id, $attributes, $hidden);
    //echo form_open_multipart('file.php', $attributes, $hidden);
    ?>
    <!-- Editor de videos -->
    <span style="display:block; width: 100%;">           
        <div id="media-1" class="mediasplitter" data-src="<?php echo trim($objBeanForm->ruta) ?>">
            <p><button class="mediaspliter-edit">Cortar video</button></p>
            <div class="mediasplitter-wrapp">
                <div class="mediasplitter-player"></div>
                <div class="mediasplitter-controls">
                    <div class="noUiSlider mediasplitter-slider"></div>
                    <div class="mediasplitter-progress"><div class="mediasplitter-progress-indicator"></div></div>
                    <p>
                        <span class="opaque">Comienza:</span> <input name="ini_corte" id="ini_corte" class="mediasplitter-time-start" autocomplete="off" value="" type="text" size="5" readonly /> 
                        <span class="opaque">Termina:</span> <input name="fin_corte" id="fin_corte" class="mediasplitter-time-end" autocomplete="off" value="" size="5" type="
                                                                    text" readonly /> 
                        <span class="opaque">Duración</span> <input name="dur_corte" id="dur_corte" class="mediasplitter-time-dur" autocomplete="off" value="" size="5" type="
                                                                    text" readonly />  <button class="mediasplitter-play" onClick="return false;">Play</button>
                        <span class="mediasplitter-time"><span class="mediasplitter-time-remaming">0.00</span> <span class="opaque">seg.</span> </span>
                    </p>
                </div>
            </div>
            <input name="dur_total" id="dur_total"  value="" type="hidden" readonly />
        </div>        

    </span>

    <!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
    <!---->
    <div class="left_arm">

        <!-- titulo -->
        <label for="titulo"><?php echo lang('videos:title'); ?> <span class="required">*</span></label>
        <?php
        $titulo = array(
            'name' => 'titulo',
            'id' => 'titulo',
            'value' => '',
            'maxlength' => '100',
            'style' => 'width:556px;'
        );
        echo form_input($titulo);
        if ($objBeanForm->video_id == 0) { ?>
            <!-- video -->
            <label for="video"><?php echo lang('videos:video'); ?><span class="required">*</span></label>
<?php
            $video = array('name' => 'video', 'id' => 'video');
            echo form_upload($video);
        }
        if ($objBeanForm->video_id > 0) {
            $imagen = array('name' => 'addImage', 'id' => 'addImage', 'type' => 'button', 'value' => '');
            echo '<div style="float:left;display:none">' . form_input($imagen) . '</div>';
?>
            <div  class="loaderAjax" id="loaderAjax" style="display: none; float: left;">
                <img src="uploads/imagenes/loading.gif">
            </div>
            <div style="clear: both;"></div>
            <div id="contenedorImage"></div>
<?php
        }
?>
        <br />
        <!-- fecha de transmisión -->
        <label for="fec_trans"><?php echo lang('videos:fecha_transmision_label'); ?></label>
        <div style="float:left;">
            <?php
            $fec_trans = array(
                'name' => 'fec_trans',
                'id' => 'fec_trans',
                'value' => $objBeanForm->fec_trans,
                'class' => 'selectedDate'
            );
            echo form_input($fec_trans);
            ?>
        </div>
        <div style="float:left;">
            <!-- horario de tranmisión -->
            <!--<label for="horario_transmision"><?php echo lang('videos:horaio_transmision'); ?></label>-->
            <?php echo lang('videos:inicio'); ?>
            <?php
            $hora_trans_ini = array(
                'name' => 'hora_trans_ini',
                'id' => 'hora_trans_ini',
                'value' => $objBeanForm->hora_trans_ini,
                'class' => 'selectedHour',
                'style' => 'width:140px;'
            );
            echo form_input($hora_trans_ini);
            ?>
        </div>
        <div style="float:right;">
            <?php echo lang('videos:fin'); ?>
            <?php
            $hora_trans_fin = array(
                'name' => 'hora_trans_fin',
                'id' => 'hora_trans_fin',
                'value' => $objBeanForm->hora_trans_fin,
                'class' => 'selectedHour',
                'style' => 'width:140px;'
            );
            echo form_input($hora_trans_fin);
            ?>
        </div>
        <br /><br /><br />
        <!-- descripcion -->
        <label for="descripcion"><?php echo lang('videos:description'); ?><span class="required">*</span></label>
        <?php echo form_textarea(array('id' => 'descripcion', 'name' => 'descripcion', 'value' => $objBeanForm->descripcion, 'rows' => 5, 'class' => 'wysiwyg-simple')); ?>

        <!-- tags tematicos -->
        <br/></br>
        <label for="tematicas"><?php echo lang('videos:etiquetas_tematicas_label'); ?> <span class="required">*</span></label>
        <div class="input"><?php echo form_input('tematicas', $objBeanForm->tematicas, 'id="tematicas"') ?></div>
        <?php
        /* $tematicas = array(
          'name' => 'tematicas',
          'id' => 'tematicas',
          'value' => set_value('tematicas'),
          'maxlength' => '250',
          'style'=>'width:556px;',
          );
          echo form_input($tematicas); */
        ?>
        <?php
        /* $personajes = array(
          'name' => 'personajes',
          'id' => 'personajes',
          'value' => set_value('personajes'),
          'maxlength' => '250',
          'style'=>'width:556px;',
          'class' => 'wysiwyg-simple'
          );
          echo form_input($personajes); */
        ?>

        <!-- tipo -->
        <!--        <br/></br>
                <label for="tipo"><?php //echo lang('videos:tipo_label');   ?></label>
        <?php //echo form_error('tipo'); ?><br/>
        <?php //echo form_dropdown('tipo', $tipo, $objBeanForm->tipo); ?> -->
        <input type="hidden" name="tipo" id="tipo" value="<?php echo $objBeanForm->tipo; ?>" >
    </div>

    <div class="right_arm">

        <!-- categoria -->

        <label for="categoria"><?php echo lang('videos:categoria_label'); ?></label>
        <?php echo form_error('categoria'); ?><br />
        <?php echo form_dropdown('categoria', $categoria, $objBeanForm->categoria); ?>
        <br/><br/>
        <!-- programa -->
        <label for="programa"><?php echo lang('videos:programa_label'); ?></label>
        <?php echo form_error('programa'); ?><br/>
        <?php
        $js = 'onChange="generate_collection();"';
        echo form_dropdown('programa', $programa, $objBeanForm->programa, $js);
        //echo form_dropdown('programa', $programa, $objBeanForm->programa); 
        ?>

        <!-- boton añadir -->
        <div class="i_plus">
            <?php
            echo form_input(array('class' => 'h_text', 'name' => 'txt_programa', 'id' => 'txt_programa'));
            $attr = array('class' => 'plus_item btn blue', 'type' => 'button', 'onclick' => 'addMaestro(\'programa\')');
            echo anchor('#', '+ ' . lang('videos:add'), $attr)
            ?>                
        </div>            
        <!-- coleccion -->
        <br/><br/>
        <label for="coleccion"><?php echo lang('videos:collection'); ?></label>
        <?php echo form_error('coleccion'); ?><br/>
        <?php
        $js = 'onChange="generate_list();" id="coleccion"';
        echo form_dropdown('coleccion', $coleccion, $objBeanForm->coleccion, $js);
        ?>

        <!-- boton añadir -->
        <div class="i_plus">
            <?php
            echo form_input(array('class' => 'h_text', 'name' => 'txt_coleccion', 'id' => 'txt_coleccion'));
            $attr = array('class' => 'plus_item btn blue', 'type' => 'button', 'onclick' => 'addMaestro(\'coleccion\')');
            echo anchor('#', '+ ' . lang('videos:add'), $attr)
            ?>                
        </div>

        <!-- lista de reproducción -->
        <br/>
        <label for="lista_rep"><?php echo lang('videos:lista_reprod_label'); ?></label>
        <?php echo form_error('lista_rep'); ?><br/>
        <?php
        echo form_dropdown('lista', $lista_rep, $objBeanForm->lista);
        ?>

        <!-- botón añadir -->
        <div class="i_plus">
            <?php
            echo form_input(array('class' => 'h_text', 'name' => 'txt_lista', 'id' => 'txt_lista'));
            $attr = array('class' => 'plus_item btn blue', 'type' => 'button', 'onclick' => 'addMaestro(\'lista\')');
            echo anchor('#', '+ ' . lang('videos:add'), $attr)
            ?>
        </div>

        <!-- fuente -->
        <br/>
        <div style="display: none;">
            <label for="fuente"><?php echo lang('videos:fuente_label'); ?><span class="required">*</span></label>
            <?php
            echo form_dropdown('fuente', $fuente, $objBeanForm->canal_id);
            ?>
        </div>
        <!-- fecha de publicación -->
        <br/><br/>
<!--        <label for="fecha_publicacion"><?php //echo lang('videos:fecha_publicacion_label');  ?></label>-->
        <?php //echo lang('videos:inicio'); ?>
        <?php
        $fec_pub_ini = array(
            'type' => 'hidden',
            'name' => 'fec_pub_ini',
            'id' => 'fec_pub_ini',
            'value' => $objBeanForm->fec_pub_ini,
            'class' => 'selectedDateTime'
        );
        echo form_input($fec_pub_ini);
        ?>

        <?php ///echo lang('videos:fin'); ?>
        <?php
        $fec_pub_fin = array(
            'type' => 'hidden',
            'name' => 'fec_pub_fin',
            'id' => 'fec_pub_fin',
            'value' => $objBeanForm->fec_pub_fin,
            'class' => 'selectedDateTime'
        );
        echo form_input($fec_pub_fin);
        ?>

        <!-- ubicacion -->
<!--        <label><?php //echo lang('videos:ubicacion_label');  ?></label>-->
        <!--<div id="map_canvas" style="width:100%;height:400px;border:solid black 1px;"></div>
        <input type="text" value="37.7699298, -122.4469157" name="txt_latlng" id="txt_latlng" size="89%" disabled="disabled">-->
        <?php
        $ubicacion = array(
            'type' => 'hidden',
            'name' => 'ubicacion',
            'id' => 'ubicacion',
            'value' => $objBeanForm->ubicacion,
            'style' => 'width:556px;',
        );
        echo form_input($ubicacion);
        ?>
        <!-- tags personajes -->
        <br/></br>
        <label for="personajes"><?php echo lang('videos:etiquetas_personajes_label'); ?><span class="required">*</span></label>
        <div class="input"><?php echo form_input('personajes', $objBeanForm->personajes, 'id="personajes"') ?></div>        
    </div>

    <div class="main_opt">            
        <!--<input type="button" onclik="saveVideo()" value="Guardar" name="btnGuardar" />-->
        <!--<a href="javascript:document.frm.submit();" class="btn orange" type="button">Guardar</a>-->
        <a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>
        &nbsp;
        <?php
//        $attr = array('class' => 'btn orange', 'type' => 'button');
//        echo anchor("#", lang('buttons.cancel'), $attr);
        ?>
    </div>
    <script type="text/javascript" >
                var dur_video;

                function activeImageVideo(imagen_id) {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    //var serializedData = $('#frm').serialize();    
                    var post_url = "/admin/videos/active_imagen/" + values['canal_id'] + "/" + values['video_id'] + "/" + imagen_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        //dataType: 'json',
                        //data:imagen_id,
                        success: function(returnRespuesta) //we're calling the response json array 'cities'
                        {
                        } //end success
                    }); //end AJAX              
                }

                function existeFragmento() {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });

                    var serializedData = $('#frm').serialize();
                    //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                    var post_url = "/admin/videos/verificarVideo/" + values['canal_id'] + "/" + values['video_id'];
                    //var r;
                    $.post(post_url, serializedData, function(data) {
                        if (data.errorValue == '0') {
                            //showMessage('exit', 'no hay errores!', 2000,'');
                            $('#frm').submit();
                        } else {
                            showMessage('error', '<?php echo lang('videos:fragment_exist') ?>', 2000, '');
                        }
                    }, "json");
                    //console.log(f.responseText);
                    //console.log($("#existe_fragmento").val());
                    /*var ff = $.ajax({
                     type: "POST",
                     url: post_url,
                     dataType: 'json',
                     data:serializedData,
                     success: function(returnValue) //we're calling the response json array 'cities'
                     {
                     //console.log(returnValue.errorValue);
                     if(returnValue.errorValue == '1'){
                     $("#existe_fragmento").val("1");
                     return true;
                     }else{
                     $("#existe_fragmento").val("0");
                     return false;
                     }
                     //return returnValue.errorValue;
                     //$("#existe_fragmento").delay(2000);
                     } //end success
                     }); */ //end AJAX */

                }

                function sleep(delay) {
                    var start = new Date().getTime();
                    while (new Date().getTime() < start + delay)
                        ;
                }
                /*
                 * 
                 * @returns {undefined}
                 */
                function saveVideo() {

                    if ($("#dur_corte").val() == $("#dur_total").val()) {
                        showMessage('error', 'La duracion del nuevo video debe ser diferente al original', 2000, '');
                    } else {
                        var values = {};
                        $.each($('#frm').serializeArray(), function(i, field) {
                            values[field.name] = field.value;
                        });

                        var editorText = CKEDITOR.instances.descripcion.getData();
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'descripcion_updated',
                            name: 'descripcion_updated',
                            value: editorText
                        }).appendTo('#frm');
                        //validamos el titulo
                        var titulo = $.trim($("#titulo").val());
                        var inputfile = $("#video").val();
                        values['tematicas'] = $.trim(values['tematicas']);
                        values['personajes'] = $.trim(values['personajes']);
                        if (titulo.length > 0) {
                            //validamos el input file
                            if (true) {
                                //verificamos si el formato del archivo es valido
                                if (true) {
                                    //validamos el ckeditor
                                    var editorText = CKEDITOR.instances.descripcion.getData();
                                    editorText = $.trim(editorText);
                                    var regex = /(<([^>]+)>)/ig;
                                    var editorText2 = editorText.replace(regex, "");
                                    editorText2 = $.trim(editorText2);
                                    editorText2 = editorText2.replace(/(&nbsp;)*/g, "");
                                    if (editorText.length > 0 && editorText2.length > 0) {
                                        //validamos que este seleccionada una categoria
                                        if (values['categoria'] > 0) {
                                            //validamos tematicas
                                            if (values['tematicas'].length > 0) {
                                                //validamos personajes
                                                if (values['personajes'].length > 0) {
                                                    //validamos la fuente del video
                                                    if (values['fuente'] > 0) {
                                                        //var repite = $("#existe_fragmento").val();
                                                        //console.log(repite);
                                                        if (true) {
<?php if ($objBeanForm->video_id > 0) { ?>
                                                            var serializedData = $('#frm').serialize();
                                                            //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                                                            var post_url = "/admin/videos/insertCorteVideo/" + values['canal_id'] + "/" + values['video_id'];
                                                            $.ajax({
                                                                type: "POST",
                                                                url: post_url,
                                                                dataType: 'json',
                                                                data: serializedData,
                                                                success: function(returnValue) //we're calling the response json array 'cities'
                                                                {
                                                                    //console.log(returnValue.value);
                                                                    if (returnValue.value == '0') {
                                                                        showMessage('exit', '<?php echo lang('videos:edit_video_success') ?>', 1000, '');
                                                                    } else {
                                                                        showMessage('error', '<?php echo lang('videos:fragment_exist') ?>', 2000, '');
                                                                    }
                                                                } //end success
                                                            }); //end AJAX                                                    
<?php } else { ?>
                                                            //$('#frm').submit();
                                                            existeFragmento();
<?php } ?>
                                                        } else {
                                                            showMessage('error', '<?php echo lang('videos:fragment_exist') ?>', 2000, '');
                                                        }
                                                    } else {
                                                        showMessage('error', '<?php echo lang('videos:require_source') ?>', 2000, '');
                                                    }
                                                } else {
                                                    showMessage('error', '<?php echo lang('videos:require_personajes') ?>', 2000, '');
                                                }
                                            } else {
                                                showMessage('error', '<?php echo lang('videos:require_tematicas') ?>', 2000, '');
                                            }
                                        } else {
                                            showMessage('error', '<?php echo lang('videos:require_category') ?>', 2000, '');
                                        }
                                    } else {
                                        showMessage('error', '<?php echo lang('videos:require_description') ?>', 2000, '');
                                    }
                                    //aquí enviamos el mensaje de validación del formato del archivo
                                } else {
                                    showMessage('error', '<?php echo lang('videos:format_invalid') ?>', 2000, '');
                                }
                            } else {
                                showMessage('error', '<?php echo lang('videos:require_video') ?>', 2000, '');
                            }
                        } else {
                            showMessage('error', '<?php echo lang('videos:require_title') ?>', 2000, '');
                        }
                    }
                }
                /**
                 * funcion para agregar nuevos programas, colecciones, listas
                 * @returns {undefined}
                 */
                function addMaestro(type_video) {
                    if ($('#txt_' + type_video).css('display') == 'inline' || $('#txt_' + type_video).css('display') == 'inline-block') {
                        $("#tipo_maestro").val(type_video);
                        var id_category_selected = $('select[name=categoria]').val();
                        if (id_category_selected > 0) {
                            var values = {};
                            $.each($('#frm').serializeArray(), function(i, field) {
                                values[field.name] = field.value;
                            });
                            values['txt_' + type_video] = $.trim(values['txt_' + type_video]);
                            if (values['txt_' + type_video].length > 0) {
                                var serializedData = $('#frm').serialize();
                                //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                                var post_url = "/admin/videos/save_maestro";
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    //dataType: 'json',
                                    data: serializedData,
                                    success: function(tipo_maestro) //we're calling the response json array 'cities'
                                    {
                                        var resultado = false;
                                        $.each(tipo_maestro, function(id, maestro) {
                                            if (id == 'error' && maestro == '0') {
                                                resultado = true;
                                            }
                                        });
                                        if (resultado) {
                                            showMessage('exit', '<?php echo lang('videos:add_programme') ?>', 1000, '');
                                            $.each(tipo_maestro, function(id, maestro)
                                            {
                                                if (id != 'error') {
                                                    var opt = $('<option />').attr("selected", "selected"); // here we're creating a new select option for each group
                                                    opt.val(id);
                                                    opt.text(maestro);
                                                    $('select[name="' + type_video + '"]').prepend(opt);
                                                }
                                            });
                                            //$("#coleccion option[id=" + myText +"]").attr("selected","selected") ;
                                            $('select[name="' + type_video + '"]').trigger("liszt:updated");
                                            $('#txt_' + type_video).val('');
                                            $('#txt_' + type_video).css('display', 'none');

                                            if (type_video == 'programa') {
                                                generate_collection();
                                            } else {
                                                if (type_video == 'coleccion') {
                                                    generate_list();
                                                }
                                            }
                                        } else {
                                            showMessage('error', '<?php echo lang('videos:exist_name') ?>', 2000, '');
                                        }

                                    } //end success
                                }); //end AJAX
                            } else {
                                showMessage('error', '<?php echo lang('videos:missing_programme') ?>', 2000, '');
                            }
                        } else {
                            showMessage('error', '<?php echo lang('videos:missing_category') ?>', 2000, '');
                        }
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

                $(document).ready(function() {
<?php if ($objBeanForm->video_id > 0) { ?>
                        //Dropdown plugin data
                        var ddData = <?php echo json_encode($objBeanForm->avatar) . ';'; ?>

                        $('#listaImagenes').ddslick({
                            data: ddData,
                            width: 300,
                            imagePosition: "center",
                            selectText: "Seleccione su imagen principal",
                            onSelected: function(data) {
                                //console.log(data['selectedData'].value);
                                activeImageVideo(data['selectedData'].value);
                            }
                        });
                        //var ind = $("indiceImage").val();
                        //$('#listaImagenes').ddslick('select', {index: ind });
<?php } ?>
                });
                //SETTING CONFIG SPANISH
                jQuery(function($) {
                    // generate a slug when the user types a title in
                    //pyro.generate_slug('#blog-content-tab input[name="title"]', 'input[name="slug"]');

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

                    //show the progress bar only if a file field was clicked
                    var show_bar = 0;
                    $('input[type="file"]').click(function() {
                        show_bar = 1;
                    });
                    //show iframe on form submit
                    $("#frm").submit(function() {
                        if (show_bar === 1) {
                            function set() {
                                $('#upload_frame').attr('src', 'upload_frame.php?up_id=<?php echo $up_id; ?>');
                            }
                            setTimeout(set);
                        }
                    });

                    $.datepicker.regional['es'] = {
                        closeText: 'Cerrar',
                        prevText: '&#x3c;Ant',
                        nextText: 'Sig&#x3e;',
                        currentText: 'Hoy',
                        timeText: 'Hora',
                        hourText: 'Hrs.',
                        minuteText: 'Min.',
                        secondText: 'Seg.',
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
                        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
                        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
                        weekHeader: 'Sm',
                        dateFormat: 'dd-mm-yy',
                        firstDay: 1,
                        isRTL: false,
                        showMonthAfterYear: false,
                        yearSuffix: ''};
                    $.datepicker.setDefaults($.datepicker.regional['es']);


                    $.timepicker.regional['es'] = {
                        closeText: 'Cerrar',
                        prevText: '&#x3c;Ant',
                        nextText: 'Sig&#x3e;',
                        timeOnlyTitle: 'Elige la hora',
                        currentText: 'Hoy',
                        timeText: 'Hora',
                        hourText: 'Hrs.',
                        minuteText: 'Min.',
                        secondText: 'Seg.'};
                    $.timepicker.setDefaults($.timepicker.regional['es']);

                    $('.selectedDateTime').datetimepicker($.datepicker.regional['es']);
                    $('.selectedDate').datepicker({
                        onSelect: function(textoFecha, objDatepicker) {
                            //$("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
                            var fragmento_id = $("select[name=fragmento]").val();
                            if (fragmento_id > 0) {
                                $("#txt_lista").val(textoFecha);
                                $("#txt_lista").css("display", "inline");
                            }
                        }
                    });
                    $('.selectedHour').timepicker($.datepicker.regional['es']);


                    /*var bar = $('.bar');
                     var percent = $('.percent');
                     var status = $('#status');
                     
                     $('#frm').ajaxForm({
                     beforeSend: function() {
                     status.empty();
                     var percentVal = '0%';
                     bar.width(percentVal)
                     percent.html(percentVal);
                     },
                     uploadProgress: function(event, position, total, percentComplete) {
                     var percentVal = percentComplete + '%';
                     bar.width(percentVal)
                     percent.html(percentVal);
                     },
                     complete: function(xhr) {
                     status.html(xhr.responseText);
                     save_database();
                     }
                     });*/

                    // Botón para subir las fotos
<?php if ($objBeanForm->video_id > 0) { ?>
                        var btn_firma = $('#addImage'), interval;
                        new AjaxUpload('#addImage', {
                            action: 'admin/videos/subir_imagen',
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
                                    saveImages(respuesta);
                                    //$('#fotografia').removeAttr('scr');
                                    //$('#fotografia').attr('src','images/' + respuesta.fileName);
                                    //$('#loaderAjax').show();
                                    // alert(respuesta.mensaje);
                                }
                                else {
                                    alert(respuesta.mensaje);
                                }

                                this.enable();
                            }
                        });

<?php } ?>

                    //tags para tematicas
                    /* $("#tematicas").select2({
                     tags:["red", "green", "blue"],
                     width:400
                     });*/

                });

                function saveImages(respuesta) {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    var editorText = CKEDITOR.instances.descripcion.getData();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'descripcion_updated',
                        name: 'descripcion_updated',
                        value: editorText
                    }).appendTo('#frm');
                    var serializedData = $('#frm').serialize();
                    var post_url = "/admin/videos/registrar_imagenes/" + values['canal_id'] + "/" + values['video_id'];
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        data: respuesta,
                        success: function(returnRespuesta) //we're calling the response json array 'cities'
                        {
                            $('#loaderAjax').hide();
                            //var liHtml = '<li><a class=\"dd-option\"> <input type=\"hidden\" value=\"'+returnRespuesta.imagen_id+'\" class=\"dd-option-value\"> <img src=\"'+returnRespuesta.url+'\" class=\"dd-option-image\"></a></li>';
                            //var tdHtml = '<td><img src=\"'+returnRespuesta.url+'\" /></td>';
                            //$("#listImage").show();
                            //$("#tableIaages tr").prepend(tdHtml);
                            //$("#listaImagenes ul").prepend(liHtml);
                            //limpiar
                            $('#listaImagenes').ddslick('destroy');
                            $("#contenedorImage").empty();
                            var htmlN = '<select id="listaImagenes">';
                            $.each(returnRespuesta.imagenes, function(k, v) {
                                //console.log(k+" ->"  +v.imagen_id + ", "+v.url); 
                                htmlN += '<option value=\"' + v.id + '\" data-imagesrc=\"' + v.path + '\" data-description=\" \"></option>';
                            });
                            htmlN += '</select>';
                            $("#contenedorImage").html(htmlN);
                            $('#listaImagenes').ddslick({
                                width: 300,
                                imagePosition: "center",
                                selectText: "Seleccione su imagen principal",
                                onSelected: function(data) {
                                    //console.log(data);
                                    activeImageVideo(data['selectedData'].value);
                                }
                            });

                        } //end success
                    }); //end AJAX        
                }

                function save_database() {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    var editorText = CKEDITOR.instances.descripcion.getData();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'descripcion_updated',
                        name: 'descripcion_updated',
                        value: editorText
                    }).appendTo('#frm');
                    var serializedData = $('#frm').serialize();
                    //var post_url = "/admin/videos/save_maestro/"+values['txt_'+type_video]+"/"+values['canal_id']+"/"+values['categoria']+"/"+type_video;
                    var post_url = "/admin/videos/carga_unitaria/" + values['canal_id'] + "/" + values['video_id'];
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        //dataType: 'json',
                        data: serializedData,
                        success: function(returnVideo) //we're calling the response json array 'cities'
                        {
                            var resultado = false;
                            $.each(returnVideo, function(id, videoValue) {
                                if (id == 'error' && videoValue == '0') {
                                    resultado = true;
                                }
                            });
                            if (resultado) {
                                var url = "admin/canales/videos/" + values['canal_id'];
                                showMessage('exit', '<?php echo lang('videos:add_video_success') ?>', 2000, url);
                            } else {
                                showMessage('error', '<?php echo lang('videos:not_found_video') ?>', 2000, '');
                            }
                        } //end success
                    }); //end AJAX

                }
                /**
                 * generamos y/o actualizamos la lista de colecciones 
                 */
                function generate_collection() {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    var serializedData = $('#frm').serialize();
                    var post_url = "/admin/videos/generate_coleccion";
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        //dataType: 'json',
                        data: serializedData,
                        success: function(returnData) //we're calling the response json array 'cities'
                        {
                            $('select[name="coleccion"]').empty();
                            $.each(returnData, function(id, maestro)
                            {
                                if (id != 'error') {
                                    var opt = $('<option />'); // here we're creating a new select option for each group
                                    opt.val(id);
                                    opt.text(maestro);
                                    $('select[name="coleccion"]').append(opt);
                                }
                            });
                            $('select[name="coleccion"]').trigger("liszt:updated");
                            //limpiamos la lista de reproducciones
                            $('select[name="lista"]').empty();
                            var opt = $('<option />'); // here we're creating a new select option for each group
                            opt.val('0');
                            opt.text('<?php echo lang('videos:select_list') ?>');
                            $('select[name="lista"]').prepend(opt);
                            $('select[name="lista"]').trigger("liszt:updated");
                            //limpiamos y generamos la nueva lista de reproducción relacionadas al canal directamente
                            //if(values['programa'] == 0){
                            generate_list();
                            //}
                        } //end success
                    }); //end AJAX
                }
                /**
                 * generación de listas en base a los programas  o colecciones 
                 * @returns {undefined}     */
                function generate_list() {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    var serializedData = $('#frm').serialize();
                    var post_url = "/admin/videos/generate_lista";
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        //dataType: 'json',
                        data: serializedData,
                        success: function(returnData) //we're calling the response json array 'cities'
                        {
                            $('select[name="lista"]').empty();
                            $.each(returnData, function(id, maestro){
                                if (id != 'error') {
                                    var opt = $('<option />'); // here we're creating a new select option for each group
                                    opt.val(id);
                                    opt.text(maestro);
                                    $('select[name="lista"]').append(opt);
                                }
                            });
                            $('select[name="lista"]').trigger("liszt:updated");
                        } //end success
                    }); //end AJAX        
                }

                function addTitle() {
                    var values = {};
                    $.each($('#frm').serializeArray(), function(i, field) {
                        values[field.name] = field.value;
                    });
                    values['titulo'] = $.trim(values['titulo']);
                    if (values['fragmento'] > 0) {
                        $("#titulo").val('PARTE ' + values['fragmento']);
                        $("#titulo").attr("readonly", "readonly");
                    } else {
                        $("#titulo").val('');
                        $("#titulo").removeAttr("readonly", "readonly");
                    }
                }
    </script>
    <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal->id; ?>" />
    <input type="hidden" name="tipo_maestro" id="tipo_maestro" value="" />
    <input type="hidden" name="video_id" id="video_id" value="<?php echo $objBeanForm->video_id ?>" />
    <input type="hidden" name="existe_fragmento" id="existe_fragmento" value="0" />
    <?php if ($objBeanForm->video_id > 0) { ?>
        <input type="hidden" name="video" id="video" value="<?php echo $objBeanForm->video_id . '.mp4' ?>" />
    <?php } ?>
    <!--<div class="progress_upload" style="display:none;">
          <div class="bar"></div >
          <div class="percent">0%</div >
      </div>
      
      <div id="status"></div>-->
    <!--Include the iframe-->
    <br />
    <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
    <br />
    <!---->  
    <?php echo form_close() ?>
</section>


<link rel="stylesheet" type="text/css" href="<?php echo base_url("system/cms/themes/pyrocms/css/nouislider.fox.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url("system/cms/themes/pyrocms/css/mediasplitter.css") ?>">

<script src="<?php echo base_url("system/cms/themes/pyrocms/js/lib/jwplayer.js") ?>"></script>
<script src="<?php echo base_url("system/cms/themes/pyrocms/js/lib/jquery.nouislider.min.js") ?>"></script>
<script src="<?php echo base_url("system/cms/themes/pyrocms/js/lib/splitter.js") ?>"></script>
<script type="text/javascript">
    $(function() {
        $(".mediaspliter-edit").on('click', function(e) {
            var _this = $(this)
                    mediaBlock = _this.closest('div.mediasplitter').addClass('mediasplitter-show');
            new MediaSplitter('#' + mediaBlock.attr('id'));
            _this.closest('p').remove();
        });
    });
</script>