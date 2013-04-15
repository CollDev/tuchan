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