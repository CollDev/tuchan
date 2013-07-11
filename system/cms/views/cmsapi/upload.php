<?php ob_start(); ?>
<?php if (false) { ?>
        <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong>Oh snap!</strong>
                    Change a few things up and try submitting again.
                </div>
                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong>Well done!</strong>
                    You successfully read this important alert message.
                </div>
                <div class="alert alert-info">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong>Heads up!</strong>
                    This alert needs your attention, but it's not super important.
                </div>
                <div class="alert">
                    <button class="close" data-dismiss="alert" type="button">×</button>
                    <strong>Warning!</strong>
                    Best check yo self, you're not looking too good.
                </div>
<?php } ?>
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#upload_form_tab" data-toggle="tab">Subida de video</a></li>
                    <li><a href="#search_form_tab" data-toggle="tab">Búsqueda</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active fade in" id="upload_form_tab">
                        <form class="form-horizontal" action="/cmsapi/upload" enctype="multipart/form-data" id="upload_form" method="post">
                            <fieldset class="col-sm-6">
                                <legend>Metadata</legend>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="canal_id">Canales</label>
                                    <div class="col-sm-10">
                                        <select id="canal_id" name="canal_id">
                                            <option value="0">Seleccione canal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="titulo">Título</label>
                                    <div class="col-sm-10">
                                        <input id="titulo" name="titulo" placeholder="Ingrese un título para el video" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="fragmento">Fragmento</label>
                                    <div class="col-sm-10">
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
                                    <label class="col-sm-2 control-label" for="video">Video</label>
                                    <div class="col-sm-10">
                                        <input id="video" name="video" type="file" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="fec_trans">Fecha de transmisión</label>
                                    <div class="col-sm-10">
                                        <input id="fec_trans" name="fec_trans" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="hora_trans_ini">Fecha inicio</label>
                                    <div class="col-sm-10">
                                        <input id="hora_trans_ini" name="hora_trans_ini" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="hora_trans_fin">Fecha fin</label>
                                    <div class="col-sm-10">
                                        <input id="hora_trans_fin" name="hora_trans_fin" type="date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="descripcion">Descripcion</label>
                                    <div class="col-sm-10">
                                        <input id="descripcion" name="descripcion" placeholder="Una síntesis del video" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="tematicas">Tematica</label>
                                    <div class="col-sm-10">
                                        <input id="tematicas" name="tematicas" placeholder="Los temas tratados" required type="text" />
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="personajes">Personajes</label>
                                    <div class="col-sm-10">
                                        <input id="personajes" name="personajes" placeholder="Principales y secundarios" required type="text" />
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="col-sm-6">
                                <legend>Jerarquía</legend>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="categoria">Categorías</label>
                                    <div class="col-sm-10">
                                        <select id="categoria" name="categoria">
                                            <option value="0">Seleccione categoría</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="programa">Programas</label>
                                    <div class="col-sm-10">
                                        <select id="programa" name="programa">
                                            <option value="0">Seleccione programa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="coleccion">Colecciones</label>
                                    <div class="col-sm-10">
                                        <select id="coleccion" name="coleccion">
                                            <option value="0">Seleccione colección</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 control-label" for="lista">Listas</label>
                                    <div class="col-sm-10">
                                        <select id="lista" name="lista">
                                            <option value="0">Seleccione lista</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-default">Subir video</button>
                                </div>
                            </fieldset>
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
                        <form class="form-horizontal" action="" id="search_form">
                            <div class="col-sm-6">
                                <label class="col-sm-2 control-label" for="termino">Términos de búsqueda</label>
                                <div class="col-sm-10">
                                    <input id="termino" type="text" name="termino" placeholder="Ingrese un término" />
                                    <p class="help-block">Título, descripción, temática, personaje</p>
                                </div>
                                <label class="col-sm-2 control-label" for="jerarquia">Jerarquías</label>
                                <div class="col-sm-10">
                                    <input id="jerarquia" type="text" name="jerarquia" placeholder="Ingrese jerarquía" />
                                    <p class="help-block">Categorías, programas, colecciones, listas</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-2 control-label" for="fecha_inicio">Fecha inicio</label>
                                <div class="col-sm-4">
                                    <input id="fecha_inicio" type="text" name="fecha_inicio" placeholder="Ingrese una fecha" />
                                    <p class="help-block">Sólo buscará en esta fecha.</p> 
                                </div>
                                <label class="col-sm-2 control-label" for="fecha_fin">Fecha fin</label>
                                <div class="col-sm-4">
                                    <input id="fecha_fin" type="text" name="fecha_fin" placeholder="Ingrese una fecha" />
                                    <p class="help-block">Buscará dentro de este rango</p>
                                </div>
                            </div>
                            <div class="row">
                                <button class="btn btn-default">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
<?php
$content = ob_get_clean();
require_once 'layout.php';