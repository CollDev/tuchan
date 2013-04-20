<noscript>
<span>PyroCMS requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
</noscript>

<div class="topbar" dir=<?php $vars = $this->load->_ci_cached_vars;
echo $vars['lang']['direction']; ?>>

    <div class="bajada2">
        <span class="boxgrid captionfull">	
            <div class="cover boxcaption">

                <div class="toggle-icon-finder">
                    <a href="#" class="linker">EXPANDER</a>
                    <a href="#" class="linker2 hide">CONTRAER</a>

                    <a href="#" class="plus_view"></a>	
                </div>
                <div class="search-box">
<!--                <span class="view_mc">BUSQUEDA</span>                          	  
                         <div class="frm-input">
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text" placeholder="Titulo">   
                               </div>
                               <br />
                               <br />
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text" placeholder="Categoria">
                               </div>
                               <br />
                               <br />
                               <div class="input-wrapper">
                                   <input id="q" name="q" type="text"  placeholder="Tipo">
                               </div>
                               <br />
                               <br />
                               <a href="#" id="s" name="s" class="btn blue">
                                   <span class="st">Buscar</span>
                               
                               </a>
                    </div>-->
                </div>   


            </div>
        </span>
    </div>

    <div class="wrapper">
        <div id="logo">
            <?php echo anchor($this->session->userdata('lista_videos_default'), 'logo',  'title="Mi Canal"') ?> 
        </div>

        <nav>
            <?php file_partial('navigation'); ?>
        </nav>
    </div>

</div>

<div class="subbar">
    <div class="wrapper">

        <h2 class="channel_item"><?php echo $module_details['name'] ? anchor('admin/' . $module_details['slug'], $module_details['name']) : lang('global:dashboard'); ?>
            <!--<small>-->
            <?php if ($this->uri->segment(2)) :
                echo '&nbsp; | &nbsp;';
            endif ?>
            <?php echo $module_details['description'] ? $module_details['description'] : ''; ?>
             <!-- </small>-->
        </h2>
        <?php file_partial('shortcuts'); ?>
    </div>
</div>

<?php
if (!empty($module_details['sections'])) :
    file_partial('sections'); 
endif ?>