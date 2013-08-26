<a class="btn blue btn_categoria" id="new_categoria">Nuevo</a>
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
                <button class="btn blue btn_categoria" id="edit_<?php echo $categoria->id; ?>">Editar</button>
                <button class="btn red btn_categoria" id="delete_<?php echo $categoria->id; ?>">Eliminar</button>
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
                <button class="btn blue btn_categoria" id="edit_<?php echo $child->id; ?>">Editar</button>
                <button class="btn red btn_categoria" id="delete_<?php echo $child->id; ?>">Eliminar</button>
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