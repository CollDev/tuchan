<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/custom.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('/addons/shared_addons/modules/canales/css/mediaquerie.css') ?>" rel="stylesheet" />
<pre>
<?php print_r($objDestacado); ?>
</pre>
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
    </div>
</div> 
        