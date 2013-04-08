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
                        <td style="width: 10%;"><?php echo $objPrograma->estado; ?></td>
                        <td style="width: 10%;"><button class="btn red" onclick="editar('<?php echo 'admin/videos/grupo_maestro/' . $objPrograma->canales_id . '/' . $objPrograma->id ?>');return false;">Editar</button></td>
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
                                    <td><?php echo $objColeccion->estado; ?></td>
                                    <td><button class="btn red" onclick="editar('<?php echo 'admin/videos/grupo_maestro/' . $objColeccion->canales_id . '/' . $objColeccion->id ?>');return false;">Editar</button></td>
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
                                                <td><?php echo $objLista->estado; ?></td>
                                                <td><button class="btn red" onclick="editar('<?php echo 'admin/videos/grupo_maestro/' . $objLista->canales_id . '/' . $objLista->id . '/'; ?>');return false;">Editar</button></td>
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
                                                            <td><?php echo $objVideo->estado; ?></td>
                                                            <td><button class="btn red" onclick="editar('<?php echo 'admin/videos/carga_unitaria/' . $objVideo->canales_id . '/' . $objVideo->id; ?>');return false;">Editar</button></td>
                                                        </tr>                                                        
                                                    </table>
                                                </h3>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        <?php endforeach;?>
    <?php endif;?>
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
    });
    function editar(url){
        $(location).attr('href',url);
    }
</script>