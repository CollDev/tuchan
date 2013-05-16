<table>
    <tr style="border: 0px;">
        <td colspan="2">
            <div><?php echo $breadcrumb; ?></div>
        </td>
    </tr>
    <tr>
        <td><div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div></td>
        <td><div style="text-align: right;" ><?php echo anchor('/admin/videos/grupo_maestro/'.$canal_id.'/0', 'Nuevo programa / coleccion', 'class="btn blue"') ?></div></td>        
    </tr>
</table>

<table id="table-1" class="table-list">
    <thead>
        <tr>
            <th style="width: 3%;">#</th>
            <th style="width: 10%;">Imagen</th>
            <th style="width: 20%;">nombre</th>
            <th style="width: 10%;">Tipo</th>
            <!--<th style="width: 10%;">Cantidad de items</th>-->
            <!--<th style="width: 10%;">Categoría</th>-->
            <!--<th style="width: 17%;">Fecha publicación</th>-->
            <th style="width: 10%;">estado</th>
            <th style="width: 10%;">Acciones</th>
        </tr>
    </thead>
    <tbody id="contenido">
        <?php if (count($lista_programas) > 0): ?>
            <?php foreach ($lista_programas as $puntero => $objPrograma): ?>
                <tr id="item_<?php echo $objPrograma->v ?>_<?php echo $objPrograma->id ?>">
                    <td></td>
                    <?php if ($objPrograma->procedencia == 1): ?>
                        <td class="collapse"><img style="width: 100px; height: 80px;" src="<?php echo $objPrograma->imagen ?>" border="0"></img></td>
                    <?php elseif ($objPrograma->procedencia == 0): ?>
                        <?php if(strlen(trim($objPrograma->imagen))== 0): ?>
                        <td class="collapse"><img style="width: 100px; height: 80px;" src="<?php echo $this->config->item('url:default_imagen') . 'no_video.jpg'; ?>" border="0"></img></td>
                        <?php else: ?>
                        <td class="collapse"><img style="width: 100px; height: 80px;" src="<?php echo $this->config->item('protocolo:http').$this->config->item('server:elemento') .'/'. $objPrograma->imagen ?>" border="0"></img></td>
                        <?php endif;?>
                    <?php else: ?>
                        <td class="collapse"><img style="width: 100px; height: 80px;" src="<?php echo $this->config->item('url:default_imagen') . 'no_video.jpg'; ?>" border="0"></img></td>
                    <?php endif; ?>
                        <td>
                            <?php if($objPrograma->tipo_grupo > 0): ?>
                                <?php echo anchor('/admin/videos/organizar/'.$canal_id.'/'.$objPrograma->id.'/'.$objPrograma->tipo_grupo, $objPrograma->nombre) ?></td>
                            <?php else: ?>
                                <?php echo $objPrograma->nombre ?>
                            <?php endif; ?>
                            
                    <td><?php echo lang('tipo:' . $objPrograma->tipo_grupo . '_maestro') ?></td>
                    <td id="estado_<?php echo $objPrograma->v ?>_<?php echo $objPrograma->id ?>"><?php echo lang('estado:' . ($objPrograma->estado - 1) . '_estado'); ?></td>
                    <?php 
                    $htmlAcciones ='';
                    if(($objPrograma->estado-1) == $this->config->item('estado:publicado')){
                        if($objPrograma->v == 'm'){
                            $htmlAcciones = anchor('/admin/videos/grupo_maestro/'.$canal_id.'/'.$objPrograma->id, lang('global:edit'), 'class="mode_edit"');
                        }else{
                            $htmlAcciones = anchor('/admin/videos/carga_unitaria/'.$canal_id.'/'.$objPrograma->id, lang('global:edit'), 'class="mode_edit"');
                            //$htmlAcciones.= anchor('/admin/canales/visualizar_video/' . $objPrograma->id, lang('global:preview'), 'class="mode_preview modal-large_custom" onclick="return false;"');
                        }
                        $htmlAcciones.= anchor('#', lang('global:delete'), 'class="mode_delete" onclick="eliminar('.$objPrograma->id.',\''.$objPrograma->v.'\');return false;"');
                        //$htmlAcciones.= anchor('#', lang('global:preview'), 'class="mode_preview"');
                    }else{
                        if(($objPrograma->estado-1) == $this->config->item('estado:borrador')){
                        if($objPrograma->v == 'm'){
                            $htmlAcciones = anchor('/admin/videos/grupo_maestro/'.$canal_id.'/'.$objPrograma->id, lang('global:edit'), 'class="mode_edit"');
                        }else{
                            $htmlAcciones = anchor('/admin/videos/carga_unitaria/'.$canal_id.'/'.$objPrograma->id, lang('global:edit'), 'class="mode_edit"');
                        }
                        $htmlAcciones.= anchor('#', lang('publish_label'), 'class="mode_publish" onclick="publicar('.$objPrograma->id.',\''.$objPrograma->v.'\');return false;"');                            
                        $htmlAcciones.= anchor('#', lang('global:delete'), 'class="mode_delete" onclick="eliminar('.$objPrograma->id.',\''.$objPrograma->v.'\');return false;"');
                        }
                    }
                    ?>
                    <td id="acciones_<?php echo $objPrograma->v ?>_<?php echo $objPrograma->id ?>"><?php echo $htmlAcciones; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

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
