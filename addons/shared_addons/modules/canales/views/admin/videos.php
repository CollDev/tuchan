<section class="title">
    <h4 class="america_tv">
        <?php if ($canal) : ?>
            <?php echo $canal->nombre ?> | <?php echo $canal->descripcion ?>
        <?php endif; ?>
    </h4>
</section>

<section>
    <?php     
        echo anchor('admin/videos/carga_unitaria/' . $canal->id, 'Carga unitaria', array('class' => ''));  
        echo '&nbsp;&nbsp;&nbsp;';
        echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
    ?>
</section>

<section class="item">
    <?php if ($lista_videos) : ?>
        <?php echo form_open('canales/videos/action'); ?>
         <table border="0" class="table-list">
            <thead>
                <tr>
                    <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                    <th><?php echo lang('videos:imagen_label'); ?></th>
                    <th><?php echo lang('videos:titulo_label'); ?></th>
                    <th><?php echo lang('videos:categoria_label'); ?></th>
                    <th><?php echo lang('videos:programa_label'); ?></th>
                    <th><?php echo lang('videos:fuente_label'); ?></th>
                    <th><?php echo lang('videos:etiquetas_tematicas_label'); ?></th>
                    <th><?php echo lang('videos:etiquetas_personajes_label'); ?></th>
                    <th><?php echo lang('videos:fecha_subida_label'); ?></th>
                    <th><?php echo lang('videos:fecha_publicacion_label'); ?></th>
                    <th><?php echo lang('videos:fecha_transmision_label'); ?></th>
                    <th><?php echo lang('videos:horario_transmision_label'); ?></th>
                    <th><?php echo lang('global:estado'); ?></th>                    
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
                        <td class="collapse"><img src="<?php echo $this->config->item('videos:imagenes') . $video->imagen ?>" border="0"></img></td>
                        <td class="collapse"><?php echo $video->titulo; ?></td>
                        <td class="collapse"><?php echo $video->categoria; ?></td>
                        <td class="collapse"><?php echo 'programa'; ?></td>
                        <td class="collapse"><?php echo $video->fuente; ?></td>
                        <td class="collapse"><?php echo $video->tematico; ?></td>
                        <td class="collapse"><?php echo $video->personaje; ?></td>
                        <td class="collapse"><?php echo $video->fecha_registro; ?></td>
                        <td class="collapse"><?php echo $video->fecha_publicacion; ?></td>
                        <td class="collapse"><?php echo $video->fecha_transmision; ?></td>
                        <td class="collapse"><?php echo $video->horario_transmision; ?></td>
                        
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
                        endswitch; ?>
                        <td class="collapse"><?php echo $estado ?></td>
                        
                        <td>

                            <?php if ($video->estado == '1') : ?>                                
                                <?php echo anchor('', lang('global:preview'), 'class="btn green" target="_blank"');?>
                            <?php else: ?>
                                <?php //echo anchor('blog/' . date('Y/m', '2013') . '/29'. $video->nombre, lang('global:view'), 'class="btn green" target="_blank"');?>
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
    <?php else: ?>
        <?php echo lang('global:no_data') ?>
    <?php endif; ?>
    <?php echo form_close(); ?>  
</section>

