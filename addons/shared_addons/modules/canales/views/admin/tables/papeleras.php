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
                <tr id="<?php echo $video->maestros; ?>_<?php echo $video->id; ?>">
                    <td class="collapse"><img style="width: 100px;" src="<?php echo $video->imagen ?>" border="0"></img></td>
                    <td class="collapse"><?php echo $video->titulo; ?></td>
                    <td class="collapse"><?php echo lang('maestro:' . $video->tipo_maestro . '_maestro'); ?></td>
                    <td class="collapse" id="video_<?php echo $video->id; ?>"><?php echo lang('estado:'.($video->estado-1).'_estado') ?></td>
                    <td>
                        <?php echo anchor('#', lang('global:restore'), 'title="'.lang('global:restore').'" class="mode_restore" onclick="restaurar_maestro(' . $video->id . ', \''.$video->maestros.'\');return false;"'); ?>
                        <?php echo anchor('#', lang('global:delete'), 'title="'.lang('global:delete').'" class="mode_delete" onclick="eliminar(' . $video->id . ', \''.$video->maestros.'\');return false;"'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="table_action_buttons">            
    </div>
<?php else: ?>
    <?php echo lang('global:no_data') ?>
<?php endif; ?>
<?php echo form_close(); ?> 