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
            <li class="alast"></li>
            <li class="active last">
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
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('papeleras'); ?>
    </div> 
</section>
<script type="text/javascript">
    $(document).ready(function() {
        mostrar_titulo();
    });
    function mostrar_titulo() {
        var vista = 'papelera';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal->id; ?>/" + vista;
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

    function restaurar_maestro(id, tipo) {
        jConfirm("Seguro que deseas restaurar este Item?", "Papelera", function(r) {
            if (r) {
                var post_url = "/admin/canales/restaurar/" + id + "/" + tipo;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            $("#" + tipo + "_" + id).empty();
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }

    function eliminar(id, tipo) {
        jConfirm("Seguro que deseas eliminar completamente este Item?", "Papelera", function(r) {
            if (r) {
                var post_url = "/admin/canales/eliminar_completamente/" + id + "/" + tipo;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        switch (respuesta.value) {
                            case "1":
                                $("#" + tipo + "_" + id).empty();
                                break;
                            case "2":
                                showMessage('error', 'No es posible eliminar. El item tiene contenidos', 2000, '');
                                break;
                        }
                    } //end success
                }); //end AJAX   
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
</script>