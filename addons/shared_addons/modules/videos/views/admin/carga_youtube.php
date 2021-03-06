<style>
    .progress_upload{position:relative;width:400px;border:1px solid #ddd;padding:1px;border-radius:3px;display:none;}
    .bar{background-color:#b4f5b4;width:0%;height:20px;border-radius:3px}
    .percent{position:absolute;display:inline-block;top:3px;left:48%}
</style>
<section class="title">
    <div>
        <ul class="main_menu">
        <?php
        if ($canal->tipo_canales_id != $this->config->item('canal:mi_canal')) {
        ?>
            <li>
        <?php echo anchor('admin/videos/carga_unitaria/' . $canal->id, $this->config->item('submenu:carga_unitaria'), array('class' => '')); ?>
            </li>
            <li class="active">
        <?php echo anchor('admin/videos/carga_youtube/' . $canal->id, $this->config->item('submenu:carga_youtube'), array('class' => '')); ?>
            </li>
        <?php
        }
        /*    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
          echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
        //echo anchor('admin/videos/maestro/' . $canal->id, 'Organizar videos', array('class' => ''));
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
            <li class="alast"></li>
            <li class="last">
        <?php echo anchor('admin/canales/papelera/' . $canal->id, 'Papelera', array('class' => '')); ?>
            </li>
        </ul>
    </div>
</section>
<script type="text/javascript">
    var ul_width = parseInt($('section.title div ul.main_menu').css('width'));
    var lilast_pos = $('section.title div ul.main_menu li.last').position();
 
    var anew_width = ul_width - lilast_pos.left;
    $('section.title div ul.main_menu li.alast').css('width',anew_width);
</script>
<section class="item">
    <?php
    if ($objBeanForm->video_id > 0):
        $title_tab = 'Editar ' . $objBeanForm->titulo;
    else:
        $title_tab = 'Registrar nuevo video ';
        ?>
    <?php endif; ?> 

    <?php
    ////get unique id 
    $up_id = uniqid();
    ?>
    <?php if ($objBeanForm->error) { ?>
        <div> <?php echo $objBeanForm->message; ?></div>
    <?php } ?>
    <!--FORM CARGA YOUTUBE-->
    <?php
    // Canales_id       
    $hidden = array('canal_id' => $canal->id);

    $attributes = array('class' => 'frm', 'id' => 'frm', 'name' => 'frm', "method" => "post");
    echo form_open_multipart('file.php', $attributes, $hidden);
    ?>
    <!--APC hidden field-->
    <input type="hidden" name="APC_UPLOAD_PROGRESS" id="progress_key" value="<?php echo $up_id; ?>"/>
    <!---->
    <div id="tabs">
        <ul>
            <div id="btnSave" style="float: left; padding-right: 10px;">
                <a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>
                <!--<a href="javascript:saveVideo();" class="btn orange" type="button"><?php //echo lang('buttons.save');      ?></a>-->
            </div>
            <li><a href="#tabs-1"><?php echo $title_tab; ?></a></li>
            <?php if ($objBeanForm->video_id > 0): ?>
                <li><a href="#tabs-2">Imagenes</a></li>
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
                    'value' => $objBeanForm->titulo,
                    'maxlength' => '100',
                    'style' => 'width:556px;'
                        //'readonly'=>'readonly'
                );
                echo form_input($titulo);
                ?>
                <br /><br /><br />
                <?php if ($objBeanForm->video_id == 0) { ?>
                    <!-- video -->
                    <label for="video"><?php echo lang('videos:youtube_url'); ?> <span class="required">*</span></label>
                    <?php
                    $video = array(
                        'name' => 'video',
                        'id' => 'video',
                        'maxlength' => '100',
                        'style' => 'width:556px;',
                        'data-regexp' => "(http://)?(www\.)?(youtube|yimg|youtu)\.([A-Za-z]{2,4}|[A-Za-z]{2}\.[A-Za-z]{2})/(watch\?v=)?[A-Za-z0-9\-_]{6,12}(&[A-Za-z0-9\-_]{1,}=[A-Za-z0-9\-_]{1,})*",
                    );
                    echo form_input($video);
                }
                ?>

                <!-- iFrame del video sólo para los publicados -->
                <?php if ($objBeanForm->video_id > 0) : ?>
                    <?php if ($objBeanForm->estado == ESTADO_PUBLICADO) : ?>
                        <div class="embed_video">
                            <textarea class="embed_content" readonly="readonly">[iframe width="560" height="315" src="<?php echo $this->config->item('motor') . '/embed/' . $objBeanForm->video_id ?>" frameborder="0" allowfullscreen][/iframe]</textarea>
                        </div>
                        <script>
                            if ($('.embed_content').length == 1) {
                                var embed = $(".embed_content").val();

                                embed = embed.replace("[", "<");
                                embed = embed.replace("]", "/>");
                                embed = embed.replace("[", "<");
                                embed = embed.replace("]", "/>");
                                $(".embed_content").val(embed);
                            }
                        </script>
                    <?php endif ?>
                <?php endif ?>

                <!-- descripcion -->
                <br /><br /><br />
                <label for="descripcion"><?php echo lang('videos:descripcion'); ?> <span class="required">*</span></label>
                <?php echo form_textarea(array('id' => 'descripcion', 'name' => 'descripcion', 'value' => $objBeanForm->descripcion, 'rows' => 5, 'class' => 'wysiwyg-simple')); ?>
                
                <?php if (count($objClips) > 0 && $objBeanForm->video_id > 0) : ?>
                    <br /><br />
                    <!-- Lista de clips -->
                    <label for="descripcion"><?php echo lang('videos:clips_label'); ?></label>
                    <br />
                    <table>
                        <tr>
                            <th><?php echo lang('videos:imagen_label'); ?></th>
                            <th><?php echo lang('videos:titulo_label'); ?></th>     
                        </tr>
                        <?php foreach ($objClips as $clip) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $clip->ruta ?>">
                                        <img src="<?php echo $clip->imagen ?>" alt="<?php echo $clip->titulo ?>" title="<?php echo $clip->titulo ?>" style="width:100px"/>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo $clip->ruta ?>">
                                        <?php echo $clip->titulo ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
            </div>
            <div class="right_arm">
                <!-- categoria -->
                <label for="categoria"><?php echo lang('videos:categoria_label'); ?> <span class="required">*</span></label>
                <?php echo form_error('categoria'); ?><br />
                <?php echo form_dropdown('categoria', $categoria, $objBeanForm->categoria); ?>

                <br /><br/><br />
                <!-- tags tematicos -->
                <label for="tematicas"><?php echo lang('videos:etiquetas_tematicas_label'); ?> <span class="required">*</span></label>
                <div class="input"><?php echo form_input('tematicas', $objBeanForm->tematicas, 'id="tematicas"') ?></div>
                
                <!-- tags personajes -->
                <br /><br /><br />
                <label for="personajes"><?php echo lang('videos:etiquetas_personajes_label'); ?></label>
                <div class="input"><?php echo form_input('personajes', $objBeanForm->personajes, 'id="personajes"') ?></div>        

            </div>
            <div class="main_opt">            
                <div  style="float: left;"></div>
            </div>
            <script type="text/javascript" >
                function mostrar_titulo() {
                    var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal->id; ?>/" + 'carga_youtube';
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'html',
                        //data:imagen_id,
                        success: function(respuesta) //we're calling the response json array 'cities'
                        {
                            $(".subbar > .wrapper").html(respuesta);
                        }
                    });
                }
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
                    var post_url = "/admin/videos/verificarVideoYouTube/" + values['canal_id'] + "/" + values['video_id'];
                    //var r;
                    $.post(post_url, serializedData, function(data) {
                        if (data.errorValue == '0') {
                            $("#btnSave").html('<a href="#" class="btn silver" onclick="return false;" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
                            $('#frm').submit();
                            //$("#btnSave").find("a").attr("disabled","false");
                        } else {
                            showMessage('error', '<?php echo lang('videos:fragment_exist') ?>', 2000, '');
                            $("#btnSave").html('<a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
                        }
                    }, "json");
                    /*var ff = $.ajax({
                     type: "POST",
                     url: post_url,
                     dataType: 'json',
                     data:serializedData,
                     success: function(returnValue) //we're calling the response json array 'cities'
                     {
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
                /**
                 * Guarda información y el video subido
                 */
                function saveVideo() {
                    $("#btnSave").html('<a href="#" class="btn silver" onclick="return false;" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
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
                    var $pass = true;
                    var $message = '';
                    //validando si es video YouTube
                    var regExp = $('input#video').data('regexp');
                    var re = new RegExp(regExp);
                    //validamos el ckeditor
                    var editorText = CKEDITOR.instances.descripcion.getData();
                    editorText = $.trim(editorText);
                    var regex = /(<([^>]+)>)/ig;
                    var editorText2 = editorText.replace(regex, "");
                    editorText2 = $.trim(editorText2);
                    editorText2 = editorText2.replace(/(&nbsp;)*/g, "");

                    if (titulo.length === 0) {
                        $message = '<?php echo lang('videos:require_title') ?>';
                        $pass = false;
                    } else if (inputfile.length === 0) {
                        $message = '<?php echo lang('videos:require_youtube') ?>';
                        $pass = false;
                    } else if (!inputfile.match(re)) {
                        $message = '<?php echo lang('videos:youtube_invalid') ?>';
                        $pass = false;
                    } else if (editorText.length === 0 && editorText2.length === 0) {
                        $message = '<?php echo lang('videos:require_description') ?>';
                        $pass = false;
                    } else if (values['categoria'] === '0') {
                        $message = '<?php echo lang('videos:require_category') ?>';
                        $pass = false;
                    } else if (values['tematicas'].length === 0) {
                        $message = '<?php echo lang('videos:require_tematicas') ?>';
                        $pass = false;
                    }

                    if ($pass) {
                        existeFragmento();
                    } else {
                        showMessage('error', $message, 2000, '');
                        $("#btnSave").html('<a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
                    }
                }

                /**
                 * funcion para agregar nuevos programas, colecciones, listas
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
                    mostrar_titulo();
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


                    var bar = $('.bar');
                    var percent = $('.percent');
                    var status = $('#status');

                    $('#frm').ajaxForm({
                        beforeSend: function() {
                            //$(".progress_upload").css("display", "block");
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
                    });

                    // Botón para subir las fotos
<?php if ($objBeanForm->video_id > 0) { ?>
                        var btn_firma = $('#addImage'), interval;
                        new AjaxUpload('#addImage', {
                            action: 'admin/videos/subir_imagen/<?php echo $objBeanForm->video_id; ?>',
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
                                }
                                else {
                                    alert(respuesta.mensaje);
                                }

                                this.enable();
                            }
                        });
<?php } ?>
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
                    var tipo = $("#tipo").val();
                    //var post_url = "/admin/videos/registrar_imagenes/" + values['canal_id'] + "/" + values['video_id'];
                    var post_url = "/admin/videos/subir_imagenes_maestro/" + values['video_id'] + "/" + tipo;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        data: respuesta,
                        success: function(returnRespuesta) //we're calling the response json array 'cities'
                        {
                            $('#loaderAjax').hide();
                            $.each(returnRespuesta.imagenes, function(k, v) {
                                var htmlimagen = '<img src="' + v.imagen + '" style="width:120px; height: 70px;">';
                                $("#tipo_" + v.tipo_imagen_id).html(htmlimagen);
                                $("#codigo_" + v.tipo_imagen_id).html(v.id);
                                $("#proceso_" + v.tipo_imagen_id).empty();
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
                    var post_url = "/admin/videos/carga_youtube/" + values['canal_id'] + "/" + values['video_id'];
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        dataType: 'json',
                        data: serializedData,
                        success: function(respuesta) //we're calling the response json array 'cities'
                        {
                            if (respuesta.error == 1) {
                                showMessage('error', '<?php echo lang('videos:size_invalid') ?>', 2000, '');
                                $("#btnSave").html('<a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
                            } else {
                                if (respuesta.error == 2) {
                                    showMessage('error', '<?php echo lang('videos:format_invalid') ?>', 2000, '');
                                    $("#btnSave").html('<a href="javascript:saveVideo();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?><img src="<?php echo BASE_URL ?>system/cms/themes/pyrocms/img/save.png" /></a>');
                                } else {
                                    if (respuesta.error == 0) {
                                        var url = "admin/canales/videos/" + values['canal_id'];
                                        showMessage('exit', '<?php echo lang('videos:add_video_success') ?>', 2000, url);
                                    }
                                }
                            }
                            /*var resultado = false;
                             $.each(returnVideo, function(id, videoValue) {
                             if (id == 'error' && videoValue == '0') {
                             resultado = true;
                             }
                             });
                             if (resultado) {
                             var url = "admin/canales/videos/" + values['canal_id'];
                             showMessage('exit', '<?php //echo lang('videos:add_video_success')        ?>', 2000, url);
                             } else {
                             showMessage('error', '<?php //echo lang('videos:not_found_video')        ?>', 2000, '');
                             }*/
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
                            $.each(returnData, function(id, maestro)
                            {
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

            <div class="progress_upload" style="display: none; clear: both;">
                <div class="bar"></div >
                <div class="percent">0%</div >
            </div>

            <div id="status"></div>
            <!--Include the iframe-->
            <!--            <br />
                        <div style="clear: both;">
                            <iframe id="upload_frame" name="upload_frame" frameborder="0" border="0" src="" scrolling="no" scrollbar="no" > </iframe>
                        </div>
                        
                        <br />-->
            <!---->
        </div>
        <?php if ($objBeanForm->video_id > 0): ?>
            <div id="tabs-2"  style="width: 100%;">
                <?php template_partial('imagenes'); ?>
            </div>
        <?php endif; ?>
    </div>
    <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal->id; ?>" />
    <input type="hidden" name="padre" id="padre" value="<?php echo $objBeanForm->padre; ?>" />
    <input type="hidden" name="tipo_maestro" id="tipo_maestro" value="" />
    <input type="hidden" name="video_id" id="video_id" value="<?php echo $objBeanForm->video_id ?>" />
    <input type="hidden" name="existe_fragmento" id="existe_fragmento" value="0" />
    <?php if ($objBeanForm->video_id > 0) { ?>
        <input type="hidden" name="video" id="video" value="<?php echo $objBeanForm->video_id . '.mp4' ?>" />
    <?php } ?>    
    <?php echo form_close() ?>
</section>

