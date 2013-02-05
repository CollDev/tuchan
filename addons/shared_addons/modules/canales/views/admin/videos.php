<section class="title">
    <h4>
        <?php if ($canal) : ?>
            <?php echo $canal->nombre ?> | <?php echo $canal->descripcion ?>
        <?php endif; ?>
    </h4>
</section>

<section>
    <?php     
        echo anchor('admin/canales/carga_unitaria', 'Carga unitaria', array('class' => ''));  
        echo '&nbsp;&nbsp;&nbsp;';
        echo anchor('admin/canales/carga_masiva', 'Carga masiva', array('class' => ''));
    ?>
</section>

<section class="item">
    <?php if ($lista_videos) : ?>
        <?php echo form_open('admin/videos/action'); ?>
         <table border="0" class="table-list">
            <thead>
                <tr>
                    <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                    <th><?php echo lang('videos:imagen_label'); ?></th>
                    <th><?php echo lang('videos:titulo_label'); ?></th>
                    <th><?php echo lang('videos:descripcion_label'); ?></th>
                    <th><?php echo lang('videos:categoria_label'); ?></th>
                    <th><?php echo lang('videos:tipo_label'); ?></th>
                    <th><?php echo lang('videos:programa_label'); ?></th>
                    <th><?php echo lang('videos:coleccion_label'); ?></th>
                    <th><?php echo lang('videos:lista_reprod_label'); ?></th>
                    <th><?php echo lang('videos:fragmento_label'); ?></th>
                    <th><?php echo lang('videos:fuente_label'); ?></th>
                    <th><?php echo lang('videos:etiquetas_tematicas_label'); ?></th>
                    <th><?php echo lang('videos:etiquetas_personajes_label'); ?></th>
                    <th><?php echo lang('videos:duracion_label'); ?></th>
                    <th><?php echo lang('videos:fecha_subida_label'); ?></th>
                    <th><?php echo lang('videos:fecha_publicacion_label'); ?></th>
                    <th><?php echo lang('videos:fecha_transmision_label'); ?></th>
                    <th><?php echo lang('videos:horario_transmision_label'); ?></th>
                    <th><?php echo lang('videos:ubicacion_label'); ?></th>
                    <th><?php echo lang('videos:tamanio_label'); ?></th>
                    <th><?php echo lang('global:estado_label'); ?></th>
                    <th class="collapse"><?php echo lang('videos:estado_label'); ?></th>
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
                <?php foreach ($lista_videos as $video) : ?>
                    <tr>
                        <td><?php echo form_checkbox('action_to[]', $video->id); ?></td>
                        <td class="collapse"><?php echo $video->nombre; ?></td>
                        <td class="collapse"><?php echo $video->descripcion; ?></td>					
                        <td><?php echo lang('videos:' . $video->status . '_label'); ?></td>
                        <td>

                            <?php if ($video->status == '1') : ?>
                                <?php //echo anchor('blog/' . date('Y/m', '2013') . '/29'. $video->nombre, lang('global:view'), 'class="btn green" target="_blank"');?>
                            <?php else: ?>
                                <?php echo anchor('', lang('global:preview'), 'class="btn green" target="_blank"');?>
                            <?php endif; ?>
                            <?php echo anchor('admin/videos/edit/' . $video->id, lang('global:edit'), 'class="btn orange edit"'); ?>
                            <?php echo anchor('admin/videos/delete/' . $video->id, lang('global:delete'), array('class' => 'confirm btn red delete')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="table_action_buttons">            
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))); ?>            
	</div>
    <?php endif; ?>
    <?php echo form_close(); ?>  
</section>

