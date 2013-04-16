<section class="title" style="margin-left: 20px;padding-top:5px"> 
    <?php if ($canal) : ?>    
        <?php 
        $logo = PATH_ELEMENTOS . $logo_canal[0]->imagen ?>
        <h4 style = "background: url('<?php echo $logo ?>') no-repeat 0px 5px; padding-left: 40px !important;">  
            <?php echo $canal->nombre ?>
        </h4>
    <?php endif; ?> 
    <?php
    echo anchor('admin/videos/carga_unitaria/' . $canal->id, 'Carga unitaria', array('class' => ''));
    echo '&nbsp;&nbsp;&nbsp;';
    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
    echo '&nbsp;&nbsp;&nbsp;';
    echo anchor('admin/videos/maestro/' . $canal->id, 'Organizar videos', array('class' => ''));
    ?>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php var_dump(template_partial('users')); ?>
    </div>     
</section>
<script src="<?php echo base_url("system/cms/themes/pyrocms/js/fix_channels.js") ?>"></script>