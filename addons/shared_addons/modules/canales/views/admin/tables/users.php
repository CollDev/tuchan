    <?php 
    if ($lista_videos) : ?>
<table>
    <tr>
        <td>
            <div style="clear: both; width: 100%;"><?php $this->load->view('admin/partials/pagination'); ?></div><br /><br />
        </td>
    </tr>
</table>
        <?php echo form_open('canales/videos/action'); ?>
         <table border="0" class="table-list">
            <thead>
                <tr>
                    <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                    <th><?php echo lang('videos:imagen_label'); ?></th>
                    <th><?php echo lang('videos:titulo_label'); ?></th>
                    <th><?php echo lang('videos:categoria_label'); ?></th>
                    <th><?php echo lang('videos:programa_label'); ?></th>
<!--                    <th><?php //echo lang('videos:fuente_label'); ?></th>-->
<!--                    <th><?php //echo lang('videos:etiquetas_tematicas_label'); ?></th>
                    <th><?php //echo lang('videos:etiquetas_personajes_label'); ?></th>-->
                    <th><?php echo lang('videos:fecha_subida_label'); ?></th>
<!--                    <th><?php //echo lang('videos:fecha_publicacion_inicio_label'); ?></th>
                    <th><?php //echo lang('videos:fecha_publicacion_fin_label'); ?></th>-->
                    <th><?php echo lang('videos:fecha_transmision_label'); ?></th>
<!--                    <th><?php //echo lang('videos:horario_transmision_inicio_label'); ?></th>
                    <th><?php //echo lang('videos:horario_transmision_fin_label'); ?></th>-->
                    <th><?php echo lang('global:estado'); ?></th>                    
                    <th width="180">Acciones</th>
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
                        <?php if($video->procedencia == '0'): ?>
                            <td class="collapse"><img style="width: 100px;" src="<?php echo $video->imagen ?>" border="0"></img></td>
                        <?php elseif($video->procedencia == '1'): ?>
                            <td class="collapse"><img style="width: 100px;" src="<?php echo $this->config->item('servidor:elemento').$video->imagen ?>" border="0"></img></td>
                        <?php else: ?>
                            <td class="collapse"><img style="width: 100px;" src="<?php echo BASE_URL.UPLOAD_IMAGENES_VIDEOS.'no_video.jpg';?>" border="0"></img></td>
                        <?php endif; ?>
                        <td class="collapse"><?php echo $video->titulo; ?></td>
                        <td class="collapse"><?php echo $video->nombre_categoria; ?></td>
                        <td class="collapse"><?php echo $video->programa; ?></td>
<!--                        <td class="collapse"><?php //echo $video->nombre_canal; ?></td>-->
<!--                        <td class="collapse"><?php //echo $video->tematico; ?></td>
                        <td class="collapse"><?php //echo $video->personaje; ?></td>-->
                        <td class="collapse"><?php echo $video->fecha_registro; ?></td>
<!--                        <td class="collapse"><?php //echo $video->fecha_publicacion_inicio; ?></td>
                        <td class="collapse"><?php //echo $video->fecha_publicacion_fin; ?></td>-->
                        <td class="collapse"><?php echo $video->fecha_transmision; ?></td>
<!--                        <td class="collapse"><?php //echo $video->horario_transmision_inicio; ?></td>
                        <td class="collapse"><?php //echo $video->horario_transmision_fin; ?></td>-->
                        
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
                            <?php //echo anchor('admin/videos/edit/' . $video->id, lang('global:edit'), 'class="btn orange edit"'); ?>
                            <?php echo anchor('/admin/videos/carga_unitaria/'. $canal->id . '/' . $video->id . '/', lang('global:edit'), 'class="btn orange edit"'); ?>
                            <?php echo anchor('/admin/videos/corte_video/'. $canal->id . '/' . $video->id . '/', lang('global:cortar'), 'class="btn orange edit"'); ?>                            
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