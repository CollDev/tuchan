<section class="title">
<<<<<<< HEAD
    <h4>Carga Masiva</h4>
</section>

<section class="item">
<!--FORM CARGA MASIVA-->
<form action="admin/videos" id="frm" name="frm" class="frm" enctype="multipart/form-data">
<div class="left_arm">
<div class="row_arm">
<!--    <div class="left_arm">-->
    <label>Titulo</label>
    <input name="" type="text" />
<!--    </div>-->
<!--    <div class="right_arm">-->
    <label>Fragmento</label>
    <select class="fragments" name="">
        <option>item 01</option>
    </select>
<!--    </div>-->
<label>Video</label>
<input name="" type="file" />


<label>Categoria</label>
<select name="">
	<option>item 01</option>
</select>
<label>Etiquetas Tematicas</label>
<input name="" type="text" />
<label>Etiquetas Personajes</label>
<input name="" type="text" />
<label>Tipo</label>
<select name="">
	<option>item 01</option>
</select>
<label>Descripcion</label>
<textarea class="ckeditor" name="editor1" id="editor1"></textarea>
</div>
</div>
<div class="right_arm">

<label>Fuente</label>
<select name="">
	<option>item 01</option>
</select>

<label>Ubicacion</label>
<!--<div id="map_canvas" style="width:100%;height:400px;border:solid black 1px;"></div>
<input type="text" value="37.7699298, -122.4469157" name="txt_latlng" id="txt_latlng" size="89%" disabled="disabled">-->

<input name="" type="text" />
</div>

<div class="main_opt">

<a href="javascript:document.frm.submit();" class="btn orange" type="button" >Guardar</a><a href="#" class="btn orange" type="button">Cancelar</a>
</div>

</form>
</section>

</section>
=======
    <h4><?php echo lang('videos:title_bulk_load') ?></h4>
</section>

<section class="item">
    <?php echo validation_errors(); ?>
    <?php
    $attributes = array('name' => 'formAddVideo', 'id' => 'formAddVideo');
    echo form_open('', $attributes);
    ?>
    <div id="infoFile" class="original">
        <label for="title"><?php echo lang('videos:title') ?></label> 
        <input type="text" name="titulo[]" id="titulo" /><br />
        <label for="title"><?php echo lang('videos:video') ?></label> 
        <input type="file" name="video[]" id="video" /><br />    
        <label for="text"><?php echo lang('videos:description') ?></label>
        <input type="text" name="descripcion[]" id="descripcion" /><br />
        <label for="text"><?php echo lang('videos:label_tematicas') ?></label>
        <input type="text" name="tematica[]" id="tematica" /><br />
        <label for="text"><?php echo lang('videos:label_personajes') ?></label>
        <input type="text" name="personaje[]" id="personaje" /><br />
        <label for="text">Fuentes</label>
        <select name="fuente"><option value="0"><?php echo lang('videos:source') ?></option></select><br />
        <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
        <input type="button" name="submit" onclick="adicionararchivo()" value="<?php echo lang('videos:add'); ?>" />
    </div>

</form>
<div id="infoFile" class="temporal" style="display:none;">
    <label for="title"><?php echo lang('videos:title') ?></label> 
    <input type="text" name="titulo[]" id="titulo" /><br />
    <label for="title"><?php echo lang('videos:video') ?></label> 
    <input type="file" name="video[]" id="video" /><br />    
    <label for="text"><?php echo lang('videos:description') ?></label>
    <input type="text" name="descripcion[]" id="descripcion" /><br />
    <label for="text"><?php echo lang('videos:label_tematicas') ?></label>
    <input type="text" name="tematica[]" id="tematica" /><br />
    <label for="text"><?php echo lang('videos:label_personajes') ?></label>
    <input type="text" name="personaje[]" id="personaje" /><br />
    <label for="text">Fuentes</label>
    <select name="fuente"><option value="0"><?php echo lang('videos:source') ?></option></select><br />
    <input type="hidden" name="canal_id" id="canal_id" value="<?php echo $canal_id; ?>" />
    <input type="button" name="submit" onclick="adicionararchivo()" value="<?php echo lang('videos:add'); ?>" />
</div>
<script type="text/javascript">
            function adicionararchivo() {
                //delete row no data
                if($('#row_no_data')){
                    $('#row_no_data').remove();
                }
                
                var values = {};
                $.each($('#formAddVideo').serializeArray(), function(i, field) {
                    values[field.name] = field.value;
                });
                var numRow = $('#listVideo >tbody >tr').length;
                var nameInputFile = 'inputFile_' + numRow;
                var row = 'r_' + numRow;
                var htmlRow = '<tr id="' + row + '"><td id="' + nameInputFile + '" class="align-center">';
                    htmlRow+='<input type="checkbox" name="action_to[]" id="groupVideo" value="'+numRow+'" />';
                    //htmlRow+='<?php //echo form_checkbox('action_to[]', 0); ?>';
                    htmlRow+='</td><td>' + values['titulo[]'] + '</td><td>Categoría</td><td>Programa</td><td>Colección</td><td>Lista de reproducción</td><td>' + values['tematica[]'] + '</td><td>' + values['personaje[]'] + '</td><td>Progreso</td><td class="actions"><a class="link" href="javascript:quitarVideo('+numRow+')"><?php echo lang("videos:quitar") ?></a></td></tr>';
                $('#listVideo > tbody:last').append(htmlRow);
                //clone and add new row in list video
                $('#infoFile').appendTo($('#' + nameInputFile));
                $('.temporal').clone().appendTo('#formAddVideo');
                $('#formAddVideo .temporal').css("display", "block");
                $('#formAddVideo .temporal').removeClass('temporal').addClass('original');
                $("#" + nameInputFile + " .original").css("display", "none");
                //reset form
                $('#formAddVideo').each(function() {
                    this.reset();
                });
            }

            function quitarVideo(position) {
                var row_id = 'r_' + position;
                $('#' + row_id).remove();
                var numRow = $('#listVideo >tbody >tr').length;
                if(numRow == 0){
                    var htmlRowNoData ='<tr id="row_no_data"><td  colspan="10"><div class="no_data"><?php echo lang("videos:no_items"); ?></div></td></tr>';
                    $('#listVideo > tbody:last').append(htmlRowNoData);
                }
            }


            $(function() {
                $('.checkAll').click(function() {
                    if ($(this).attr('checked')) {
                        $('input:checkbox').attr('checked', true);
                    }
                    else {
                        $('input:checkbox').attr('checked', false);
                    }
                });
            });

</script>
<?php
$attributes = array('target' => 'iframeUpload', 'name' => 'formUpload', 'id' => 'formUpload');
echo form_open_multipart('', $attributes);
?>
<table id="listVideo" class="table-list">
    <thead>
        <tr>
            <th class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
            <th><?php echo lang('videos:title') ?></th>
            <th><?php echo lang('videos:category') ?></th>
            <th><?php echo lang('videos:programme') ?></th>
            <th><?php echo lang('videos:collection') ?></th>
            <th><?php echo lang('videos:list_player') ?></th>
            <th><?php echo lang('videos:label_tematicas') ?></th>
            <th><?php echo lang('videos:label_personajes') ?></th>
            <th><?php echo lang('videos:progress') ?></th>
            <th class="actions"><?php echo lang('videos:action') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr id="row_no_data">
            <td  colspan="10">
                <div class="no_data">
                    <?php echo lang('videos:no_items'); ?>
                </div>                
            </td>
        </tr>
    </tbody>
</table>
<input type="submit" name="submit" value="<?php echo lang('buttons.save'); ?>" />
<input type="button" name="submit" value="<?php echo lang('buttons.delete'); ?>" />
<input type="button" name="submit" value="<?php echo lang('buttons.cancel'); ?>" />
<form>
    <script type="text/javascript">
        $(function() {
            $('#formUpload').submit(function(e) {
                e.preventDefault();
                $.ajaxFileUpload({
                    /*url         :'./upload/upload_file/',*/
                    url: './carga_masiva/',
                    secureuri: false,
                    fileElementId: 'video[0]',
                    dataType: 'json',
                    data: {
                        'title': 'loaddddd'
                    },
                    success: function(data, status)
                    {
                        if (data.status != 'error')
                        {
                            //$('#files').html('<p>Reloading files...</p>');
                            //refresh_files();
                            //$('#title').val('');
                        }
                        alert(data.msg);
                    }
                });
                return false;
            });
        });
    </script>
</section>
>>>>>>> 1201ee8a8121b87db20ea4af381bef22058262ef
