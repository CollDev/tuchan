<table>
    <thead>
        <tr>
            <th>Nº</th>
            <th>Nombre</th>
            <th>Fecha de registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
<?php
$indice = 1;
foreach($categorias as $categoria) {
?>
        <tr>
            <td>
                <?php echo $indice; ?>
            </td>
            <td>
                <?php echo $categoria->nombre; ?>                
            </td>
            <td>
                <?php echo $categoria->fecha_registro; ?>
            </td>
            <td>
                <?php if ($categoria->estado == 0) { ?>
                <button class="btn blue btn_categoria" id="restore_<?php echo $categoria->id; ?>">Restaurar</button>
                <button class="btn red btn_categoria" id="purge_<?php echo $categoria->id; ?>">Purgar</button>
                <?php } ?>
            </td>
        </tr>
<?php
    if (!empty($children[$categoria->id])) {
        foreach($children[$categoria->id] as $child) {
?>
        <tr>
            <td></td>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child->nombre; ?>               
            </td>
            <td>
                <?php echo $child->fecha_registro; ?>
            </td>
            <td>
                <?php if ($child->estado == 0) { ?>
                <button class="btn blue btn_categoria" id="restore_<?php echo $child->id; ?>">Restaurar</button>
                <button class="btn red btn_categoria" id="purge_<?php echo $child->id; ?>">Purgar</button>
                <?php } ?>
            </td>
        </tr>
<?php
        }
    }
    $indice++;
}
?>        
    </tbody>
</table>
<div id="categoria-modal" style="display:none"></div>