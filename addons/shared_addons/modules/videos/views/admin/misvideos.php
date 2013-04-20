<section class="title"> 
    <h2>Archivos subidos</h2>
</section>
<section class="item">
    <table>
        <tr>
            <th>#</th>
            <th>nombre</th>
            <th>tamaño</th>
        </tr>
        <?php foreach ($misvideos as $index=>$objArchivo): ?>
        <tr>
            <td><?php echo ($index+1); ?></td>
            <td><?php echo $objArchivo->nombre; ?></td>
            <td><?php echo $objArchivo->peso; ?></td>
            <td><?php echo $objArchivo->ruta; ?></td>
        </tr>
        <?php endforeach;?>
    </table>
</section>