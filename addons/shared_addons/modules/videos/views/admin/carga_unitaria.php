<section class="title">
    <h4>
        <?php if ($canal->nombre) : ?>
            <?php echo $canal->nombre ?> | Carga Unitaria
        <?php endif; ?>
    </h4>
</section>

<section class="item">
    <!--FORM CARGA UNITARIA-->

    <form action="admin/videos" id="frm" name="frm" class="frm" enctype="multipart/form-data">
        <div class="left_arm">
            <label>Título</label>
            <input name="titulo" type="text" />
            <label>Video</label>
            <input name="video" type="file" />
            <label>Descripción</label>
            <textarea class="ckeditor" name="editor1" id="editor1"></textarea>
            <label>Fragmento</label>
            <select class="fragments" name="">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <label>Categoría</label>
            <select name="categoria">
                <option value="1">Categoría 01</option>
            </select>
            <label>Etiquetas Temáticas</label>
            <input name="tematicas" type="text" />
            <label>Etiquetas Personajes</label>
            <input name="personajes" type="text" />
            <label>Tipo</label>
            <select name="tipo">
                <option value="1">Tipo 01</option>
            </select>
        </div>
        <div class="right_arm">
            <label>Programa</label>
            <select name="programa">
                <option value="1">Programa 01</option>
            </select>
            <label>Colección</label>
            <select name="coleccion">
                <option value="1">Colección 1</option>
            </select>
            <div class="i_plus">
                <input class="h_text" name="" type="text" />
                <a href="#" class="plus_item btn blue" type="button">+ Añadir</a>
            </div>

            <label>Lista de Reproducción</label>
            <select name="lista_rep">
                <option value="1">Lista 01</option>
            </select>
            <div class="i_plus">
                <input class="h_text" name="" type="text" />
                <a href="#" class="plus_item btn blue" type="button">+ Añadir</a>
            </div>

            <label>Fuente</label>
            <select name="fuente">
                <option value="1">Fuente 01</option>
            </select>

            <label>Fecha de Publicación</label>
            Inicio
            <input type="text" class="selectedDateTime" />
            Fin
            <input type="text" class="selectedDateTime" />


            <label>Fecha de Transmisión</label>
            <input type="text" class="selectedDate" />

            <label>Horario de Transmisión</label>
            Incio
            <input type="text" class="selectedHour" />
            Fin
            <input type="text" class="selectedHour" />

            <label>Ubicación</label>
            <!--<div id="map_canvas" style="width:100%;height:400px;border:solid black 1px;"></div>
            <input type="text" value="37.7699298, -122.4469157" name="txt_latlng" id="txt_latlng" size="89%" disabled="disabled">-->

            <input name="ubicacion" type="text" />
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