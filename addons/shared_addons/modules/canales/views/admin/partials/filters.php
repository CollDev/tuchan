<fieldset id="filters">
    <legend><?php echo lang('global:filters'); ?></legend>
    <?php echo form_open('');
    echo form_hidden('f_module', $module_details['slug']); ?>
    <ul>
        <li>
            <?php //echo lang('user_active', 'f_active');
            //echo form_dropdown('f_active', array(0 => lang('global:select-all'), 1 => lang('global:yes'), 2 => lang('global:no') ), array(0)); ?>
        </li>
        <li>
            <?php if (isset($programa)) {
                //echo lang('user_group_label', 'f_group');
                echo form_dropdown('f_programa', array(0 => lang('global:select-all-programs')) + $programa);
            } ?>
        </li>
        <li>
            <?php if (isset($estados)) {
                //echo lang('user_group_label', 'f_group');
                echo form_dropdown('f_estado', array(0 => lang('global:select-all-status')) + $estados);
            } ?>
        </li>
        <li>
            <?php if (isset($tipo_item)) {
                //echo lang('user_group_label', 'f_group');
                echo form_dropdown('f_tipo', array(0 => lang('global:select-all-type')) + $tipo_item);
            } ?>
        </li>			
        <li><?php echo form_input('f_keywords'); ?></li>
        <li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
    </ul>
    <?php echo form_close(); ?>
</fieldset>