<?php if ($maestros) : ?>
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
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('videos:imagen_label'); ?></th>
                <th><?php echo lang('videos:titulo_label'); ?></th>
                <th><?php echo lang('global:type'); ?></th>
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
            <?php foreach ($maestros as $video) : ?>
                <tr id="item_<?php echo $video->id; ?>">
                    <td><?php echo form_checkbox('action_to[]', $video->id); ?></td>
                    <td class="collapse"><img style="width: 100px;" src="<?php echo $video->imagen ?>" border="0"></img></td>
                    <td class="collapse"><?php echo $video->nombre; ?></td>
                    <td class="collapse"><?php echo lang('maestro:' . $video->tipo_grupo_maestro_id . '_maestro'); ?></td>
                    <?php
                    switch ($video->estado) :
                        case '0':
                            $estado = lang('estado:0_estado');
                            break;
                        case '1':
                            $estado = lang('estado:1_estado');
                            break;
                        case '2':
                            $estado = lang('estado:2_estado');
                            break;
                    endswitch;
                    ?>
                    <td class="collapse" id="video_<?php echo $video->id; ?>"><?php echo $estado ?></td>

                    <td>
                        <?php echo anchor('#', lang('global:edit'), 'class="mode_restore" onclick="restaurar_maestro(' . $video->id . ');return false;"'); ?>
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