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
                        <form class="form-horizontal row" action="<?php echo $motor; ?>/cmsapi/post_upload" enctype="multipart/form-data" id="upload_form" method="post" novalidate>
                            <fieldset class="col-sm-6">
                                <legend>Metadata</legend>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="titulo">Título</label>
                                    <div class="col-sm-9">
                                        <input id="titulo" name="titulo" placeholder="Ingrese un título para el video" required type="text" />
                                    </div>
                                </div>
                                <div class="row hidden">
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
                                    <div class="col-sm-9 col-offset-3">
                                        <div class="progress progress-striped active" style="display: none;">
                                            <div class="bar progress-bar"></div>
                                            <div class="percent">0%</div>
                                        </div>
                                        <div id="status"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="fecha_transmision">Transmisión</label>
                                    <div class="col-sm-9">
                                        <input id="fecha_transmision" name="fecha_transmision" placeholder="Seleccione una fecha" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="hora_trans_ini">Inicio</label>
                                    <div class="col-sm-9">
                                        <input id="hora_trans_ini" name="hora_trans_ini" placeholder="Seleccione una hora" type="time" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="hora_trans_fin">Fin</label>
                                    <div class="col-sm-9">
                                        <input id="hora_trans_fin" name="hora_trans_fin" placeholder="Seleccione una hora" type="time" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="descripcion">Descripcion</label>
                                    <div class="col-sm-9">
                                        <input id="descripcion" name="descripcion" placeholder="Una síntesis del video" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="tematicas_tag">Tematica</label>
                                    <div class="col-sm-9">
                                        <input id="tematicas" name="tematicas" placeholder="Los temas tratados" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-3 control-label" for="personajes_tag">Personajes</label>
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
                                    <button id="submit_upload" class="btn btn-default pull-right">Subir video</button>
                                </div>
                            </fieldset>
                            <input id="canal_id" name="canal_id" type="hidden" value="<?php echo $canal_id; ?>" />
                            <input name="padre" type="hidden" value="0" />
                            <input id="fec_trans" name="fec_trans" type="hidden" value="" />
                            <input name="video_id" type="hidden" value="0" />
                            <input name="ubicacion" type="hidden" value="" />
                            <input name="fec_pub_fin" type="hidden" value="" />
                            <input name="fec_pub_ini" type="hidden" value="" />
                            <input name="tipo_maestro" type="hidden" value="" />
                            <input name="int_tipo_video" type="hidden" value="1" />
                            <input name="existe_fragmento" type="hidden" value="0" />
                            <input id="motor" name="motor" type="hidden" value="<?php echo $motor; ?>" />
                        </form>
                    </div>
                    <div class="tab-pane fade" id="search_form_tab">
                        <form class="form-horizontal row" action="" id="search_form">
                            <fieldset class="row">
                                <div class="col-sm-5">
                                    <div class="row">
                                        <label class="col-sm-4 control-label" for="termino">Términos</label>
                                        <div class="col-sm-8">
                                            <input id="termino" type="text" name="termino" placeholder="Ingrese un término" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 control-label" for="canal_search_id">Canales</label>
                                        <div class="col-sm-8">
                                            <select id="canal_search_id" name="canal_search_id">
                                                <option value="0">Seleccione canal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <label class="col-sm-1 control-label" for="fec_ini">Inicio</label>
                                    <div class="col-sm-5">
                                        <input id="fec_ini" type="date" name="fec_ini" />
                                        <p class="help-block">Sólo aquí.</p> 
                                    </div>
                                    <label class="col-sm-1 control-label" for="fec_fin">Fin</label>
                                    <div class="col-sm-5">
                                        <input id="fec_fin" type="date" name="fec_fin" />
                                        <p class="help-block">En el rango</p>
                                    </div>
                                    <button class="btn btn-default pull-right">Buscar</button>
                                </div>
                            </fieldset>
                            <input id="fecha_inicio" name="fecha_inicio" type="hidden" value="" />
                            <input id="fecha_fin" name="fecha_fin" type="hidden" value="" />
                        </form>
                        <div id="search_results"></div>
                    </div>
                    <div class="tab-pane fade" id="cut_form_tab"></div>
                </div>
<div class="modal fade" id="videoUrl">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button btn btn-default" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Video URL</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn btn-default">Close</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once 'layout.php';