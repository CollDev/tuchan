<section class="title">
    <div>
        <ul class="main_menu">
            <li class="alast"></li>
            <li class="last">
        <?php echo anchor('admin/canales/papelera/', 'Papelera', array('class' => '')); ?>
            </li>
        </ul>
    </div>
</section>
<script type="text/javascript">
    var ul_width = parseInt($('section.title div ul.main_menu').css('width'));
    var lilast_pos = $('section.title div ul.main_menu li.last').position();
 
    var anew_width = ul_width - lilast_pos.left;
    $('section.title div ul.main_menu li.alast').css('width',anew_width);
</script>
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