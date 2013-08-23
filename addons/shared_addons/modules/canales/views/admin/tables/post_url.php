<table>
    <thead>
        <tr>
            <th>Indice</th>
            <th>Logo</th>
            <th>Nombre</th>
            <th>Post URL</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
<?php
$indice = 1;
foreach($canales as $canal) {
?>
        <tr>
            <td>
                <?php echo $indice; ?>
            </td>
            <td>
                <img src="<?php echo $canal->logo; ?>" alt="<?php echo $canal->descripcion; ?>" title="<?php echo $canal->descripcion; ?>" />
            </td>
            <td>
                <?php echo $canal->nombre; ?>                
            </td>
            <td>
                <input class="input" id="input_post_<?php echo $canal->id; ?>" type="text" value="<?php echo $canal->post_url; ?>" />
            </td>
            <td>
                <button class="btn blue enviar_post" id="btn_post_<?php echo $canal->id; ?>">Enviar</button>
            </td>
        </tr>
<?php
    $indice++;
}
?>        
    </tbody>
</table>