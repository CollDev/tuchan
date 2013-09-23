<?php
if(!session_id()) {
    session_start();
}
ob_start();
?>
            <ul class="nav nav-tabs">
                <li><a href="#videos_list" data-toggle="tab">Videos</a></li>
                <li><a href="#upload" data-toggle="tab">Subida</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="videos_list">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th>Estado liquid</th>
                                    <th>Fecha registro</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
                                foreach ($videos as $video) {
                                    switch ($video->estado_liquid) {
                                        case 0:
                                            $labell = 'default';
                                            $spanl = 'Nuevo';
                                            break;
                                        case 1:
                                            $labell = 'default';
                                            $spanl = 'Codificando';
                                            break;
                                        case 2:
                                            $labell = 'default';
                                            $spanl = 'Codificando';
                                            break;
                                        case 3:
                                            $labell = 'info';
                                            $spanl = 'Subiendo';
                                            break;
                                        case 4:
                                            $labell = 'warning';
                                            $spanl = 'Borrador';
                                            break;
                                        case 5:
                                            $labell = 'primary';
                                            $spanl = 'Activo';
                                            break;
                                        case 6:
                                            $labell = 'success';
                                            $spanl = 'Publicado';
                                            break;
                                        default:
                                            $labell = 'danger';
                                            $spanl = 'Desconocido';
                                            break;
                                    }
                                    switch ($video->estado) {
                                        case 0:
                                            $labele = 'default';
                                            $spane = 'Codificando';
                                            break;
                                        case 1:
                                            $labele = 'warning';
                                            $spane = 'Borrador';
                                            break;
                                        case 2:
                                            $labele = 'success';
                                            $spane = 'Publicado';
                                            break;
                                        case 3:
                                            $labele = 'danger';
                                            $spane = 'Eliminado';
                                            break;
                                        case 4:
                                            $labele = 'danger';
                                            $spane = 'Error';
                                            break;
                                        default:
                                            $labele = 'danger';
                                            $spane = 'Desconocido';
                                            break;
                                    }
                                    $tr = 'default';
                                    $liquid_array = array("0", "1", "2", "3");
                                    $estado_array = array("0", "3", "4");
                                    if ($video->estado_liquid == 6 && $video->estado == 2) {
                                        $tr = 'success';
                                    } else if (in_array($video->estado_liquid, $liquid_array, false)) {
                                        $tr = 'warning';
                                        if (!in_array($video->estado, $estado_array, false)) {
                                            $tr = 'danger';
                                        }
                                    } else if (in_array($video->estado, $estado_array, false)) {
                                        $tr = 'warning';
                                        if (!in_array($video->estado_liquid, $liquid_array, false)) {
                                            $tr = 'active';
                                       }
                                    }
?>
                                <tr class="<?php echo $tr; ?>">
                                    <td><?php echo $video->id; ?></td>
                                    <td><?php echo $video->titulo; ?></td>
                                    <td><span class="label label-<?php echo $labele ?>"><?php echo $spane; ?></span></td>
                                    <td><span class="label label-<?php echo $labell ?>"><?php echo $spanl; ?></span></td>
                                    <td><?php echo $video->fecha_registro; ?></td>
                                </tr>
<?php
                                }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="upload">
                    <form class="form-horizontal" id="upload_video" role="form" enctype="multipart/form-data" action="<?php echo base_url(); ?>migrate/upload" method="post">
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" name="titulo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="video" class="col-sm-2 control-label">Video</label>
                            <div class="col-sm-10">
                                <input type="file" id="video" name="video">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button id="submit_upload" type="submit" class="btn btn-primary">Subir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<?php
$content = ob_get_clean();
require_once 'layout.php';