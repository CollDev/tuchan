<table class="table-list">
    <thead>
        <tr>
            <th>#</th>
            <th>Imagen</th>
            <th>nombre</th>
            <th>Tipo</th>
            <th>Fecha publicación</th>
            <th>estado</th>
            <th>Acciones</th>
            <th>ID</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($items) > 0):
            foreach ($items as $puntero => $objItem):
                ?>
        <tr>
            <td><?php echo ($puntero+1) ?></td>
            <td><img style="width:120px; height: 70px;" src="<?php echo $objItem->imagen; ?>" /></td>
            <td><?php echo $objItem->nombre; ?></td>
            <td><?php echo $objItem->tipo; ?></td>
            <td><?php echo $objItem->fecha_registro; ?></td>
            <td><?php echo $objItem->estado; ?></td>
            <td><a href="#" onclick="quitarGrupoMaestro(<?php echo $objItem->grupo_detalle_id; ?>);return false;" class="btn red">Quitar</a></td>
            <td><?php echo $objItem->grupo_detalle_id; ?></td>
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