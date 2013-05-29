<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<section class="title"> 
    <div style ="float: left;">
        <?php
        echo anchor('admin/videos/carga_unitaria/' . $canal_id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        /*    echo anchor('admin/videos/carga_masiva/' . $canal_id, 'Carga masiva', array('class' => ''));
          echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
        echo anchor('admin/videos/organizar/' . $canal_id, 'Organizar videos', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/canales/portada/' . $canal_id, 'Portadas', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('/admin/videos/grupo_maestro/' . $canal_id, 'Crear programas', array('class' => ''));        
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $canal_id, 'Papelera', array('class' => '')); ?>
    </div>    
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
        <input type="hidden" name="txtDescripcion" id="txtDescripcion" value="" />
        <br /><br />
        <div style="width:100%;">
            <div  style=" float: left; width: 50%; text-align: left;">
                <a href="#" onclick="guardarSeccion();
                        return false;" class="btn blue" type="button">Publicar en portada</a>    
                   <?php //echo anchor('/admin/canales/vista_previa/', lang('buttons.preview'), array('target' => '_blank', 'class' => 'btn orange modal-large')); ?>                
            </div>
            <div style="float: right; width: 50%; text-align: right;">
                <?php echo form_dropdown('template', $templates, $objSeccion->templates_id); ?>            
            </div>
            <div style="clear: both;"></div>
        </div>        
        <?php
        echo '<div id="filter-stage">';
        template_partial('secciones');
        echo '</div>';
        ?>
        <div style="width:100%;">
            <div  style=" float: left; width: 50%; text-align: left;">
                <a href="#" onclick="guardarSeccion();
                        return false;" class="btn blue" type="button">Publicar en portada</a>    
                   <?php //echo anchor('/admin/canales/vista_previa/', lang('buttons.preview'), array('target' => '_blank', 'class' => 'btn orange modal-large')); ?>                
            </div>
            <div style="float: right; width: 50%; text-align: right;">
                <?php echo form_dropdown('template', $templates, $objSeccion->templates_id); ?>            
            </div>
            <div style="clear: both;"></div>
        </div>
        <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
        <input type="hidden" name="portada_id" id="portada_id" value="<?php echo $objSeccion->portadas_id; ?>" />
        <input type="hidden" name="seccion_id" id="seccion_id" value="<?php echo $objSeccion->id; ?>" />        
    </div>
    <?php echo form_close() ?>
    <script type="text/javascript">
                    $(document).ready(function() {
                        mostrar_titulo();
                    });
                    function mostrar_titulo() {
                        var vista = 'detalle_seccion';
                        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal_id; ?>/" + vista;
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
                    function buscar_para_destacado_categoria(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_destacado_categoria/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_destacado_categoria');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_programa_micanal(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_programa_micanal/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_programa_micanal');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_micanal(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_micanal/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_micanal');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_destacado_micanal(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_destacado_micanal/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_destacado_micanal');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_losmas_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_losmas_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_losmas_programa');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_video_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_video_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_video_programa');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_lista_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_lista_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_lista_programa');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_coleccion_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_coleccion_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_coleccion_programa');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_destacado_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_destacado_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_portada_programa(newPage, 'buscar_para_destacado_programa');
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_losmas(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_losmas/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_losmas(newPage);
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_video(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_video/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_video(newPage);
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_lista(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_lista/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_lista(newPage);
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_coleccion(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_coleccion/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_coleccion(newPage);
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_programa(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_programa/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_programa(newPage);
                                    }
                                });
                            } //end success
                        }); //end AJAX              
                    }
                    function buscar_para_destacado(numero_pagina) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_destacado/" + numero_pagina;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#divResultado").html(respuesta);
                                var cantidad_mostrar = $("#cantidad_mostrar").val();
                                var total = $("#total").val() - 0;
                                $('#black').smartpaginator({
                                    totalrecords: total,
                                    recordsperpage: cantidad_mostrar,
                                    theme: 'black',
                                    onchange: function(newPage) {
                                        //$('#r').html('Page # ' + newPage);
                                        paginar_destacado(newPage);
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
                    function paginar_portada_programa(newPage, metodo) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/" + metodo + "/" + newPage + "/1";
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
                    function paginar_losmas(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_losmas/" + newPage + "/1";
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
                    function paginar_video(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_video/" + newPage + "/1";
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
                    function paginar_lista(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_lista/" + newPage + "/1";
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
                    function paginar_coleccion(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_coleccion/" + newPage + "/1";
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
                    function paginar_programa(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_programa/" + newPage + "/1";
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
                    function paginar_destacado(newPage) {
                        var serializedData = $('#frmBuscar').serialize();
                        var post_url = "/admin/canales/buscar_para_destacado/" + newPage + "/1";
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

                    function agregarMaestroASeccion(canal_id, maestro_id, seccion_id) {
                        $("#div_" + maestro_id).empty();
                        var html = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + maestro_id).html(html);
                        var post_url = "/admin/canales/agregarMaestroASeccion/" + maestro_id + '/' + seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            data: 'canal_id=' + canal_id,
                            success: function(respuesta)
                            {
                                if (respuesta.error == 1) {
                                    showMessage('error', '<?php echo lang('seccion:not_found_image_template'); ?>', 2000, '');
                                } else {
                                    $("#div_" + maestro_id).empty();
                                    var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                                    $("#div_" + maestro_id).html(htmlAgregado);
                                    mostrar_lista_detalle_seccion(canal_id, seccion_id);
                                }
                            } //end success
                        }); //end AJAX             
                    }
                    function agregarVideoASeccion(canal_id, video_id, seccion_id) {
                        $("#div_" + video_id).empty();
                        var html = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + video_id).html(html);
                        var post_url = "/admin/canales/agregarVideoASeccion/" + video_id + '/' + seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            data: 'canal_id=' + canal_id,
                            success: function(respuesta)
                            {
                                if (respuesta.error == 1) {
                                    showMessage('error', '<?php echo lang('seccion:not_found_image_template'); ?>', 2000, '');
                                } else {
                                    $("#div_" + video_id).empty();
                                    var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                                    $("#div_" + video_id).html(htmlAgregado);
                                    mostrar_lista_detalle_seccion(canal_id, seccion_id);
                                }
                            } //end success
                        }); //end AJAX             
                    }
                    function agregarCanalASeccion(canal_padre, canal_item, seccion_id) {
                        $("#div_" + canal_item).empty();
                        var html = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + canal_item).html(html);
                        var post_url = "/admin/canales/agregarCanalASeccion/" + canal_item + '/' + seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            data: 'canal_id=' + canal_padre,
                            success: function(respuesta)
                            {
                                if (respuesta.error == 1) {
                                    showMessage('error', '<?php echo lang('seccion:not_found_image_template'); ?>', 2000, '');
                                } else {
                                    $("#div_" + canal_item).empty();
                                    var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                                    $("#div_" + canal_item).html(htmlAgregado);
                                    mostrar_lista_detalle_seccion(canal_padre, seccion_id);
                                }
                            } //end success
                        }); //end AJAX             
                    }

                    function mostrar_lista_detalle_seccion(canal_id, seccion_id) {
                        var post_url = "/admin/canales/mostrar_lista_detalle_seccion/" + canal_id + '/' + seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'html',
                            //data: 'maestro_id=' + maestro_id + '&seccion_id=' + seccion_id,
                            success: function(respuesta)
                            {
                                $("#table-1").html(respuesta);
                                $("#table-1").tableDnD({
                                    onDrop: function(table, row) {
                                        ordenarLista($.tableDnD.serialize());
                                    }
                                });
                            } //end success
                        }); //end AJAX          
                        //$("#filter-stage").html('');
                    }

                    /**
                     * método para quitar los items de una sección
                     * @param int detalle_seccion_id
                     * @returns json
                     */
                    function quitarDetalleSeccion(detalle_seccion_id, canal_id, seccion_id) {
                        jConfirm("Seguro que deseas quitar este Item?", "Detalle de secciones", function(r) {
                            if (r) {
                                var post_url = "/admin/canales/quitar_detalle_seccion/" + detalle_seccion_id;
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    dataType: 'json',
                                    //data: indexOrder,
                                    success: function(respuesta)
                                    {
                                        if (respuesta.value == '1') {
                                            //location.reload();
                                            mostrar_lista_detalle_seccion(canal_id, seccion_id);
                                        }
                                    } //end success
                                }); //end AJAX   
                            }
                        });

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

                    function agregar_descripcion(detalle_seccion_id) {
                        var post_url = "/admin/canales/obtener_descripcion_detalle_seccion/" + detalle_seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            //data: serializedData,
                            success: function(respuesta)
                            {
                                var html = '<textarea onkeypress="return textonly(event,' + detalle_seccion_id + ');" id="descripcion_texto_' + detalle_seccion_id + '" nombre="descripcion_texto_' + detalle_seccion_id + '">'+respuesta.value+'</textarea>';
                                var boton = '<a href="#" class="btn blue" onclick="quitar_descripcion(' + detalle_seccion_id + '); return false;">Quitar descripción</a>';
                                $("#descripcion_" + detalle_seccion_id).html(html);
                                $("#boton_" + detalle_seccion_id).html(boton);
                                $("#descripcion_texto_" + detalle_seccion_id).focus();
                            } //end success
                        }); //end AJAX                      

                    }

                    function quitar_descripcion(detalle_seccion_id) {
                        var post_url = "/admin/canales/obtener_descripcion_detalle_seccion/" + detalle_seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            //data: serializedData,
                            success: function(respuesta)
                            {
                                var html = respuesta.value;
                                $("#descripcion_" + detalle_seccion_id).html(html);
                            } //end success
                        }); //end AJAX                          
                        var boton = '<a href="#" class="btn blue" onclick="agregar_descripcion(' + detalle_seccion_id + '); return false;">Agregar descripción</a>';
                        $("#boton_" + detalle_seccion_id).html(boton);
                    }

                    function deshabilitar_boton(id) {
                        var htmlAgregado = '<a href="#" id="agregado" name="agregado" class="btn silver" onclick="return false;">Agregado</a>';
                        $("#div_" + id).html(htmlAgregado);
                    }

                    function textonly(e, detalle_seccion_id) {
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
                                guardar_descripcion(detalle_seccion_id);
                            } else {
                                if (code == 27) {
                                    //cancelar la nueva descripcion
                                    quitar_descripcion(detalle_seccion_id);
                                } else {
                                    return true;
                                }
                            }

                        } else {
                            var AllowRegex = /^[0-9A-Za-z]+$/;
                            if (AllowRegex.test(character))
                                return true;
                        }
                        return false;
                    }

                    function guardar_descripcion(detalle_seccion_id) {
                        var texto = $("#descripcion_texto_" + detalle_seccion_id).val();
                        $("#txtDescripcion").val(texto);
                        var serializedData = $('#frmSeccion').serialize();
                        var post_url = "/admin/canales/guardar_descripcion/" + detalle_seccion_id;
                        $.ajax({
                            type: "POST",
                            url: post_url,
                            dataType: 'json',
                            data: serializedData,
                            success: function(respuesta)
                            {
                                $("#descripcion_" + detalle_seccion_id).empty();
                                $("#descripcion_" + detalle_seccion_id).html(respuesta.texto);
                                var boton = '<a href="#" class="btn blue" onclick="agregar_descripcion(' + detalle_seccion_id + '); return false;">Agregar descripción</a>';
                                $("#boton_" + detalle_seccion_id).html(boton);
                            } //end success
                        }); //end AJAX 
                    }
    </script>
</section>
