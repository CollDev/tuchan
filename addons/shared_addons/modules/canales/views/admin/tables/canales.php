<?php if ($canales) : ?>
    <table>
        <tr>
            <td colspan="7">
                <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
            </td>
            <td>
                <?php if ($this->session->userdata['group'] == 'admin'): ?>
                <div style="text-align: right;" >
                    <?php echo anchor('/admin/canales/canal/', lang('canales:new'), 'class="btn blue"') ?>
                </div>
                <?php endif; ?>
            </td>            
        </tr>    
    </table>
    <?php echo form_open(''); ?>
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th></th>
                <th><?php echo lang('canales:nombre_label'); ?></th>
                <th class="collapse"><?php echo lang('canales:descripcion_label'); ?></th>
                <th class="collapse"><?php echo lang('canales:estado_label'); ?></th>
                <th width="300"><?php echo lang('global:acciones'); ?></th>
                <?php if ($this->session->userdata['group'] == 'admin'): ?>
                    <th><?php echo lang('global:migracion'); ?></th>
                <?php endif; ?>
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
                <tr id="canal_<?php echo $post->id ?>">
                    <td><?php echo form_checkbox('action_to[]', $post->id); ?></td>
                    <td class="collapse"><img src="<?php echo $post->imagen_iso; ?>" /></td>
                    <td class="collapse"><?php echo anchor('admin/canales/videos/' . $post->id, $post->nombre); //$post->nombre;      ?></td>
                    <td class="collapse"><?php echo strip_tags($post->descripcion); ?></td>					
                    <td><div id="canal_estado_<?php echo $post->id; ?>"><?php echo lang('global:' . $post->estado . '_estado'); ?></div></td>
                    <?php
                    switch ($post->estado):
                        case $this->config->item('estado:borrador'):
                            $link = anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="mode_edit"');
                            $link.= anchor('/admin/canales/previsualizar_canal/' . $post->id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                            $link.= anchor('/admin/canales/portada/' . $post->id, lang('global:ver_portada'), 'class="mode_front"');
                            $link.= anchor('#', lang('global:1_estado'), 'class="mode_publish" onclick="publicar(' . $post->id . ',\'canal\');return false;"');
                            $link.= anchor('#', lang('global:delete'), 'class="mode_delete" onclick="eliminar(' . $post->id . ',\'canal\');return false;"');
                            break;
                        case $this->config->item('estado:publicado')://btn orange edit
                            $link = anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="mode_edit"');
                            $link.=anchor('/admin/canales/previsualizar_canal/' . $post->id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                            $link.= anchor('/admin/canales/portada/' . $post->id, lang('global:ver_portada'), 'class="mode_front"');
                            $link.= anchor('#', lang('global:delete'), 'class="mode_delete" onclick="eliminar(' . $post->id . ',\'canal\');return false;"');
                            break;
                        case $this->config->item('estado:eliminado'):
                            $link = anchor('/admin/canales/previsualizar_canal/' . $post->id, lang('global:preview'), 'target ="_blank" class="mode_preview modal-large"');
                            $link.= anchor('/admin/canales/portada/' . $post->id, lang('global:ver_portada'), 'class="mode_front"');
                            $link.= anchor('#', lang('global:restore'), 'class="mode_restore" onclick="restablecer(' . $post->id . ',\'canal\');return false;"');
                            break;
                    endswitch;
                    ?>
                    <td>
                        <?php //echo anchor('/admin/canales/canal/' . $post->id, lang('global:edit'), 'class="btn orange edit"'); ?>
                        <?php //echo anchor('', lang('global:delete'), array('class' => 'confirm btn red delete','onclick'=>' dispatch('.$post->id.');return false;')); ?>
                        <?php //echo anchor('', 'crear portadas', array('class' => 'btn red delete', 'onclick' => ' dispatch(' . $post->id . ');return false;')); ?>
                        <div id="canal_boton_<?php echo $post->id; ?>"><?php echo $link; ?></div>
                    </td>
                    <?php if ($this->session->userdata['group'] == 'admin'): ?>
                        <td>
<!--                            <a href="#" onclick="migrar_canal(<?php echo $post->id; ?>);return false;">
                                <img src="<?php //echo BASE_URL . 'system/cms/themes/pyrocms/img/Import24x24.png'; ?>" />
                            </a>-->
                            <a href="/admin/canales/importacion/<?php echo $post->id; ?>">
                                <img src="<?php echo BASE_URL . 'system/cms/themes/pyrocms/img/Import24x24.png'; ?>" />
                            </a>
                        </td>
                        <?php endif; ?>                    
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