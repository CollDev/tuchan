<section class="title">
    <h4><?php echo lang('maestros:organizar_videos') ?></h4>
</section>
<section class="bar" style="width: 100%;">
    <div style="text-align: right;" >
        <?php echo anchor('/admin/videos/grupo_maestro/'.$canal_id.'/0', 'Nuevo', 'class="btn blue"') ?>
    </div>
</section>
<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('maestros'); ?>
    </div>
</section>