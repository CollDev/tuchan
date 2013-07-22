<?php
if(!session_id()) {
    session_start();
}
ob_start();
if (!empty($_SESSION['upload_result'])) {
?>
                <div class="alert alert-<?php echo $_SESSION['upload_result']['type'] ?> fade in">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong><?php echo $_SESSION['upload_result']['title'] ?></strong>
                    <?php echo $_SESSION['upload_result']['message'] ?>
                </div>
<?php
    unset($_SESSION['upload_result']);
}
?>

                <div id="flash_message" class="alert" style="display: none;">
                    <strong id="flash_title"></strong>
                </div>
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#upload_form_tab" data-toggle="tab">Subida de video</a></li>
                    <li><a href="#search_form_tab" data-toggle="tab">Búsqueda</a></li>
                    <li class="disabled"><a id="corte_video" href="#cut_form_tab" data-toggle="">Corte de video</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active fade in" id="upload_form_tab">
                        <form class="form-horizontal" action="/cmsapi/upload" enctype="multipart/form-data" id="upload_form" method="post">
                            <fieldset class="col-sm-6">
                                <legend>Metadata</legend>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="titulo">Título</label>
                                    <div class="col-sm-9">
                                        <input id="titulo" name="titulo" placeholder="Ingrese un título para el video" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="fragmento">Fragmento</label>
                                    <div class="col-sm-9">
                                        <select id="fragmento" name="fragmento">
                                            <option value="0">Seleccione un fragmento</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="video">Video</label>
                                    <div class="col-sm-9">
                                        <input id="video" name="video" type="file" />
                                    </div>
                                    <div class="progress_upload" style="display: none; clear: both;">
                                    <div class="bar"></div>
                                    <div class="percent">0%</div>
                                </div>
                                <div id="status"></div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="fec_trans">Fecha de transmisión</label>
                                    <div class="col-sm-9">
                                        <input id="fec_trans" name="fec_trans" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="hora_trans_ini">Fecha inicio</label>
                                    <div class="col-sm-9">
                                        <input id="hora_trans_ini" name="hora_trans_ini" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="hora_trans_fin">Fecha fin</label>
                                    <div class="col-sm-9">
                                        <input id="hora_trans_fin" name="hora_trans_fin" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="descripcion">Descripcion</label>
                                    <div class="col-sm-9">
                                        <input id="descripcion" name="descripcion" placeholder="Una síntesis del video" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="tematicas">Tematica</label>
                                    <div class="col-sm-9">
                                        <input id="tematicas" name="tematicas" placeholder="Los temas tratados" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="personajes">Personajes</label>
                                    <div class="col-sm-9">
                                        <input id="personajes" name="personajes" placeholder="Principales y secundarios" required type="text" />
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="col-sm-6">
                                <legend>Jerarquía</legend>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="categoria">Categorías</label>
                                    <div class="col-sm-9">
                                        <select id="categoria" name="categoria">
                                            <option value="0">Seleccione categoría</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="programa">Programas</label>
                                    <div class="col-sm-9">
                                        <select id="programa" name="programa">
                                            <option value="0">Seleccione programa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="coleccion">Colecciones</label>
                                    <div class="col-sm-9">
                                        <select id="coleccion" name="coleccion">
                                            <option value="0">Seleccione colección</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="lista">Listas</label>
                                    <div class="col-sm-9">
                                        <select id="lista" name="lista">
                                            <option value="0">Seleccione lista</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-default pull-right">Subir video</button>
                                </div>
                            </fieldset>
                            <input id="canal_id" type="hidden" name="canal_id" value="<?php echo $canal_id; ?>" />
                            <input type="hidden" name="padre" value="0" />
                            <input type="hidden" name="video_id" value="0" />
                            <input type="hidden" name="ubicacion" value="" />
                            <input type="hidden" name="fec_pub_fin" value="" />
                            <input type="hidden" name="fec_pub_ini" value="" />
                            <input type="hidden" name="tipo_maestro" value="" />
                            <input type="hidden" name="int_tipo_video" value="1" />
                            <input type="hidden" name="existe_fragmento" value="0" />
                        </form>
                    </div>
                    <div class="tab-pane fade" id="search_form_tab">
                        <div class="row">
                            <form class="form-horizontal" action="" id="search_form">
                                <div class="col-sm-5">
                                    <label class="col-sm-4 control-label" for="termino">Términos    </label>
                                    <div class="col-sm-8">
                                        <input id="termino" type="text" name="termino" placeholder="Ingrese un término" />
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <label class="col-sm-2 control-label" for="fecha_inicio">Fecha inicio</label>
                                    <div class="col-sm-4">
                                        <input id="fecha_inicio" type="date" name="fecha_inicio" />
                                        <p class="help-block">Sólo en esta fecha.</p> 
                                    </div>
                                    <label class="col-sm-2 control-label" for="fecha_fin">Fecha fin</label>
                                    <div class="col-sm-4">
                                        <input id="fecha_fin" type="date" name="fecha_fin" />
                                        <p class="help-block">Dentro del rango</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button class="btn btn-default pull-right">Buscar</button>
                                </div>
                            </form>
                        </div>
                        <div id="search_results" class="row"></div>
                    </div>
                    <div class="tab-pane fade" id="cut_form_tab">
                        <div id="cut_this_video"></div>
                    </div>
                </div>
<?php
$content = ob_get_clean();
require_once 'layout.php';