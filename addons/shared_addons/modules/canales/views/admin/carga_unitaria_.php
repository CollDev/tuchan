<section class="title">
    <h4>Carga Unitaria</h4>
</section>

<section class="item">
<!--FORM CARGA UNITARIA-->
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<!--<script src="http://textextjs.com/js/jquery-1.8.2.js" type="text/javascript"></script>-->
<script src="http://textextjs.com/js/doc.js" type="text/javascript"></script>
<script src="http://bundlejs.com/alexgorbatchev/products/textext/1.3.1?compress=true&bundles=core,ajax,arrow,autocomplete,filter,focus,prompt,suggestions,tags" type="text/javascript"></script>
<script type="text/javascript">
    $('#textarea').textext({
        plugins : 'tags prompt focus autocomplete ajax arrow',
        tagsItems : [ 'Basic', 'JavaScript', 'PHP', 'Scala' ],
        prompt : 'Añadir Mas...',
        ajax : {
            url : 'data.json',
            dataType : 'json',
            cacheResults : true
        }
    });
</script>
<form action="admin/videos" id="frm" name="frm" class="frm" enctype="multipart/form-data">
<div class="left_arm">
<div>
    <div class="left_arm">
    <label>Titulo</label>
    <input name="" type="text" />
    </div>
    <div class="right_arm">
    <label>Fragmento</label>
    <select class="fragments" name="">
        <option>item 01</option>
    </select>
    </div>
</div>
<div class="row_arm">
<label>Video</label>
<input name="" type="file" />
<label>Descripcion</label>
<textarea class="ckeditor" name="editor1" id="editor1"></textarea>

<label>Categoria</label>
<select name="">
	<option>item 01</option>
</select>
<label>Etiquetas Tematicas</label>
ahaajaajaj
<textarea id="textarea" class="example" rows="1" style="width: 400px;">
</textarea>	
<!--<input name="" type="text" />-->
<label>Etiquetas Personajes</label>
<input name="" type="text" />
<label>Tipo</label>
<select name="">
	<option>item 01</option>
</select>
</div>
</div>
<div class="right_arm">
<label>Programa</label>
<select name="">
	<option>item 01</option>
</select>
<label>Coleccion</label>
<select name="">
	<option>item 01</option>
</select>
<div class="i_plus">
<input class="h_text" name="" type="text" />
<a href="#" class="plus_item btn blue" type="button">+ Añadir</a>
</div>

<label>Lista de Reproduccion</label>
<select name="">
	<option>item 01</option>
</select>
<div class="i_plus">
<input class="h_text" name="" type="text" />
<a href="#" class="plus_item btn blue" type="button">+ Añadir</a>
</div>

<label>Fuente</label>
<select name="">
	<option>item 01</option>
</select>

<label>Fecha de Publicacion</label>
Inicio
<input type="text" class="selectedDateTime" />
Fin
<input type="text" class="selectedDateTime" />


<label>Fecha de Transmision</label>
<input type="text" class="selectedDate" />

<label>Horario de Transmision</label>
Incio
<input type="text" class="selectedHour" />
Fin
<input type="text" class="selectedHour" />

<label>Ubicacion</label>
<!--<div id="map_canvas" style="width:100%;height:400px;border:solid black 1px;"></div>
<input type="text" value="37.7699298, -122.4469157" name="txt_latlng" id="txt_latlng" size="89%" disabled="disabled">-->

<input name="" type="text" />
</div>

<div class="main_opt">

<a href="javascript:document.frm.submit();" class="btn orange" type="button" >Guardar</a><a href="#" class="btn orange" type="button">Cancelar</a>
</div>
<script type="text/javascript" >


//SETTING CONFIG SPANISH
jQuery(function($){
        $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '&#x3c;Ant',
                nextText: 'Sig&#x3e;',
                currentText: 'Ahora mismo',
				timeText: 'Hora',
				hourText: 'Hrs.',
				minuteText: 'Min.',
				secondText: 'Seg.',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                'Jul','Ago','Sep','Oct','Nov','Dic'],
                dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
                dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['es']);
});


jQuery(function($){
        $.timepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '&#x3c;Ant',
                nextText: 'Sig&#x3e;',
				timeOnlyTitle: 'Elige la hora',
                currentText: 'Ahora mismo',
				timeText: 'Hora',
				hourText: 'Hrs.',
				minuteText: 'Min.',
				secondText: 'Seg.'};
        $.timepicker.setDefaults($.timepicker.regional['es']);
});


$(function() { 
$('.selectedDateTime').datetimepicker($.datepicker.regional['es']); 
$('.selectedDate' ).datepicker();
$('.selectedHour').timepicker($.datepicker.regional['es']);


});


</script>
</form>
</section>