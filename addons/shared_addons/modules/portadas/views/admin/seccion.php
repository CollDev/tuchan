<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<section class="title">
    <h4><?php echo $title; ?></h4>
</section>
<section class="item">
    <?php
    $attributes = array('class' => 'frm', 'id' => 'frmSeccion', 'name' => 'frmSeccion');
    echo form_open_multipart('admin/portadas/guardar_seccion/' . $objSeccion->id, $attributes);
    ?>
    <div class="main_opt">

        <!-- titulo -->
        <label for="titulo"><?php echo lang('portadas:name'); ?> <span class="required">*</span></label>
        <?php
        $titulo = array(
            'name' => 'nombre',
            'id' => 'nombre',
            'value' => $objSeccion->nombre,
            'maxlength' => '100',
            'style' => 'width:556px;'
                //'readonly'=>'readonly'
        );
        echo form_input($titulo);
        ?>
        <!-- titulo -->
        <label for="descripcion"><?php echo lang('portadas:description'); ?> <span class="required">*</span></label>
        <?php
        $descripcion = array(
            'name' => 'descripcion',
            'id' => 'descripcion',
            'value' => $objSeccion->descripcion,
            'maxlength' => '100',
            'style' => 'width:556px;'
                //'readonly'=>'readonly'
        );
        echo form_input($descripcion);
        ?>  
        <!--        grilla de imagenes-->
<!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />-->
<!--        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>-->
<!--        <link rel="stylesheet" href="/resources/demos/style.css" />-->
        <style>
            #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
            #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
        </style>
        <script>
             $(document).ready(function() {
                $("#sortable").sortable();
                $("#sortable").disableSelection();
            });
        </script>

        <ul id="sortable">
            <li class="ui-state-default">1</li>
            <li class="ui-state-default">2</li>
        </ul>
        <?php echo form_close() ?>
</section>
