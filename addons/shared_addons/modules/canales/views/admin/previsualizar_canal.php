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
                                        <?php $detalles = $objPortada[0]->detalle; ?>
                                        <?php if (count($detalles) > 0): ?>
                                            <?php foreach ($detalles as $puntero => $objDetalle): ?>
                                                <img src="<?php echo $objDetalle->imagen; ?>" title="Insensato Corazón" alt="Insensato Corazón">
                                                <?php
                                                break;
                                            endforeach;
                                            ?>
                                        <?php else: ?>
                                            <img style="width:820px; height: 400px;" src="<?php echo $this->config->item('url:portada'); ?>" title="Insensato Corazón" alt="Insensato Corazón">
                                        <?php endif; ?>
                                        <div class="mode_fade">
                                            <a href="#">

                                                <div class="layer_info">
                                                    <div class="data_info down_place4">
                                                        <span class="span_text2"></span> 
                                                        <h5>Insensato Corazón</h5>
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
    </div>
</div>