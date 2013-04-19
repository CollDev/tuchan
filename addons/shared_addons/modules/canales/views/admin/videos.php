<section class="title" style="margin-left: 20px;padding-top:5px"> 
    <?php if ($canal) : ?>    
        <?php
//        if (isset($logo_canal[0])) {
//            $logo = PATH_ELEMENTOS . $logo_canal[0]->imagen;
//        } else {
//            $logo = '';
//        }
        ?>
<!--        <div class="logo_canal">
            <img src="<?php echo $logo ?>" />
        </div>
        <h4 style = "padding-left: 40px !important;">  
            <?php //echo $canal->nombre . '&nbsp;|&nbsp;' ?>
        </h4>-->
    <?php endif; ?> 
    <?php
    echo anchor('admin/videos/carga_unitaria/' . $canal->id, 'Carga unitaria', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo anchor('admin/videos/maestro/' . $canal->id, 'Organizar videos', array('class' => ''));
    echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
    echo anchor('admin/canales/portada/' . $canal->id, 'Portadas', array('class' => ''));
    ?>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php var_dump(template_partial('users')); ?>
    </div>     
</section>
<script type="text/javascript">
    $(document).ready(function() {
        var vista = 'Videos';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal->id; ?>/"+vista;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'html',
            //data:imagen_id,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                $(".subbar > .wrapper").html(respuesta);
            } //end success
        }); //end AJAX         
    });
</script>
<!--<script src="<?php //echo base_url("system/cms/themes/pyrocms/js/fix_channels.js")   ?>"></script>-->