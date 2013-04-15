<style>
    .gridster ul li{
        border: 1px solid #05B2D2;
    }
</style>
<fieldset style="height: 240px;">
    <legend>Items</legend>
    <div class="gridster">
        <ul>
            <?php
            $arrayDetalleSeccion = $objSeccion->detalle;
            if (count($arrayDetalleSeccion) > 0):
                ?>
                <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">
                    <img src="<?php echo $imagen_portada; ?>" style="width: 400px; height: 200px;" />
                </li>
                <!--        <li data-row="2" data-col="1" data-sizex="1" data-sizey="1"></li>
                        <li data-row="3" data-col="1" data-sizex="1" data-sizey="1"></li>
                
                        <li data-row="1" data-col="2" data-sizex="2" data-sizey="1"></li>
                        <li data-row="2" data-col="2" data-sizex="2" data-sizey="2"></li>
                
                        <li data-row="1" data-col="4" data-sizex="1" data-sizey="1"></li>
                        <li data-row="2" data-col="4" data-sizex="2" data-sizey="1"></li>
                        <li data-row="3" data-col="4" data-sizex="1" data-sizey="1"></li>
                
                        <li data-row="1" data-col="5" data-sizex="1" data-sizey="1"></li>
                        <li data-row="3" data-col="5" data-sizex="1" data-sizey="1"></li>
                
                        <li data-row="1" data-col="6" data-sizex="1" data-sizey="1"></li>
                        <li data-row="2" data-col="6" data-sizex="1" data-sizey="2"></li>-->
            <?php endif; ?>
        </ul>
    </div>
</fieldset>    
<br />
<!-- titulo -->
<label for="descripcion_portada"><?php echo lang('canales:descripcion_label_item'); ?> <span class="required">*</span></label>
<?php
$titulo = array(
    'name' => 'descripcion_portada',
    'id' => 'descripcion_portada',
    'value' => $objSeccion->detalle[0]->descripcion_item,
    'maxlength' => '100',
    'style' => 'width:556px;'
        //'readonly'=>'readonly'
);
echo form_input($titulo);
?>
<!-- fragmento -->
<br/>
<div style="width: 100%; text-align: right;">
    <?php echo form_dropdown('template', $templates, $objSeccion->templates_id); ?>            
</div>


<fieldset style="height: 540px;">
    <legend>Vista previa</legend>
    <img src="<?php echo $imagen_portada; ?>" />
</fieldset>
<br />
<a href="#" onclick="guardarPortada();return false;" class="btn orange" type="button"><?php echo lang('buttons.save'); ?></a>
<a href="#" class="btn orange" type="button"><?php echo lang('buttons.cancel'); ?></a>
<script type="text/javascript">
    $(function() { //DOM Ready

        $(".gridster ul").gridster({
            widget_margins: [10, 10],
            widget_base_dimensions: [400, 200],
            min_rows: 1,
            max_size_y: 1
        });

    });

    function guardarPortada() {
        var values = {};
        $.each($('#frmSeccion').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        var serializedData = $('#frmSeccion').serialize();    
        var post_url = "/admin/canales/actualizar_destacado/";
        var url = "admin/canales/portada/"+values['canal_id'];
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            data:serializedData,
            success: function(respuesta) //we're calling the response json array 'cities'
            {
                if(respuesta.value){

                    showMessage('exit', '<?php echo lang('seccion:success_saved') ?>', 2000, url);                    
                }
            } //end success
        }); //end AJAX  
    }
</script>