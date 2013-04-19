<style>
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<section class="title"> 
    <?php
    echo anchor('admin/videos/carga_unitaria/' . $canal_id, 'Carga unitaria', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
/*    echo anchor('admin/videos/carga_masiva/' . $canal_id, 'Carga masiva', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';*/
    echo anchor('admin/videos/maestro/' . $canal_id, 'Organizar videos', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo anchor('admin/canales/portada/' . $canal_id, 'Portadas', array('class' => ''));
    ?>
</section>
<?php if ($objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')): ?>
<!--    <section>
        <a href="#" id="display-form" title="<?php //echo lang('portada:add_portada'); ?>"><?php //echo lang('portada:add_portada'); ?></a>
    </section>-->
<?php endif; ?>
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('portadas'); ?>
    </div>
    <?php if (/*$objCanal->tipo_canales_id == $this->config->item('canal:mi_canal')*/TRUE): ?>
        <script type="text/javascript">
            $(function() {
                mostrar_titulo();
                var altura = $(document).height();
                $(".bajada2").css('height', '800');
                console.log(altura);
                var nombre = $("#nombre"),
                        nombre_seccion = $("#nombre_seccion"),
                        descripcion = $("#descripcion"),
                        descripcion_seccion = $("#descripcion_seccion"),
                        tipo = $("select[name=tipo]"),
                        tipo_seccion = $("select[name=tipo_seccion]"),
                        allFields = $([]).add(nombre).add(descripcion).add(tipo),
                        tips = $(".validateTips");

                function updateTips(t) {
                    tips
                            .text(t)
                            .addClass("ui-state-highlight");
                    setTimeout(function() {
                        tips.removeClass("ui-state-highlight", 1500);
                    }, 500);
                }

                function checkLength(o, n, min, max) {
                    if (o.val().length > max || o.val().length < min) {
                        o.addClass("ui-state-error");
                        updateTips("La cantidad de texto del campo " + n + " debe estar entre " +
                                min + " y " + max + ".");
                        return false;
                    } else {
                        return true;
                    }
                }

                function checkRegexp(o, regexp, n) {
                    if (!(regexp.test(o.val()))) {
                        o.addClass("ui-state-error");
                        updateTips(n);
                        return false;
                    } else {
                        return true;
                    }
                }

                $("#dialog-form").dialog({
                    autoOpen: false,
                    height: 540,
                    width: 540,
                    modal: true,
                    buttons: {
                        "Registrar": function() {
                            var bValid = true;
                            allFields.removeClass("ui-state-error");

                            bValid = bValid && checkLength(nombre, "nombre", 3, 150);
                            bValid = bValid && checkLength(descripcion, "descripcion", 6, 200);
                            //bValid = bValid && checkLength(tipo, "tipo", 5, 16);

                            //bValid = bValid && checkRegexp(nombre, /^[a-z]([0-9a-z_])+$/i, "EL nombre solo es valido  caracteres alfanumericos a-z, 0-9");
                            //bValid = bValid && checkRegexp(descripcion, /^[a-z]([0-9a-z_])+$/i, "EL nombre solo es valido  caracteres alfanumericos a-z, 0-9");
                            // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                            // bValid = bValid && checkRegexp(descripcion, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com");
                            //bValid = bValid && checkRegexp(tipo, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9");
                            if (bValid) {
                                var post_url = "/admin/canales/agregar_portada/" + $("#canal_id").val();
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    dataType: 'json',
                                    data: 'nombre=' + nombre.val() + '&descripcion=' + descripcion.val() + '&tipo=' + tipo.val(),
                                    success: function(respuesta)
                                    {
                                        //$(".validateTips").empty();
                                        if (respuesta.error == 1) {
                                            //$(".validateTips").html('<?php //echo lang('portada:portada_existe');    ?>');
                                            updateTips('<?php echo lang('portada:portada_existe'); ?>');
                                        } else {
                                            $(this).dialog("close");
                                            location.reload();
                                        }
                                    } //end success
                                }); //end AJAX                         
                            }
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function() {
                        allFields.val("").removeClass("ui-state-error");
                    }
                });

                $("#display-form")
                        .button()
                        .click(function() {
                    $("#dialog-form").dialog("open");
                    return false;
                });

                $("#seccion-form").dialog({
                    autoOpen: false,
                    height: 540,
                    width: 540,
                    modal: true,
                    buttons: {
                        "Registrar": function() {
                            var bValid = true;
                            allFields.removeClass("ui-state-error");
                            bValid = bValid && checkLength(nombre_seccion, "nombre", 3, 150);
                            bValid = bValid && checkLength(descripcion_seccion, "descripcion", 6, 200);
                            if (bValid) {
                                var serializedData = $('#frmNuevoSeccion').serialize();
                                var post_url = "/admin/canales/agregar_seccion/" + $("#portada_id").val();
                                $.ajax({
                                    type: "POST",
                                    url: post_url,
                                    dataType: 'json',
                                    data: serializedData+'&canal_id='+$("#canal_id").val(),
                                    success: function(respuesta)
                                    {
                                        if (respuesta.error == 1) {
                                            updateTips('<?php echo lang('portada:seccion_existe'); ?>');
                                        } else {
                                            var htmlTable = '<table><tr>';
                                            htmlTable += '<td style="width: 5%;">' + respuesta.value.indice + '</td>';
                                            htmlTable += '<td style="width: 28%;">' + respuesta.value.nombre + '</td>';
                                            htmlTable += '<td style="width: 30%;">' + respuesta.value.descripcion + '</td>';
                                            htmlTable += '<td style="width: 10%;">' + respuesta.value.estado + '</td>';
                                            htmlTable += '<td style="width: 25%;">' + respuesta.value.acciones + '</td>';
                                            htmlTable += '</tr></table>';
                                            $("#" + respuesta.portada_id).append(htmlTable);
                                            console.log(this);
                                            $("#seccion-form").dialog("close");
                                            //location.reload();
                                        }
                                    } //end success
                                }); //end AJAX                         
                            }
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function() {
                        allFields.val("").removeClass("ui-state-error");
                    }
                });

            });
            function agregar_seccion(portada_id) {
                $("#portada_id").val(portada_id);
                $("#seccion-form").dialog("open");
            }
            function agregar_portada() {
                $("#dialog-form").dialog("open");
            }
            
        function mostrar_titulo() {
            var vista = 'portadas';
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


        </script>
        <div id="dialog-form" title="Agregar nueva Portada"  style="display:none;">
            <p class="validateTips"><?php echo lang('portada:all_form_fiels_are_required'); ?></p>
            <form>
                <fieldset>
                    <label for="name"><?php echo lang('canales:nombre_label'); ?></label>
                    <input type="text" name="nombre" id="nombre" class="text ui-widget-content ui-corner-all" style="width:420px;" />
                    <label for="email"><?php echo lang('canales:descripcion_label'); ?></label>
                    <input type="text" name="descripcion" id="descripcion" value="" class="text ui-widget-content ui-corner-all" style="width:420px;" />
                    <label for="password"><?php echo lang('portada:tipo_portada') ?></label>
                    <?php echo form_dropdown('tipo', $tipo, 0); ?>
                    <input type="hidden" nombre="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
                </fieldset>
            </form>
        </div>
        <div id="seccion-form" title="Agregar nueva Sección" style="display:none;">
            <p class="validateTips"><?php echo lang('portada:all_form_fiels_are_required'); ?></p>
            <form id="frmNuevoSeccion" name="frmNuevoSeccion">
                <fieldset>
                    <input type="hidden" nombre="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
                    <label for="name"><?php echo lang('canales:nombre_label'); ?></label>
                    <input type="text" name="nombre_seccion" id="nombre_seccion" class="text ui-widget-content ui-corner-all" style="width:420px;" />
                    <label for="descripcion"><?php echo lang('canales:descripcion_label'); ?></label>
                    <input type="text" name="descripcion_seccion" id="descripcion_seccion" value="" class="text ui-widget-content ui-corner-all" style="width:420px;" />
                    <br />
<!--                    <label for="tipo_seccion"><?php //echo lang('portada:tipo_portada') ?></label>
                    <?php //echo //form_dropdown('tipo_seccion', $tipo_seccion, 10); ?>
                    <br /><br />-->
                    <label for="templates"><?php echo lang('portada:template') ?></label>
                    <?php echo form_dropdown('template', $templates, 0); ?>
                    
                    <input type="hidden" nombre="portada_id" id="portada_id" value="" />
                </fieldset>
            </form>
        </div>
    <?php endif; ?>
</section>