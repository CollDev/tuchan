<section class="title">
    <h4><?php echo $module_details['name']; ?></h4>
</section>

<section class="item">
    <?php if ($canal) : ?>
        <?php echo $canal->nombre ?> | <?php echo $canal->descripcion ?>
    <?php endif; ?>
</section>

