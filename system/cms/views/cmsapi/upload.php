<?php
ob_start();
if (false) {
?>
<div class="jError"></div>
<?php
}
?>
<form action="/cmsapi/upload" enctype="multipart/form-data" id="upload_form" method="post">
            <fieldset>
                <legend>Subida de video</legend>
                <fieldset>
                    <legend>Metadata</legend>
                    <label for="lista_canales">Canales</label>
                    <select id="lista_canales" name="canal_id">
                        <option value="0">Seleccione canal</option>
                    </select>
                    <br>
                    <label for="titulo">Título</label>
                    <input id="titulo" name="titulo" placeholder="Ingrese un título para el video" required type="text" />
                    <br>
                    <label for="fragmento">Fragmento</label>
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
                    <br>
                    <label for="video">Video</label>
                    <input id="video" name="video" type="file" />
                    <br>
                    <label for="fecha_transmision">Fecha de transmisión</label>
                    <input id="fec_trans" name="fec_trans" type="date" />
                    <br>
                    <label for="fecha_transmision_inicio">Fecha de transmisión inicio</label>
                    <input id="hora_trans_ini" name="hora_trans_ini" type="date" />
                    <br>
                    <label for="fecha_transmision_fin">Fecha de transmisión fin</label>
                    <input id="hora_trans_fin" name="hora_trans_fin" type="date" />
                    <br>
                    <label for="descripcion">Descripcion</label>
                    <input id="descripcion" name="descripcion" placeholder="Una síntesis del video" required type="text" />
                    <br>
                    <label for="tematica">Tematica</label>
                    <input id="tematicas" name="tematicas" placeholder="Los temas tratados" required type="text" />
                    <br>
                    <label for="personajes">Personajes</label>
                    <input id="personajes" name="personajes" placeholder="Principales y secundarios" required type="text" />
                    <br>
                </fieldset>
                <fieldset>
                    <legend>Jerarquía</legend>
                    <label for="lista_categorias">Categorías</label>
                    <select id="lista_categorias" name="categoria">
                        <option value="0">Seleccione categoría</option>
                    </select>
                    <br>
                    <label for="lista_programas">Programas</label>
                    <select id="lista_programas" name="programa">
                        <option value="0">Seleccione programa</option>
                    </select>
                    <br>
                    <label for="lista_colecciones">Colecciones</label>
                    <select id="lista_colecciones" name="coleccion">
                        <option value="0">Seleccione colección</option>
                    </select>
                    <br>
                    <label for="lista_listas">Listas</label>
                    <select id="lista_listas" name="lista">
                        <option value="0">Seleccione listas</option>
                    </select>
                </fieldset>
                <fieldset>
                    <legend>Enviar el formulario</legend>
                    <button>Subir video</button>
                </fieldset>
                <input type="hidden" name="padre" value="0" />
                <input type="hidden" name="video_id" value="0" />
                <input type="hidden" name="ubicacion" value="" />
                <input type="hidden" name="fec_pub_fin" value="" />
                <input type="hidden" name="fec_pub_ini" value="" />
                <input type="hidden" name="tipo_maestro" value="" />
                <input type="hidden" name="int_tipo_video" value="1" />
                <input type="hidden" name="existe_fragmento" value="0" />
            </fieldset>
        </form>
        <form action="" id="search_form">
            <fieldset>
                <legend>Búsqueda</legend>
                <fieldset>
                    <legend>Términos de búsqueda</legend>
                    <label for="busca_termino">Título, descripción, temática, personaje</label>
                    <input id="termino" type="text" name="termino" placeholder="Ingrese un término" />
                </fieldset>
                <fieldset>
                    <legend>Jerarquía</legend>
                    <label for="busca_termino">Categorías, programas, colecciones, listas</label>
                    <input id="jerarquia" type="text" name="jerarquia" placeholder="Ingrese un término" />
                </fieldset>
                <fieldset>
                    <legend>Fecha</legend>
                    <label for="busca_termino">Fecha inicio</label>
                    <input id="fecha_inicio" type="text" name="fecha_inicio" placeholder="Ingrese una fecha" />
                    <br>
                    <label for="busca_termino">Fecha fin</label>
                    <input id="fecha_fin" type="text" name="fecha_fin" placeholder="Ingrese una fecha" />
                </fieldset>
                <fieldset>
                    <legend>Enviar el formulario</legend>
                    <button>Buscar</button>
                </fieldset>
            </fieldset>
        </form>
<?php $content = ob_get_clean();
require_once 'layout.php';