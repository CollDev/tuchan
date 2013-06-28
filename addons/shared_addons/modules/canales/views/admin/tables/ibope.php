<table>
    <thead>
        <tr>
            <th>Indice</th>
            <th>Logo</th>
            <th>Nombre</th>
            <th>texto</th>
            <th>boton</th>
            <th>Id</th>
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
                <input class="input" id="input_canal_<?php echo $canal->id; ?>" type="text"/>
            </td>
            <td>
                <button class="btn blue enviar_canal" id="btn_canal_<?php echo $canal->id; ?>">Enviar</button>
            </td>
            <td>
                <?php echo $canal->id; ?>                
            </td>
        </tr>
<?php
    $indice++;
}
?>        
    </tbody>
</table>