<table>
    <thead>
        <tr>
            <th>Indice</th>
            <th>Logo</th>
            <th>Nombre</th>
            <th>API Key</th>
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
                <input class="input_key_canal" id="key_canal_<?php echo $canal->id; ?>" type="text" value="<?php echo $canal->key_canal; ?>" readonly />
            </td>
            <td>
                <button class="btn blue key_canal" id="btn_canal_<?php echo $canal->id; ?>">Generar</button>
            </td>
        </tr>
<?php
    $indice++;
}
?>        
    </tbody>
</table>