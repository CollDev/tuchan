<fieldset id="filters">

    <legend><?php echo lang('global:filters'); ?></legend>
    <?php echo form_open(''); ?>
    <?php echo form_hidden('f_module', $module_details['slug']); ?>
    <ul>
        <li>
            <?php //echo lang('user_active', 'f_active'); ?>
            <?php //echo form_dropdown('f_active', array(0 => lang('global:select-all'), 1 => lang('global:yes'), 2 => lang('global:no') ), array(0)); ?>
        </li>

        <!--			<li>
        <?php //if(isset($programa)){?>
        <?php //echo lang('user_group_label', 'f_group'); ?>
        <?php //echo form_dropdown('f_programa', array(0 => lang('global:select-programs')) + $programa); ?>
        <?php //} ?>
                                </li>-->
        <li>
            <?php if (isset($programa)) { ?>
                <?php //echo lang('user_group_label', 'f_group'); ?>
                <?php echo form_dropdown('f_programa', array(0 => lang('global:select-all-programs')) + $programa); ?>
            <?php } ?>
        </li>
        <li>
            <?php if (isset($estados)) { ?>
                <?php //echo lang('user_group_label', 'f_group'); ?>
                <?php echo form_dropdown('f_estado', array(0 => lang('global:select-all-status')) + $estados); ?>
            <?php } ?>
        </li>			
        <li><?php echo form_input('f_keywords'); ?></li>
        <li><?php echo anchor(current_url(), lang('buttons.cancel'), 'class="cancel"'); ?></li>
    </ul>
    <?php echo form_close(); ?>
</fieldset>