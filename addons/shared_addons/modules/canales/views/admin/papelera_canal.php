<section class="title"> 
    <div style ="float: left;">
    </div>
    <div style="float: right;">
        <?php echo anchor('admin/canales/papelera/', 'Papelera', array('class' => '')); ?>
    </div>
</section>

<section class="item">
    <?php template_partial('filters'); ?>
    <div id="filter-stage">
        <?php template_partial('papeleras'); ?>
    </div> 
</section>
<script type="text/javascript">
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
    
    function eliminar(id, tipo){
        jConfirm("Seguro que deseas eliminar completamente este Item?", "Papelera", function(r) {
            if (r) {
                var post_url = "/admin/canales/eliminar_completamente/" + id+"/"+tipo;
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