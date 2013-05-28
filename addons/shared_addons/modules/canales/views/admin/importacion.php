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
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('/admin/videos/grupo_maestro/' . $objCanal->id, 'Crear programas', array('class' => ''));            
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
    $attributes = array('class' => 'frm', 'id' => 'frmImportacion', 'name' => 'frmImportacion');
    echo form_open_multipart('admin/canales/importacion/' . $objCanal->id, $attributes, $hidden);
    ?>
    <div class="left_arm">
        <!-- programa -->
        <label for="programa"><?php echo lang('videos:programa_label'); ?></label>
        <?php
        $js = 'onChange="generate_collection();"';
        echo form_dropdown('programa', $programa, 0, $js);
        ?>
        <!-- coleccion -->
        <br /><br />
        <label for="coleccion"><?php echo lang('videos:collection'); ?></label>
        <?php
        $js = 'onChange="generate_list();" id="coleccion"';
        echo form_dropdown('coleccion', $coleccion, 0, $js);
        ?>
        <!-- lista de reproducción -->
        <br /><br />
        <label for="lista_rep"><?php echo lang('videos:lista_reprod_label'); ?></label>
        <?php
        echo form_dropdown('lista', $lista, 0);
        ?>
        <!-- titulo -->
        <label for="txtTag">Tag: <span class="required">*</span></label>
        <?php
        $titulo = array(
            'name' => 'tag',
            'id' => 'tag',
            'value' => '',
            'maxlength' => '100',
            'style' => 'width:556px;',
            'onkeypress' => 'return textonly(event)'
        );
        echo form_input($titulo);
        ?>        
    </div>
    <div class="main_opt">
        <div id="button_send">
            <a href="javascript:migrar_canal();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>    
        </div>
        <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $objCanal->id ?>" />
    </div>
    <?php echo form_close() ?>
</section>
<script type="text/javascript">
    function mostrar_titulo() {
        var vista = 'importacion_videos';
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
     * generamos y/o actualizamos la lista de colecciones 
     */
    function generate_collection() {
        var values = {};
        $.each($('#frmImportacion').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        var serializedData = $('#frmImportacion').serialize();
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
        $.each($('#frmImportacion').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        var serializedData = $('#frmImportacion').serialize();
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

    function textonly(e) {
        var code;
        if (!e)
            var e = window.event;
        if (e.keyCode)
            code = e.keyCode;
        else if (e.which)
            code = e.which;
        var character = String.fromCharCode(code);
        if (code == 32 || code == 8 || code == 46 || code == 241 || code == 209) {
            return true;
        } else {
            var AllowRegex = /^[0-9A-Za-z]+$/;
            if (AllowRegex.test(character))
                return true;
        }
        return false;
    }

    function migrar_canal() {
        var buttonHtml = '<a href="#" onclick="return false;" class="btn silver" type="button"><?php echo lang('buttons.save'); ?></a>';
        $("#button_send").html(buttonHtml);
        jConfirm("Seguro que deseas realizar la importación?", "Canales", function(r) {
            if (r) {
                var post_url = "/admin/videos/iniciar_migracion/" + $("#canal_id").val();
                var serializedData = $('#frmImportacion').serialize();
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    data: serializedData,
                    success: function(respuesta)
                    {
                        if (respuesta.error == 1) {//no tiene apikey
                            showMessage('error', 'Este canal no tiene un apikey', 2000, '');
                            buttonHtml = '<a href="javascript:migrar_canal();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>';
                            $("#button_send").html(buttonHtml);
                        } else {
                            //showMessage('exit', 'Se importaron ' + respuesta.cantidad + ' video', 2000, '');
                            if (respuesta.error == 2) {//no tiene tag
                                showMessage('error', 'Ingrese un tag', 2000, '');
                                buttonHtml = '<a href="javascript:migrar_canal();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>';
                                $("#button_send").html(buttonHtml);
                            } else {
                                if (respuesta.error == 3) {//no maestros
                                    showMessage('error', 'Seleccione un maestro', 2000, '');
                                    buttonHtml = '<a href="javascript:migrar_canal();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>';
                                    $("#button_send").html(buttonHtml);
                                } else {
                                    showMessage('exit', 'Se importaron ' + respuesta.cantidad + ' videos', 2000, '');
                                }
                            }
                        }
                    } //end success
                }); //end AJAX                  
            } else {
                buttonHtml = '<a href="javascript:migrar_canal();" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>';
                $("#button_send").html(buttonHtml);
            }
        });
    }




    $(document).ready(function() {
        mostrar_titulo();
        //upload de la imagen de portada
    });
</script>
<?php echo form_close() ?>
</section>