<section class="title"> 
    <div style ="float: left;">
        <?php
        echo anchor('admin/videos/carga_unitaria/' . $canal->id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        /*    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
          echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
        echo anchor('admin/videos/organizar/' . $canal->id, 'Organizar videos', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/canales/portada/' . $canal->id, 'Portadas', array('class' => ''));
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $canal->id, 'Papelera', array('class' => '')); ?>
    </div>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('users'); ?>
    </div> 
    <!--    <div id="visualizar_video">
            <div class="flowplayer" data-swf="<?php //echo base_url('addons/shared_addons/modules/canales/js/flowplayer.swf')       ?>" data-ratio="0.417">
                <video>
                    <source id="urlvideo" type="video/mp4" src="http://webcast.sambatech.com.br/805FD4/origin1/account/194/10/2013-04-20/video/02db9b15f36f4ffdebab51b3cb2db47c/2102.mp4" />
                </video>
            </div>        
        </div>-->
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var vista = 'Videos';
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
        //funcion reloj para verificar los estados en la DB
<?php if ($this->config->item('video:verificar')): ?>
            var id = setInterval("verificar_estado_video()", <?php echo $this->config->item('video:segundos') ?>);
<?php endif; ?>
        //setTimeout("clearInterval(" + id + ")", 15000);
    });

    function verificar_estado_video() {
        var serializedData = $('#formListaVideo').serialize();
        var post_url = "/admin/videos/verificar_estado_video/";
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            data: serializedData,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                if (respuesta.error == 0) {
                    $.each(respuesta.videos, function(index, value) {
                        if (value == 0) {
                            var estado = 'Codificando';
                        }
                        if (value == 1) {
                            var estado = 'Borrador';
                        }
                        if (value == 2) {
                            var estado = 'Publicado';
                        }
                        if (value == 3) {
                            var estado = 'Eliminado';
                        }
                        $("#video_" + index).html(estado);
                    });
                }
            } //end success
        }); //end AJAX         

    }

    function visualizar_video(video_id) {
        $("#visualizar_video").dialog({
            height: 399,
            width: 820,
            modal: true
        });
        //new MediaSplitter('#media-1', '<?php //echo base_url("addons/shared_addons/modules/canales/js/lib/flowplayer/flowplayer-3.2.16.swf")         ?>');
        var post_url = "/admin/canales/liquid_player/" + video_id + "/400/400";
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'html',
            //data:imagen_id,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                $("#urlvideo").html(respuesta);
            } //end success
        }); //end AJAX         

    }

    function eliminar_video(video_id) {
        jConfirm("Seguro que deseas eliminar este video?", "Video", function(r) {
            if (r) {
                var post_url = "/admin/canales/eliminar_video/" + video_id;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            $("#item_" + video_id).empty();
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }

    function publicar_video(video_id) {
        jConfirm("Seguro que deseas publicar este video?", "Video", function(r) {
            if (r) {
                var post_url = "/admin/canales/publicar_video/" + video_id;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            var htmlVideo = '<a href="/admin/canales/visualizar_video/' + video_id + '" class="mode_preview modal-large_custom" onclick="return false;">Previsualizar</a>';
                            htmlVideo += '<a href="/admin/canales/corte_video/' + $("#canal_id").val() + '/' + video_id + '" class="mode_cut">Cortar</a>';
                            htmlVideo += '<a href="/admin/videos/carga_unitaria/' + $("#canal_id").val() + '/' + video_id + '" class="mode_edit">Editar</a>';
                            htmlVideo += '<a href="#" class="mode_delete" onclick="eliminar_video(' + video_id + ')">Eliminar</a>';
                            $("#accion_" + video_id).html(htmlVideo);
                            var estado = 'Publicado';
                            $("#video_" + video_id).html(estado);
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
</script>
<!--<script src="<?php //echo base_url("system/cms/themes/pyrocms/js/fix_channels.js")               ?>"></script>-->