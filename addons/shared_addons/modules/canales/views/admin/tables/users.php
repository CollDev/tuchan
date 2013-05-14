<?php if ($lista_videos) : ?>
    <table>
        <tr>
            <td>
                <div style="clear: both; width: 100%;"><?php $this->load->view('admin/partials/pagination'); ?></div><br /><br />
            </td>
        </tr>
    </table>
    <?php
    $attributes = array('class' => 'frm', 'id' => 'formListaVideo', 'name' => 'formListaVideo');
    echo form_open('canales/videos/action', $attributes);
    
    ?>
<input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal->id; ?>" />
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('videos:imagen_label'); ?></th>
                <th><?php echo lang('videos:titulo_label'); ?></th>
                <th><?php echo lang('videos:categoria_label'); ?></th>
                <th><?php echo lang('videos:programa_label'); ?></th>
                <th><?php echo lang('videos:fecha_subida_label'); ?></th>
                <th><?php echo lang('videos:fecha_transmision_label'); ?></th>
                <th><?php echo lang('global:estado'); ?></th>                    
                <th width="180"><?php echo lang('global:acciones'); ?></th>                    
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="7">
                    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                </td>
            </tr>
        </tfoot>
        <tbody>                
            <?php foreach ($lista_videos as $video) : ?>
                <tr id="item_<?php echo $video->id; ?>">
                    <td><?php echo form_checkbox('action_to[]', $video->id); ?></td>
                    <?php if ($video->procedencia == '0'): ?>
                        <td class="collapse"><img style="width: 100px;" src="<?php echo $video->imagen ?>" border="0"></img></td>
                    <?php elseif ($video->procedencia == '1'): ?>
                        <td class="collapse"><img style="width: 100px;" src="<?php echo $this->config->item('servidor:elemento') . $video->imagen ?>" border="0"></img></td>
                    <?php else: ?>
                        <td class="collapse"><img style="width: 100px;" src="<?php echo $this->config->item('url:default_imagen') . 'no_video.jpg'; ?>" border="0"></img></td>
                    <?php endif; ?>
                    <td class="collapse"><input type="hidden" name="codvideo_<?php echo $video->id ?>" id="codvideo_<?php echo $video->id ?>" value="<?php echo $video->id ?>" /><?php echo $video->nombre; ?></td>
                    <td class="collapse"><?php echo $video->categoria; ?></td>
                    <td class="collapse"><?php echo $video->gm3_nom; ?></td>
                    <td class="collapse"><?php echo $video->fecha_registro; ?></td>
                    <td class="collapse"><?php echo $video->fecha_transmision; ?></td>
                    <?php
                    switch ($video->estado) :
                        case '0':
                            $estado = lang('videos:0_estado');
                            break;
                        case '1':
                            $estado = lang('videos:1_estado');
                            break;
                        case '2':
                            $estado = lang('videos:2_estado');
                            break;
                        case '3':
                            $estado = lang('videos:3_estado');
                            break;
                    endswitch;
                    ?>
                    <td class="collapse" id="video_<?php echo $video->id; ?>"><?php echo $estado ?></td>

                    <td id="accion_<?php echo $video->id; ?>">

                        <?php if ($video->estado == 1 || $video->estado == 2) : ?>                                
                            <?php echo anchor('/admin/canales/visualizar_video/' . $video->id, lang('global:preview'), 'class="mode_preview modal-large_custom" onclick="return false;"'); ?>
                            <?php echo anchor('/admin/videos/corte_video/' . $canal->id . '/' . $video->id . '/', lang('global:cortar'), 'class="mode_cut"'); ?>
                        <?php endif; ?>
                        <?php if($video->estado == $this->config->item('video:borrador')): ?>
                            <?php echo anchor('#', lang('publish_label'), 'class="mode_publish" onclick="publicar_video(' . $video->id . ');return false;"'); ?>
                        <?php endif; ?>
                        <?php echo anchor('/admin/videos/carga_unitaria/' . $canal->id . '/' . $video->id . '/', lang('global:edit'), 'class="mode_edit"'); ?>
                        <?php echo anchor('#', lang('global:delete'), 'onclick="eliminar_video(' . $video->id . ');return false;" class="mode_delete"'); ?>

                    </td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <div class="table_action_buttons">            
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('publish'))); ?>            
    </div>
<?php else: ?>
    <?php echo lang('global:no_data') ?>
<?php endif; ?>
<?php echo form_close(); ?> 