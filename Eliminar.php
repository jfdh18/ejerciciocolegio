<?php
include ("includes/header.php");
if (isset($_GET["Id"])){
    $idestudiante=$_GET["Id"];
    $eliminar="DELETE FROM estudiante WHERE IdEstudiante={$idestudiante} ";
    $d=mysqli_query($db,$eliminar);
    if ($d!=0){
        header("location:Index.php");
    }
}else{
    header("location:Index.php");
}
?>