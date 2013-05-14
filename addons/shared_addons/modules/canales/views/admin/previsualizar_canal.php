<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/custom.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/mediaquerie.css') ?>" rel="stylesheet" />

<div class="container-main">
    <div class="wrapper-main">
        <div class="mc_column mc_columnA ">
            <div class="mc_column mc_columnE mc_mbottom  mc_mright">  
                <div class="mc_colum mc_columnA player_video_main3">
                    <div class="flexslider">
                        <ul class="slides">
                            <li>
                                <div class="content_section3">
                                    <div class="layer_content">
                                        <?php
                                        $objColeccionSeccion = $objPortada->secciones;
                                        $titulo = '';
                                        ?>
                                        <?php if (count($objColeccionSeccion) > 0): ?>
                                            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                                                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:destacado')): ?>
                                                    <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                                                    <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                                        <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                                            <img src="<?php echo $objDetalleSeccion->imagen; ?>" title="Insensato Corazón" alt="Insensato Corazón">
                                                            <div class="mode_fade">
                                                                <a href="#">
                                                                    <div class="layer_info">
                                                                        <div class="data_info down_place4">
                                                                            <span class="span_text2"></span>
                                                                            <h5>
                                                                                <?php
                                                                                    $objMaestro = $objDetalleSeccion->objMaestro;
                                                                                    if(count($objMaestro)>0){
                                                                                        echo $objMaestro->nombre;
                                                                                    }
                                                                                ?>
                                                                            </h5>
                                                                            <span class="span_text2"></span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <?php break; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <img style="width:820px; height: 400px;" src="<?php echo $this->config->item('url:portada'); ?>" title="Insensato Corazón" alt="Insensato Corazón">
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <img style="width:820px; height: 400px;" src="<?php echo $this->config->item('url:portada'); ?>" title="Insensato Corazón" alt="Insensato Corazón">
                                        <?php endif; ?>
                                        <div class="mode_fade">
                                            <a href="#">
                                                <div class="layer_info">
                                                    <div class="data_info down_place4">
                                                        <span class="span_text2"></span> 
                                                        <h5><?php echo $titulo; ?></h5>
                                                        <span class="span_text2"></span>
                                                    </div>
                                                </div>

                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="mc_column mc_columnF">
                <div class="mc_column canal_data">
                    <div class="mc_column item_canal_logo">
                        <img src="<?php echo $objCanal->logo; ?>" title="<?php echo $objCanal->descripcion; ?>" alt="<?php echo $objCanal->descripcion; ?>"> 

                    </div>
                    <div class="mc_column item_canal_desc">
                        <?php echo $objCanal->descripcion; ?>
                    </div>         
                </div>
                <div class="mc_column canal_info">
                    <div class="mc_column item_canal_text1">
                        <a class="suscript_link3 linker_btn3 tcol08 " href="#"><span class="suscript_left2"></span><span class="suscript_center2  size08 tol00">suscribirse</span><span class="suscript_right2"></span></a>
                        <a class="suscript_link3 linker_btn3 tcol08 "><span class="suscript_number  size08 tol00">125879</span></a>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>151 videos</span>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>1,443 seguidores</span>
                    </div>
                    <div class="mc_column item_canal_text2">
                        <span>Favoritos</span>
                    </div>        
                </div>
            </div>
        </div>
        <!--start programas-->
        <?php if (count($objColeccionSeccion) > 0): ?>
            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:programa')): ?>
                    <div class="sli_item_">
                        <div class="head_section mc_column mc_columnA mc_mbottom">
                            <div class="bkg_col02 hsection">
                                <h4>PROGRAMA</h4>               
                            </div>
                        </div>
                        <div class="mc_column mc_columnA head_section mbottom str_E">
                            <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                            <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                    <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                        <a href="#">
                                            <div class="content_section2">
                                                <div class="mc_column layer_content">
                                                    <img src="<?php echo $objDetalleSeccion->imagen; ?>" alt="ATV">
                                                </div>
                                            </div>
                                        </a>
                                    </div>                                    
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>        
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <!--end programas-->

        <!--start listas-->
        <?php if (count($objColeccionSeccion) > 0): ?>
            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:lista')): ?>        
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>LISTAS</h4>           

                                <!--<div class="options_section">
                                    <span class="options_left"></span>
                                    <span class="options_center"><a href="#">+popular del d&#237;a</a></span>
                                    <span class="options_right"></span>-->

                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">          
                                <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                                <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                    <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                        <li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="<?php echo $objDetalleSeccion->imagen; ?>" alt="ATV"/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end listas-->

        <!--start los mas vistos -->
        <?php if (count($objColeccionSeccion) > 0): ?>
            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:visto')): ?>        
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>LOS MAS VISTOS</h4>           

                                <!--<div class="options_section">
                                    <span class="options_left"></span>
                                    <span class="options_center"><a href="#">+popular del d&#237;a</a></span>
                                    <span class="options_right"></span>-->

                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">          
                                <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                                <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                    <?php $contador = 0; ?>
                                    <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                        <?php if ($contador < 4): ?>
                                            <li class="str_C">
                                                <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                    <div>
                                                        <a href="#">
                                                            <img src="<?php echo $objDetalleSeccion->imagen; ?>" alt="ATV"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end los mas vistos -->

        <!--start los mas comentados -->
        <?php if (count($objColeccionSeccion) > 0): ?>
            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:comentado')): ?>        
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>LOS MAS COMENTADOS</h4>           

                                <!--<div class="options_section">
                                    <span class="options_left"></span>
                                    <span class="options_center"><a href="#">+popular del d&#237;a</a></span>
                                    <span class="options_right"></span>-->

                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">          
                                <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                                <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                    <?php $contador = 0; ?>
                                    <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                        <?php if ($contador < 4): ?>
                                            <li class="str_C">
                                                <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                    <div>
                                                        <a href="#">
                                                            <img src="<?php echo $objDetalleSeccion->imagen; ?>" alt="ATV"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end los mas comentados -->

        <!--start los mas recientes -->
        <?php if (count($objColeccionSeccion) > 0): ?>
            <?php foreach ($objColeccionSeccion as $puntero => $objSeccion): ?>
                <?php if ($objSeccion->tipo_secciones_id == $this->config->item('seccion:reciente')): ?>        
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>LOS MAS COMENTADOS</h4>           

                                <!--<div class="options_section">
                                    <span class="options_left"></span>
                                    <span class="options_center"><a href="#">+popular del d&#237;a</a></span>
                                    <span class="options_right"></span>-->

                                <div class="controls-slider">
                                    <div class="hidden_links">
                                        <span class="back_link"></span>
                                        <span class="forward_link"></span>
                                    </div>
                                    <a href="#" class="back_linker_small">Siguiente</a>
                                    <a href="#" class="forward_linker_small">Anterior</a>
                                </div>
                            </div>
                        </div>  
                        <div class="mc_column mc_columnA bd_sli">
                            <ul class="sli_ver">          
                                <?php $coleccion_detalle_seccion = $objSeccion->detalle_seccion; ?>
                                <?php if (count($coleccion_detalle_seccion) > 0): ?>
                                    <?php $contador = 0; ?>
                                    <?php foreach ($coleccion_detalle_seccion as $indice => $objDetalleSeccion): ?>
                                        <?php if ($contador < 4): ?>
                                            <li class="str_C">
                                                <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                    <div>
                                                        <a href="#">
                                                            <img src="<?php echo $objDetalleSeccion->imagen; ?>" alt="ATV"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php $contador++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end los mas recientes -->        

    </div>
</div>