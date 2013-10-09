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
            <ul class="nav nav-tabs">
                <li><a href="#videos_list" data-toggle="tab">Videos <?php echo $videos['nombre']; ?></a></li>
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
                                    <th width="108">Estado liquid</th>
                                    <th width="154">Fecha registro</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
                                $rutasplitter = array();
                                $count = 0;
                                foreach ($videos['videos'] as $video) {
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
                                        if ($count < 7) {
                                            $rutasplitter[$count]['id'] = $video->id;
                                            $rutasplitter[$count]['url'] = trim($video->rutasplitter);
                                            $rutasplitter[$count]['titulo'] = trim($video->titulo);
                                        }
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
                                    <td>
                                        <div id="<?php echo $video->id; ?>_status"></div>
                                    </td>
                                </tr>
<?php
                                    $count++;
                                }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script>
<?php
echo '                var $videos = ' . json_encode($rutasplitter) . ';';
?>

            </script>
<?php
$content = ob_get_clean();
require_once 'layout.php';