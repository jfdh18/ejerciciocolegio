<?php
include 'includes/redirect.php';
include 'includes/header.php';
$estudiantes = mysqli_query($db, "SELECT * FROM estudiante");

$num_total_usuarios=mysqli_num_rows($estudiantes);
if ($num_total_usuarios>0) {
    $num_x_pagina = 3;
    $pagina = false;
    if (isset($_GET['pagina'])) {
        $pagina = $_GET['pagina'];
    }
    if (!$pagina) {
        $inicio = 0;
        $pagina = 1;
    } else {
        $inicio = ($pagina - 1) - $num_x_pagina;
    }

    $total_paginas = ceil($num_total_usuarios / $num_x_pagina);

    $sql = "SELECT * FROM users ORDER  BY (user_id) DESC LIMIT {$inicio},{$num_x_pagina}";
    $usuarios = mysqli_query($db, $sql);
}else{
    echo "No hay usuarios";
}
?>
    <table class="table">
        <tr>
            <th>Documento</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Direccion</th>
            <th>Acudiente</th>
            <th>Telefono Acudiente</th>
            <th></th>
        </tr>
        <?php while ($estudiante = mysqli_fetch_assoc($estudiantes)) { ?>
            <tr>
                <td><?=$estudiante["Documento"]?></td>
                <td><?=$estudiante["Nombres"]?></td>
                <td><?=$estudiante["Apellidos"]?></td>
                <td><?=$estudiante["Direccion"]?></td>
                <td><?=$estudiante["Acudiente"]?></td>
                <td><?=$estudiante["TelefonoAcudiente"]?></td>
                <td>
                    <a href="Detalles.php?Id=<?=$estudiante["IdEstudiante"]?>" class="btn btn-success">Ver</a>
                    <a href="Editar.php?Id=<?=$estudiante["IdEstudiante"]?>" class="btn btn-warning">Editar</a>
                    <a href="Eliminar.php?Id=<?=$estudiante["IdEstudiante"]?>" class="btn btn-danger">Eliminar</a>
                </td>
            </tr>
        <?php } ?>
    </table>

<?php if ($num_total_usuarios>1){?>
    <ul class="pagination">
        <li><a href="?pagina=<?=($pagina-1)?>"><<</a></li>

        <?php
        for ($i=0;$i<=$total_paginas;$i++){
            if ($pagina==$i){?>
                <li class="disabled"><a <?=$i?>"></a> </li>
            <?php } else{?>
                <li class="disabled"><a href="?pagina=<?=$i?>"></a> </li>
            <?php } ?>
        <?php } ?>
        <li> <a href="?pagina=<?php $show_paginas=($pagina+1); if($show_paginas<=$total_paginas){ echo $show_paginas;}?>">>></a>
        </li>
    </ul>
<?php } ?>

<?php include("includes/footer.php")?>