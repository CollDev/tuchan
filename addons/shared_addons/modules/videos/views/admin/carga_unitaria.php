<section class="title">
    <h4>
        <?php if ($canal->nombre) : ?>
            <?php echo $canal->nombre ?> | Carga Unitaria
        <?php endif; ?>
    </h4>
</section>

<section class="item">
    
    <!--FORM CARGA UNITARIA-->
    <?php 
    // Canales_id       
    $hidden = array('canal_id' => $canal->id);
        
    $attributes = array('class' => 'frm', 'id' => 'frm', 'name' => 'frm', 
                        'enctype' => 'multipart/form-data');
    echo form_open('admin/videos/carga_unitaria', $attributes, $hidden); ?>
    
        <div class="left_arm">
        
            <!-- titulo -->
            <label for="titulo">Título <span class="required">*</span></label>
            <?php
            $titulo = array(
                'name'        => 'titulo',
                'id'          => 'titulo',
                'value'       => set_value('titulo'),
                'maxlength'   => '100',
            );
            echo form_input($titulo);?>
                                                
            <!-- video -->
            <label for="video">Video <span class="required">*</span></label>
            <?php  $video = array('name'   => 'video');
            echo form_upload($video);?>

            <!-- descripcion -->
            <label for="descripcion">Descripción <span class="required">*</span></label>
            <?php
            $descripcion = array(
                'class' => 'ckeditor',
                'name'  => 'editor1',
                'id'    => 'editor1',
                'value' => set_value('editor1'),
            );
            echo form_textarea($descripcion);?>            

            <!-- fragmento -->
            <br/>
            <label for="fragmento">Fragmento</label>
            <?php echo form_error('fragmento'); ?><br />
            <?php
            $fragmento = array(
                  '1' => '1',
                  '2' => '2',
                  '3' => '3',
                  '4' => '4',
            );
            echo form_dropdown('fragmento', $fragmento);?>
            
            <!-- categoria -->
            <br/><br/>
            <label for="categoria">Categoría</label>
            <?php echo form_error('categoria'); ?><br />
            <?php
            $categoria = array(
                  '1' => 'Categoria 1'
            );
            echo form_dropdown('categoria', $categoria);?>
            
            <!-- tags tematicos -->
            <br/></br>
            <label for="tematicas">Etiquetas Temáticas <span class="required">*</span></label>
            <?php
            $tematicas = array(
                'name'        => 'tematicas',
                'id'          => 'tematicas',
                'value'       => set_value('tematicas'),
                'maxlength'   => '250',
            );
            echo form_input($tematicas);?>
           
            <!-- tags personajes -->
            <br/></br>
            <label for="personajes">Etiquetas Personajes <span class="required">*</span></label>
            <?php
            $personajes = array(
                'name'        => 'personajes',
                'id'          => 'personajes',
                'value'       => set_value('personajes'),
                'maxlength'   => '250',
            );
            echo form_input($personajes);?>
            
            <!-- tipo -->
            <br/></br>
            <label for="tipo">Tipo</label>
            <?php echo form_error('tipo'); ?><br/>
            <?php
            $tipo = array(
                  '1' => 'Tipo 1'
            );
            echo form_dropdown('tipo', $tipo);?>            
        </div>
    
        <div class="right_arm">
            
            <!-- programa -->
            <label for="programa">Programa</label>
            <?php echo form_error('programa'); ?><br/>
            <?php
            $programa= array(
                  '1' => 'Programa 1'
            );
            echo form_dropdown('programa', $programa);?>
                        
            <!-- coleccion -->
            <br/><br/>
            <label for="coleccion">Colección</label>
            <?php echo form_error('coleccion'); ?><br/>
            <?php
            $coleccion= array(
                  '1' => 'Colección 1'
            );
            echo form_dropdown('coleccion', $coleccion);?>
                  
            <!-- boton añadir -->
            <div class="i_plus">
                <?php
                echo form_input(array('class' => 'h_text'));                
                $attr = array('class' => 'plus_item btn blue', 'type' => 'button');
                echo anchor('#', '+ Añadir', $attr)
                ?>                
            </div>

            <!-- lista de reproducción -->
            <br/>
            <label for="lista_rep">Lista de Reproducción</label>
            <?php echo form_error('lista_rep'); ?><br/>
            <?php
            $lista_rep = array(
                  '1' => 'Lista 1'
            );
            echo form_dropdown('lista_rep', $lista_rep);?>
            
            <!-- botón añadir -->
            <div class="i_plus">
                <?php
                echo form_input(array('class' => 'h_text'));
                $attr = array('class' => 'plus_item btn blue', 'type' => 'button');
                echo anchor('#', '+ Añadir', $attr)
                ?>
            </div>

            <!-- fuente -->
            <br/>
            <label for="fuente">Fuente <span class="required">*</span></label>
            <?php
            $fuente = array(
                  '1' => 'Fuente 1'
            );
            echo form_dropdown('fuente', $fuente);?>
                        
            <!-- fecha de publicación -->
            <br/><br/>
            <label for="fecha_publicacion">Fecha de Publicación</label>
            Inicio
            <?php 
            $fec_pub_ini = array(
                'name'  => 'fec_pub_ini',
                'id'    => 'fec_pub_ini',
                'value' => set_value('fec_pub_ini'),
                'class' => 'selectedDateTime'
            );
            echo form_input($fec_pub_ini); 
            ?>
            
            Fin
            <?php 
            $fec_pub_fin = array(
                'name'  => 'fec_pub_fin',
                'id'    => 'fec_pub_fin',
                'value' => set_value('fec_pub_fin'),
                'class' => 'selectedDateTime'
            );
            echo form_input($fec_pub_fin); ?>

            <!-- fecha de transmisión -->
            <label for="fec_trans">Fecha de Transmisión</label>
            <?php 
            $fec_trans = array(
                'name'  => 'fec_trans',
                'id'    => 'fec_trans',
                'value' => set_value('fec_trans'),
                'class' => 'selectedDate'
            );
            echo form_input($fec_trans); ?>

            <!-- horario de tranmisión -->
            <label for="horario_transmision">Horario de Transmisión</label>
            Incio
            <?php 
            $hora_trans_ini = array(
                'name'  => 'hora_trans_ini',
                'id'    => 'hora_trans_ini',
                'value' => set_value('hora_trans_ini'),
                'class' => 'selectedHour'
            );
            echo form_input($hora_trans_ini); 
            ?>
            
            Fin
            <?php 
            $hora_trans_fin = array(
                'name'  => 'hora_trans_fin',
                'id'    => 'hora_trans_fin',
                'value' => set_value('hora_trans_fin'),
                'class' => 'selectedHour'
            );
            echo form_input($hora_trans_fin); 
            ?>

            <!-- ubicacion -->
            <label>Ubicación</label>
            <!--<div id="map_canvas" style="width:100%;height:400px;border:solid black 1px;"></div>
            <input type="text" value="37.7699298, -122.4469157" name="txt_latlng" id="txt_latlng" size="89%" disabled="disabled">-->
            <?php 
            $ubicacion = array(
                'name'  => 'ubicacion',
                'id'    => 'ubicacion',
                'value' => set_value('ubicacion'),
            );
            echo form_input($ubicacion); 
            ?>
        </div>
    
        <div class="main_opt">            
            <a href="javascript:document.frm.submit();" class="btn orange" type="button">Guardar</a>
            &nbsp;
            <?php 
            $attr = array('class' => 'btn orange', 'type' => 'button');
            echo anchor("#", 'Cancelar', $attr) 
            ?>
        </div>
        <script type="text/javascript" >

            //SETTING CONFIG SPANISH
            jQuery(function($){
                $.datepicker.regional['es'] = {
                    closeText: 'Cerrar',
                    prevText: '&#x3c;Ant',
                    nextText: 'Sig&#x3e;',
                    currentText: 'Hoy',
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
                    dateFormat: 'dd-mm-yy',
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
                    currentText: 'Hoy',
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
    <?php echo form_close() ?>
</section>