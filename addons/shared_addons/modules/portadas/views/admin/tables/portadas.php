<style>
    .ui-accordion-header .ui-icon{
        left: 2.5em !important;
    }
    .ui-accordion-content-active{
        height: auto !important;
    }
</style>
<?php if ($portadas) : ?>
    <?php echo form_open(''); ?>
    <table>
        <tr>
            <td style="width: 5%;">#</td>
            <td style="width: 30%;">Portadas</td>
            <td style="width: 30%;">Detalle</td>
            <td style="width: 10%;">Estado</td>
            <td style="width: 25%;">Acciones</td>
        </tr>
    </table>
    <div id="accordion">
        <?php
        foreach ($portadas as $index => $post):
            ?>
            <h3>
                <table>
                    <tr>
                        <td style="width: 5%;"><?php echo $index +1; ?></td>
                        <td style="width: 30%;"><?php echo $post->nombre; ?></td>
                        <td style="width: 30%;"><?php echo $post->descripcion; ?></td>
                        <td style="width: 10%;"><?php echo $post->estado; ?></td>
                        <td style="width: 25%;">Previsualizar | Editar | Eliminar</td>
                    </tr>
                </table>
            </h3>
            <div>
                <?php
                    $coleccion_seccion = $post->secciones;
                    if(count($coleccion_seccion)>0):
                        foreach($coleccion_seccion as $indice=>$objSeccion):
                            switch ($objSeccion->estado){
                                case 0:$estado = 'Borrador';
                                    $acciones = 'Previsualizar | Publicar | <a title="Editar" href="admin/portadas/seccion/'.$objSeccion->id.'">Editar</a> | Eliminar';
                                    break;
                                case 1: $estado = 'Publicado';
                                    $acciones = 'Ver | <a title="Editar" href="admin/portadas/seccion/'.$objSeccion->id.'">Editar</a> | Eliminar';
                                    break;
                                case 2 : $estado = 'Eliminado';
                                    $acciones = 'Previsualizar |Restablecer';
                                    break;
                            }
                ?>
                <table>
                    <tr>
                        <td style="width: 5%;"><?php echo $indice +1; ?></td>
                        <td style="width: 30%;"><?php echo $objSeccion->nombre; ?></td>
                        <td style="width: 30%;"><?php echo $objSeccion->descripcion; ?></td>
                        <td style="width: 10%;"><?php echo $estado; ?></td>
                        <td style="width: 25%;"><?php echo $acciones; ?></td>
                    </tr>
                </table>                    
                <?php
            endforeach;
                    endif;
                ?>
            </div>
            <?php
        endforeach;
        ?>
    </div>
    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
    <?php echo form_close(); ?>
    <script>
        $(document).ready(function() {
            //$(function() {
                $("#accordion").accordion();
            });
    </script>
<?php endif; ?>
    