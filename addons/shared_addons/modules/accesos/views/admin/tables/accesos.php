<?php if ($canales) : ?>

    <?php echo form_open(''); ?>
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('accesos:nombre_label'); ?></th>
                <th><?php echo lang('global:estado'); ?></th>
                <th><?php echo lang('accesos:predeterminado_label'); ?></th>
            </tr>
        </thead>
        <!--<tfoot>
            <tr>
                <td colspan="7">
                    <div class="inner"><?php //$this->load->view('admin/partials/pagination'); ?></div>
                </td>
            </tr>
        </tfoot>-->
        <tbody>
            <?php  
            $checked = ""; 
            $checked2 = ""; ?>
            <?php foreach ($canales as $canal) : ?>            
                <?php foreach ($canales_asignados as $asignado) : ?>                                                   
                    <!-- Para el predeterminado -->
                    <?php if ($canal->id == $asignado->predeterminado) :
                        $checked2 = "checked";
                     else : 
                        $checked2 = "";
                    endif ?>          
                    
                    <!-- Canales asignados -->
                    <?php if ($canal->id == $asignado->canal_id && $asignado->estado==1) :
                        $checked = "checked";
                        break;
                    else : 
                        $checked = "";
                    endif ?> 
                <?php endforeach; ?>
                <tr>
                    <td><?php echo form_checkbox('action_to[]', $canal->id, $checked); ?></td>
                    <td class="collapse"><?php echo anchor('admin/canales/portada/' . $canal->id, $canal->nombre); ?></td>
                    <td class="collapse"><?php echo lang('global:'.$canal->estado.'_estado'); ?></td>
                    <td>
                       <?php echo form_radio('default[]', $canal->id, $checked2); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="buttons float-right padding-top">
        <button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
        <a href="<?php echo site_url('admin/accesos/index/' . $usuario_id); ?>" class="btn gray cancel"><?php echo lang('buttons.cancel'); ?></a>
    </div>
    
<?php else: ?>
    <div class="no_data"><?php echo lang('canales:no_data'); ?></div>
<?php endif; ?>
<?php echo form_close(); ?>