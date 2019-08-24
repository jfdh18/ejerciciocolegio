<?php
include 'includes/redirect.php';
include ("includes/header.php");
if (isset($_GET["Id"])){
$idestudiante=$_GET["Id"];
$consulta="SELECT * FROM estudiante WHERE IdEstudiante={$idestudiante}";
$estudiante=mysqli_query($db,$consulta);
}else{
    header("location:Index.php");
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
    </tr>
    <?php while ($e = mysqli_fetch_assoc($estudiante)) { ?>
        <tr>
            <td><?=$e["Documento"]?></td>
            <td><?=$e["Nombres"]?></td>
            <td><?=$e["Apellidos"]?></td>
            <td><?=$e["Direccion"]?></td>
            <td><?=$e["Acudiente"]?></td>
            <td><?=$e["TelefonoAcudiente"]?></td>
        </tr>
    <?php } ?>
</table>
