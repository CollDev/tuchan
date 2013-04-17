<style>
    .table-list tbody tr:hover{
        background-color: #CCE4E5;
    }
</style>
<table class="table-list">
    <thead>
        <tr>
            <th style="width: 5%">#</th>
            <th style="width: 20%">Imagen</th>
            <th style="width: 10%">Tipo</th>
            <th style="width: 10%">tamaño</th>
            <th style="width: 10%">Fecha publicación</th>
            <th style="width: 10%">estado</th>
            <th style="width: 30%">Acciones</th>
            <th style="width: 5%">ID</th>
        </tr>
    </thead>
    <tbody id="divContenido">
        <?php
        if (count($imagenes) > 0):
            foreach ($imagenes as $puntero => $objImagen):
                ?>
        <tr>
            <td style="width: 5%"><?php echo ($puntero+1) ?></td>
            <td style="width: 20%"><img style="width:120px; height: 70px;" src="<?php echo $objImagen->imagen; ?>" /></td>
            <td style="width: 10%"><?php echo $objImagen->tipo_imagen; ?></td>
            <td style="width: 10%"><?php echo $objImagen->tamanio; ?></td>
            <td style="width: 10%"><?php echo $objImagen->fecha_registro; ?></td>
            <td style="width: 10%"><?php echo lang('global:'.$objImagen->estado.'_estado'); ?></td>
            <td style="width: 30%"><a href="#" onclick="return false;" class="btn blue">Cambiar Imagen</a></td>
            <td style="width: 5%"><?php echo $objImagen->id; ?></td>
        </tr>
                <?php
            endforeach;
            ?>

<?php else: ?>
            <tr>
                <td colspan="8">No hay items</td>
            </tr>
<?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8"></td>
        </tr>
    </tfoot>
</table>