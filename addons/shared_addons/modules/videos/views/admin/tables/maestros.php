<style>
    .ui-accordion-header .ui-icon{
        left: 2.5em !important;
    }
    .ui-accordion-content-active{
        height: auto !important;
    }
    #programas{
        font-weight: normal;
        color:#000;
    }
    #programas table tr td{
        text-align: left;
    }
    #programas .ui-accordion{
        width: 95%;
    }
    #programas .ui-accordion-header{
        width: 100%;
    }
</style>
<table>
    <tr>
        <td><div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div></td>
        <td>    <div style="text-align: right;" >
        <?php echo anchor('/admin/videos/grupo_maestro/'.$canal_id.'/0', 'Nuevo programa / coleccion', 'class="btn blue"') ?>
    </div></td>
    </tr>
</table>

<table id="table-1" class="table-list">
    <thead>
        <tr>
            <th style="width: 3%;">#</th>
            <th style="width: 10%;">Imagen</th>
            <th style="width: 20%;">nombre</th>
            <th style="width: 10%;">Tipo</th>
            <th style="width: 10%;">Cantidad de items</th>
            <th style="width: 10%;">Categoría</th>
            <th style="width: 17%;">Fecha publicación</th>
            <th style="width: 10%;">estado</th>
            <th style="width: 10%;">Acciones</th>
        </tr>
    </thead>
    <tbody id="contenido">
    </tbody>
</table>
<div id="programas">
    <?php if (count($lista_programas) > 0): ?>
        <?php foreach ($lista_programas as $index => $objPrograma): ?>
            <h3>
                <table>
                    <tr>
                        <td style="width: 3%;"><?php echo ($index + 1); ?></td>
                        <td style="width: 10%;"><img style="width:100px; height: 60px;" src="<?php echo $objPrograma->imagen; ?>" /></td>
                        <td style="width: 20%;"><?php echo $objPrograma->nombre; ?></td>
                        <td style="width: 10%;"><?php echo $objPrograma->tipo; ?></td>
                        <td style="width: 10%;"><?php echo $objPrograma->cantidad; ?></td>
                        <td style="width: 10%;"><?php echo $objPrograma->categoria; ?></td>
                        <td style="width: 17%;"><?php echo $objPrograma->fecha_registro; ?></td>
                        <td style="width: 10%;"><div id="programa_<?php echo $objPrograma->id; ?>"><?php echo $objPrograma->estado; ?></div></td>
                        <?php
                        switch ($objPrograma->estado_id):
                            case $this->config->item('estado:borrador'):
                                $u = 'admin/videos/grupo_maestro/' . $objPrograma->canales_id . '/' . $objPrograma->id;
                                $link = '<a href="#" class="mode mode_edit" onclick="editar(\'' . $u . '\');return false;">Editar</a>';
                                $link.= '<a href="#" class="mode mode_delete" onclick="eliminar(' . $objPrograma->id . ', \'programa\');return false;">Eliminar</a>';
                                $link.= '<a href="#" class="mode mode_publish" onclick="publicar(' . $objPrograma->id . ', \'programa\');return false;">Publicar</a>';
                                break;
                            case $this->config->item('estado:publicado'):
                                $u = 'admin/videos/grupo_maestro/' . $objPrograma->canales_id . '/' . $objPrograma->id;
                                $link = '<a href="#" class="mode mode_edit" onclick="editar(\'' . $u . '\');return false;">Editar</a>';
                                $link.= '<a href="#" class="mode mode_delete btnEliminar" onclick="eliminar(' . $objPrograma->id . ', \'programa\');return false;">Eliminar</a>';
                                break;
                            case $this->config->item('estado:eliminado'):
                                $u = 'admin/videos/grupo_maestro/' . $objPrograma->canales_id . '/' . $objPrograma->id;
                                //$link = '<button class="btn blue" onclick="publicar(' . $objPrograma->id . ', \'programa\');return false;">Publicar</button>';
                                $link = '<a href="#" class="mode mode_restore" onclick="restablecer(' . $objPrograma->id . ', \'programa\');return false;">Restablecer</a>';
                                break;
                        endswitch;
                        ?>
                        <td style="width: 10%;"><div id="programa_boton_<?php echo $objPrograma->id; ?>"><?php echo $link; ?></div></td>
                    </tr>                    
                </table>
            </h3>
            <div class="coleccion">
                <?php if (count($objPrograma->coleccion) > 0): ?>
                    <?php foreach ($objPrograma->coleccion as $puntero => $objColeccion): ?>
                        <h3>
                            <table style="width: 90%">
                                <tr>
                                    <td><?php echo ($puntero + 1); ?></td>
                                    <td><img style="width:100px; height: 70px;" src="<?php echo $objColeccion->imagen; ?>" /></td>
                                    <td><?php echo $objColeccion->nombre; ?></td>
                                    <td><?php echo $objColeccion->tipo; ?></td>
                                    <td><?php echo $objColeccion->cantidad; ?></td>
                                    <td><?php echo $objColeccion->categoria; ?></td>
                                    <td><?php echo $objColeccion->fecha_registro; ?></td>
                                    <td><div id="coleccion_<?php echo $objColeccion->id; ?>"><?php echo $objColeccion->estado; ?></div></td>
                                    <?php
                                    switch ($objColeccion->estado_id):
                                        case $this->config->item('estado:borrador'):
                                            $u = 'admin/videos/grupo_maestro/' . $objColeccion->canales_id . '/' . $objColeccion->id;
                                            $link = '<button class="btn orange" onclick="editar(\'' . $u . '\');return false;">Editar</button>';
                                            $link.= '<button class="btn red" onclick="eliminar(' . $objColeccion->id . ', \'coleccion\');return false;">Eliminar</button>';
                                            $link.= '<button class="btn blue" onclick="publicar(' . $objColeccion->id . ', \'coleccion\');return false;">Publicar</button>';
                                            break;
                                        case $this->config->item('estado:publicado'):
                                            $u = 'admin/videos/grupo_maestro/' . $objColeccion->canales_id . '/' . $objColeccion->id;
                                            $link = '<button class="btn orange" onclick="editar(\'' . $u . '\');return false;">Editar</button>';
                                            $link.= '<button class="btn red btnEliminar" onclick="eliminar(' . $objColeccion->id . ', \'coleccion\');return false;">Eliminar</button>';
                                            break;
                                        case $this->config->item('estado:eliminado'):
                                            $u = 'admin/videos/grupo_maestro/' . $objColeccion->canales_id . '/' . $objColeccion->id;
                                            //$link.= '<button class="btn blue" onclick="publicar(' . $objColeccion->id . ', \'coleccion\');return false;">Publicar</button>';
                                            $link = '<button class="btn green" onclick="restablecer(' . $objColeccion->id . ', \'coleccion\');return false;">Restablecer</button>';
                                            break;
                                    endswitch;
                                    ?>                                    
                                    <td><div id="coleccion_boton_<?php echo $objColeccion->id; ?>"><?php echo $link; ?></div></td>
                                </tr>                                
                            </table>
                        </h3>
                        <div class="lista">
                            <?php if (count($objColeccion->lista) > 0): ?>
                                <?php foreach ($objColeccion->lista as $puntero_lista => $objLista): ?>
                                    <h3>
                                        <table>
                                            <tr>
                                                <td><?php echo ($puntero_lista + 1); ?></td>
                                                <td><img style="width:100px; height: 70px;" src="<?php echo $objLista->imagen; ?>" /></td>
                                                <td><?php echo $objLista->nombre; ?></td>
                                                <td><?php echo $objLista->tipo; ?></td>
                                                <td><?php echo $objLista->cantidad; ?></td>
                                                <td><?php echo $objLista->categoria; ?></td>
                                                <td><?php echo $objLista->fecha_registro; ?></td>
                                                <td><div id="lista_<?php echo $objLista->id; ?>"><?php echo $objLista->estado; ?></div></td>
                                                <?php
                                                switch ($objLista->estado_id):
                                                    case $this->config->item('estado:borrador'):
                                                        $u = 'admin/videos/grupo_maestro/' . $objLista->canales_id . '/' . $objLista->id;
                                                        $link = '<a href="#" class="mode mode_edit" onclick="editar(\'' . $u . '\');return false;">Editar</a>';
                                                        $link.= '<button class="btn red" onclick="eliminar(' . $objLista->id . ', \'lista\');return false;">Eliminar</button>';
                                                        $link.= '<button class="btn blue" onclick="publicar(' . $objLista->id . ', \'lista\');return false;">Publicar</button>';
                                                        break;
                                                    case $this->config->item('estado:publicado'):
                                                        $u = 'admin/videos/grupo_maestro/' . $objLista->canales_id . '/' . $objLista->id;
                                                        $link = '<a href="#" class="mode mode_edit" onclick="editar(\'' . $u . '\');return false;">Editar</a>';
                                                        $link.= '<button class="btn red btnEliminar" onclick="eliminar(' . $objLista->id . ', \'lista\');return false;">Eliminar</button>';
                                                        break;
                                                    case $this->config->item('estado:eliminado'):
                                                        $u = 'admin/videos/grupo_maestro/' . $objLista->canales_id . '/' . $objLista->id;
                                                        //$link = '<button class="btn blue" onclick="publicar(' . $objLista->id . ', \'lista\');return false;">Publicar</button>';
                                                        $link = '<button class="btn green" onclick="restablecer(' . $objLista->id . ', \'lista\');return false;">Restablecer</button>';
                                                        break;
                                                endswitch;
                                                ?>                                                
                                                <td><div id="lista_boton_<?php echo $objLista->id; ?>"><?php echo $link; ?></div></td>
                                            </tr>                                            
                                        </table>
                                    </h3>
                                    <div class="video">
                                        <?php if (count($objLista->videos) > 0): ?>
                                            <?php foreach ($objLista->videos as $puntero_video => $objVideo): ?>
                                                <h3>
                                                    <table>
                                                        <tr id="rowvideo">
                                                            <td><?php echo ($puntero_video + 1); ?></td>
                                                            <td><img style="width:100px; height: 70px;" src="<?php echo $objVideo->imagen; ?>" /></td>
                                                            <td><?php echo $objVideo->titulo; ?></td>
                                                            <td><?php echo $objVideo->tipo; ?></td>
                                                            <td><?php echo $objVideo->cantidad; ?></td>
                                                            <td><?php echo $objVideo->categoria; ?></td>
                                                            <td><?php echo $objVideo->fecha_registro; ?></td>
                                                            <td><div id="video_<?php echo $objVideo->id; ?>"><?php echo $objVideo->estado; ?></div></td>
                                                            <?php
                                                            switch ($objVideo->estado_id):
                                                                case $this->config->item('estado:borrador'):
                                                                    $u = 'admin/videos/carga_unitaria/' . $objVideo->canales_id . '/' . $objVideo->id;
                                                                    $link = '<button class="btn orange" onclick="editar(\'' . $u . '\');return false;">Editar</button>';
                                                                    $link.= '<button class="btn red" onclick="eliminar(' . $objVideo->id . ', \'video\');return false;">Eliminar</button>';
                                                                    $link.= '<button class="btn blue" onclick="publicar(' . $objVideo->id . ', \'video\');return false;">Publicar</button>';
                                                                    break;
                                                                case $this->config->item('estado:publicado'):
                                                                    $u = 'admin/videos/carga_unitaria/' . $objVideo->canales_id . '/' . $objVideo->id;
                                                                    $link = '<button class="btn orange" onclick="editar(\'' . $u . '\');return false;">Editar</button>';
                                                                    $link.= '<button class="btn red btnEliminar" onclick="eliminar(' . $objVideo->id . ', \'video\');return false;">Eliminar</button>';
                                                                    break;
                                                                case $this->config->item('estado:eliminado'):
                                                                    $u = 'admin/videos/carga_unitaria/' . $objVideo->canales_id . '/' . $objVideo->id;
                                                                    //$link.= '<button class="btn blue" onclick="publicar(' . $objColeccion->id . ', \'video\');return false;">Publicar</button>';
                                                                    $link = '<button class="btn green" onclick="restablecer(' . $objVideo->id . ', \'video\');return false;">Restablecer</button>';
                                                                    break;
                                                            endswitch;
                                                            ?>                                                             
                                                            <td><div id="video_boton_<?php echo $objVideo->id; ?>"><?php echo $link; ?></div></td>
                                                        </tr>                                                        
                                                    </table>
                                                </h3>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<table>
    <tfoot>
        <tr class="nodrag">
            <td colspan="9">
                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
        </tr>
    </tfoot>    
</table>
<input type="hidden" name="canal" id="canal" value="<?php echo $canal_id; ?>" />

<script type="text/javascript">
    $(document).ready(function() {
        var altura = $(window).height();
        $(".bajada2").css('height', altura);
        $("#programas").accordion({
            active: false,
            autoHeight: false,
            collapsible: true
        });
        $(".coleccion").accordion({
            active: false,
            autoHeight: false,
            collapsible: true
        });
        $(".lista").accordion({
            active: false,
            autoHeight: false,
            collapsible: true
        });

        $('.mode').click(function(e) {
            e.stopPropagation();
            //Your Code here(For example a call to your function)
        });
    });
    function editar(url) {
        $(location).attr('href', url);
    }
    function eliminar(maestro_id, tipo) {
        jConfirm("Seguro que deseas eliminar este Item?", "Maestros", function(r) {
            if (r) {
                if (tipo == 'video') {
                    var post_url = "/admin/videos/eliminar_video/" + maestro_id;
                } else {
                    var post_url = "/admin/videos/eliminar_maestro/" + maestro_id;
                }
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            //location.reload();
                            $("#" + tipo + "_" + maestro_id).empty();
                            $("#" + tipo + "_" + maestro_id).html('Eliminado');
                            var htmlButton = '';
                            //htmlButton+='<button class="btn blue" onclick="publicar(' +maestro_id+ ', \''+tipo+'\');return false;">Publicar</button>';
                            htmlButton = '<button class="btn green" onclick="restablecer(' + maestro_id + ', \'' + tipo + '\');return false;">Restablecer</button>';
                            $("#" + tipo + "_boton_" + maestro_id).html(htmlButton);
                            $('.btn').click(function(e) {
                                e.stopPropagation();
                                //Your Code here(For example a call to your function)
                            });
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
    function restablecer(maestro_id, tipo) {
        jConfirm("Seguro que deseas restablecer este Item?", "Maestros", function(r) {
            if (r) {
                if (tipo == 'video') {
                    var post_url = "/admin/videos/restablecer_video/" + maestro_id;
                } else {
                    var post_url = "/admin/videos/restablecer_maestro/" + maestro_id;
                }
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            //location.reload();
                            $("#" + tipo + "_" + maestro_id).empty();
                            $("#" + tipo + "_" + maestro_id).html('Borrador');
                            var htmlButton = '';
                            var canal_id = $("#canal").val();
                            if (tipo == 'video') {
                                var url = 'admin/videos/carga_unitaria/' + canal_id + '/' + maestro_id;
                            } else {
                                var url = 'admin/videos/grupo_maestro/' + canal_id + '/' + maestro_id;
                            }
                            htmlButton += '<button class="btn orange" onclick="editar(\'' + url + '\');return false;">Editar</button>';
                            htmlButton += '<button class="btn red" onclick="eliminar(' + maestro_id + ', \'' + tipo + '\');return false;">Eliminar</button>';
                            htmlButton += '<button class="btn blue" onclick="publicar(' + maestro_id + ', \'' + tipo + '\');return false;">Publicar</button>';
                            $("#" + tipo + "_boton_" + maestro_id).html(htmlButton);
                            $('.btn').click(function(e) {
                                e.stopPropagation();
                                //Your Code here(For example a call to your function)
                            });
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
    function publicar(maestro_id, tipo) {
        jConfirm("Seguro que deseas publicar este Item?", "Maestros", function(r) {
            if (r) {
                if (tipo == 'video') {
                    var post_url = "/admin/videos/publicar_video/" + maestro_id;
                } else {
                    var post_url = "/admin/videos/publicar_maestro/" + maestro_id;
                }
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if (respuesta.value == 1) {
                            //location.reload();
                            $("#" + tipo + "_" + maestro_id).empty();
                            $("#" + tipo + "_" + maestro_id).html('Publicado');
                            var htmlButton = '';
                            var canal_id = $("#canal").val();
                            if (tipo == 'video') {
                                var url = 'admin/videos/carga_unitaria/' + canal_id + '/' + maestro_id;
                            } else {
                                var url = 'admin/videos/grupo_maestro/' + canal_id + '/' + maestro_id;
                            }
                            htmlButton += '<button class="btn orange" onclick="editar(\'' + url + '\');return false;">Editar</button>';
                            htmlButton += '<button class="btn red" onclick="eliminar(' + maestro_id + ', \'' + tipo + '\');return false;">Eliminar</button>';
                            $("#" + tipo + "_boton_" + maestro_id).html(htmlButton);
                            $('.btn').click(function(e) {
                                e.stopPropagation();
                                //Your Code here(For example a call to your function)
                            });
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
</script>