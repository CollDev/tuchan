<section class="title"> 
    <div style ="float: left;">
        <?php
        echo anchor('admin/videos/carga_unitaria/' . $canal->id, $this->config->item('submenu:carga_unitaria'), array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        /*    echo anchor('admin/videos/carga_masiva/' . $canal->id, 'Carga masiva', array('class' => ''));
          echo '&nbsp;&nbsp;|&nbsp;&nbsp;'; */
        echo anchor('admin/videos/maestro/' . $canal->id, 'Organizar videos', array('class' => ''));
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
        echo anchor('admin/canales/portada/' . $canal->id, 'Portadas', array('class' => ''));
        ?>        
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/' . $canal->id, 'Papelera', array('class' => '')); ?>
    </div>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('papeleras'); ?>
    </div> 
</section>
<script type="text/javascript">
    $(document).ready(function() {
        mostrar_titulo();
    });
    function mostrar_titulo() {
        var vista = 'papelera';
        var post_url = "/admin/canales/mostrar_titulo/<?php echo $canal->id; ?>/" + vista;
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
    }

    function restaurar_maestro(id, tipo) {
        jConfirm("Seguro que deseas restaurar este Item?", "Papelera", function(r) {
            if (r) {
                var post_url = "/admin/canales/restaurar/" + id+"/"+tipo;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: 'json',
                    //data: indexOrder,
                    success: function(respuesta)
                    {
                        if(respuesta.value == 1){
                            $("#"+tipo+"_"+id).empty();
                        }
                    } //end success
                }); //end AJAX   
            }
        });
    }
</script>