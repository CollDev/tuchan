<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/custom.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/mediaquerie.css') ?>" rel="stylesheet" />
<div class="container-main">
    <div class="wrapper-main">
        <div class="mc_column mc_columnA mc_mbottom">
                <div class="flexslider">
                    <ul class="slides">           
                        <li>
                            <div class="content_section3">
                                <?php if(count($objDestacado)>0): ?>
                                <div class="layer_content">                        
                                       <img src="<?php echo $objDestacado->imagen ?>" title="Fútbol en América" alt="Fútbol en América">
                                        <div class="mode_fade">
                                            <a href="#">
                                            <div class="layer_info">
                                                <div class="data_info down_place4">
                                                    <span class="span_text2"></span> 
                                                    <h5><?php echo $objDestacado->titulo ?></h5>
                                                    <span class="span_text2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</span>
                                                </div>
                                            </div>
                                            </a> 
                                        </div>                     
                                </div>
                                <?php endif; ?>
                            </div>
                        </li>
                     </ul>
                </div> 
        </div>
        <!--listamos las colecciones activas-->
        <!--start colecciones-->
        <?php if (count($coleccion) > 0): ?>
            <?php foreach ($coleccion as $puntero => $arrayImagen): ?>
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>Temporada</h4>           

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
                                <?php if (count($arrayImagen) > 0): ?>
                                    <?php foreach ($arrayImagen as $indice => $imagen): ?>
                                        <li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="<?php echo $imagen; ?>" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end colecciones-->
        <!--start lista-->
        <?php if (count($lista) > 0): ?>
            <?php foreach ($lista as $puntero => $arrayImagen): ?>
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>Listas</h4>           

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
                                <?php if (count($arrayImagen) > 0): ?>
                                    <?php foreach ($arrayImagen as $indice => $imagen): ?>
                                        <li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="<?php echo $imagen; ?>" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end lista-->
        <!--start video-->
        <?php if (count($video) > 0): ?>
            <?php foreach ($video as $puntero => $arrayImagen): ?>
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>videos</h4>           

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
                                <?php if (count($arrayImagen) > 0): ?>
                                    <?php foreach ($arrayImagen as $indice => $imagen): ?>
                                        <li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="<?php echo $imagen; ?>" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end video-->
        <!--start colecciones-->
        <?php if (count($personalizado) > 0): ?>
            <?php foreach ($personalizado as $puntero => $arrayImagen): ?>
                    <div class="sli_item">
                        <!-- ENCABEZADO -->
                        <div class="head_section mc_column mc_columnA mc_mbottom hd_sli">
                            <div class="bkg_col02 hsection">

                                <h4>Secciones personalizadas</h4>           

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
                                <?php if (count($arrayImagen) > 0): ?>
                                    <?php foreach ($arrayImagen as $indice => $imagen): ?>
                                        <li class="str_C">
                                            <div class="mc_column mc_columnD mc_mbottom mc_mright">
                                                <div>
                                                    <a href="#">
                                                        <img src="<?php echo $imagen; ?>" alt=""/>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>                                
                            </ul>
                        </div>   
                    </div>
            <?php endforeach; ?>
        <?php endif; ?>        
        <!--end colecciones-->
    </div>
</div> 
        