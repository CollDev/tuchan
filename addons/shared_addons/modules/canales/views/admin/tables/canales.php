<?php if ($canales) : ?>

    <?php echo form_open(''); ?>
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('canales:nombre_label'); ?></th>
                <th class="collapse"><?php echo lang('canales:descripcion_label'); ?></th>
                <th class="collapse"><?php echo lang('canales:estado_label'); ?></th>
                <th width="180"></th>
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
            <?php foreach ($canales as $post) : ?>
                <tr>
                    <td><?php echo form_checkbox('action_to[]', $post->id); ?></td>
                    <td class="collapse"><?php echo anchor('admin/canales/portada/' . $post->id, $post->nombre); //$post->nombre;  ?></td>
                    <td class="collapse"><?php echo $post->descripcion; ?></td>					
                    <td><div id="canal_<?php echo $post->id ?>"><?php echo lang('canales:' . $post->estado . '_label'); ?></div></td>
                    <?php
                    switch ($post->estado):
                        case $this->config->item('estado:borrador'):
                            $link = anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="btn orange edit"');
                            $link.=anchor('#', lang('global:preview'), 'class="btn  edit"');
                            $link.=anchor('#', lang('global:1_estado'), 'class="btn blue edit"');
                            $link.=anchor('#', lang('global:delete'), 'class="btn red edit"');
                            break;
                        case $this->config->item('estado:publicado'):
                            $link = anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="btn orange edit"');
                            $link.=anchor('#', lang('global:preview'), 'class="btn silver edit"');
                            $link.=anchor('#', lang('global:delete'), 'class="btn red edit"');
                            break;
                        case $this->config->item('estado:eliminado'):
                            $link.=anchor('#', lang('global:preview'), 'class="btn silver edit"');
                            $link.=anchor('#', lang('global:restore'), 'class="btn blue edit"');
                            break;
                    endswitch;
                    ?>
                    <td>
                        <?php //echo anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="btn orange edit"'); ?>
                        <?php //echo anchor('', lang('global:delete'), array('class' => 'confirm btn red delete','onclick'=>' dispatch('.$post->id.');return false;')); ?>
                        <?php //echo anchor('', 'crear portadas', array('class' => 'btn red delete', 'onclick' => ' dispatch(' . $post->id . ');return false;')); ?>
                        <?php echo $link; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="table_action_buttons">            
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))); ?>            
    </div>
<?php else: ?>
    <div class="no_data"><?php echo lang('canales:no_data'); ?></div>
<?php endif; ?>
<?php echo form_close(); ?>