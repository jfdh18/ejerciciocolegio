<?php
include 'includes/redirect.php';
require_once("includes/header.php");
function mostrarError($error, $field){
    if(isset($error[$field]) && !empty($field)){
        $alerta='<div class="alert alert-danger">'.$error[$field].'</div>';
    }else{
        $alerta='';
    }
    return $alerta;
}
function setValueField($datos,$field, $textarea=false){
    if(isset($datos) && count($datos)>=1){
        if($textarea != false){
            echo $datos[$field];
        }else{
            echo "value='{$datos[$field]}'";
        }
    }
}
//Buscar Usuario
if(!isset($_GET["Id"]) || empty($_GET["Id"]) || !is_numeric($_GET["Id"])){
    header("location:index.php");
}
$id=$_GET["Id"];
$consulta=mysqli_query($db, "SELECT * FROM Estudiante WHERE IdEstudiante={$id}");
$estudiante=mysqli_fetch_assoc($consulta);
if(!isset($estudiante["IdEstudiante"]) || empty($estudiante["IdEstudiante"])){
    header("location:index.php");
}
//Validar usuario
$error=array();
if(isset($_POST["submit"])){
    if(!empty($_POST["documento"]) && is_numeric($_POST["documento"])){
        $documento_validador=true;
    }else{
        $documento_validador=false;
        $error["documento"]="El documento no es válido";
    }
    if(!empty($_POST["nombre"]) && !is_numeric($_POST["nombre"])){
        $nombre_validador=true;
    }else{
        $nombre_validador=false;
        $error["nombre"]="El nombre no es válido";
    }
    if(!empty($_POST["apellidos"])&& !is_numeric($_POST["apellidos"])){
        $apellidos_validador=true;
    }else{
        $apellidos_validador=false;
        $error["apellidos"]="Los apellidos no son válidos";
    }
    if(!empty($_POST["direccion"])){
        $direccion_validador=true;
    }else{
        $direccion_validador=false;
        $error["direccion"]="La direccion no puede estar vacía";
    }
    if(isset($_POST["acudiente"])){
        $acudiente_validador=true;
    }else{
        $acudiente_validador=false;
        $error["acudiente"]="ingrese un nombre de algun acudiente";
    }
    if(isset($_POST["telefonoacudiente"])){
        $acudiente_validador=true;
    }else{
        $acudiente_validador=false;
        $error["telefonoacudiente"]="ingrese un telefono para su acudiente";
    }

    $image=null;
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
        if(!is_dir("uploads")){
            $dir = mkdir("uploads", 0777, true);
        }else{
            $dir=true;
        }
        if($dir){
            $filename= time()."-".$_FILES["image"]["name"]; //concatenar función tiempo con el nombre de imagen
            $muf=move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/".$filename); //mover el fichero utilizando esta función
            $image=$filename;
            if($muf){
                $image_upload=true;
            }else{
                $image_upload=false;
                $error["image"]= "La imagen no se ha subido";
            }
        }
        //var_dump($_FILES["image"]);
        //die();
    }
    //Actualizar Usuarios en la base de Datos
    if(count($error)==0){
        $sql= "UPDATE Estudiante set Documento='{$_POST["documento"]}',"
            . "Nombres= '{$_POST["nombre"]}',"
            . "Apellidos= '{$_POST["apellidos"]}',"
            . "Direccion= '{$_POST["direccion"]}',"
            . "Acudiente= '{$_POST["acudiente"]}',"
            . "TelefonoAcudiente= '{$_POST["telefonoacudiente"]}',";


        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])){
            $sql.= "Foto='{$image}',";
        }
        if(isset($_POST["password"]) && !empty($_POST["password"])){
            $sql.= "password='".sha1($_POST["password"])."' ";
        }
        $sql.= "WHERE IdEstudiante={$estudiante["IdEstudiante"]}";
        $actualizar=mysqli_query($db, $sql);
        if($actualizar){
            $consultaestudiante=mysqli_query($db, "SELECT * FROM Estudiante WHERE IdEstudiante={$id}");
            $estudiante=mysqli_fetch_assoc($consultaestudiante);
        }
    }else{
        $actualizar=false;
    }
}
?>
<h2>Editar Usuario <?php echo $estudiante["IdEstudiante"]."-".$estudiante["Nombres"]." ".$estudiante["Apellidos"];?></h2>
<?php if(isset($_POST["submit"]) && count($error)==0 && $actualizar !=false){?>
    <div class="alert alert-success">
        El usuario se ha actualizado correctamente !!
    </div>
<?php }elseif(isset($_POST["submit"])){?>
    <div class="alert alert-danger">
        El usuario NO se ha actualizado correctamente !!
    </div>
<?php } ?>
<form action="" method="POST" enctype="multipart/form-data">
    <label for="documento">Documento:
        <input type="text" name="documento" class="form-control" <?php setValueField($estudiante, "Documento");?>/>
        <?php echo mostrarError($error, "documento");?>
    </label>
    </br></br>
    <label for="nombre">Nombres:
        <input type="text" name="nombre" class="form-control" <?php setValueField($estudiante, "Nombres");?>/>
        <?php echo mostrarError($error, "nombre");?>
    </label>
    </br></br>
    <label for="apellidos">Apellidos:
        <input name="apellidos" class="form-control" <?php setValueField($estudiante, "Apellidos");?>/>
        <?php echo mostrarError($error, "apellidos");?>
    </label>
    </br></br>
    <label for="direccion">Direccion:
        <input type="text" name="direccion" class="form-control" <?php setValueField($estudiante, "Direccion");?>/>
        <?php echo mostrarError($error, "direccion");?>
    </label>
    </br></br>
    <label for="acudiente">Acudiente:
        <input type="text" name="acudiente" class="form-control" <?php setValueField($estudiante, "Acudiente");?>/>
        <?php echo mostrarError($error, "acudiente");?>
    </label>
    </br></br>
    <label for="telefonoacudiente">Telefono Acudiente:
        <input type="text" name="telefonoacudiente" class="form-control" <?php setValueField($estudiante, "TelefonoAcudiente");?>/>
        <?php echo mostrarError($error, "telefonoacudiente");?>
    </label>
    </br></br>
    <label for="image">
        <?php if($estudiante["Foto"] != null){?>
            Imagen de Perfil: <img src="uploads/<?php echo $estudiante["image"] ?>" width="100"/><br/>
        <?php } ?>
        Actualizar foto de Perfil:
        <input type="file" name="image" class="form-control"/>
    </label>
    </br></br>
    <label for="password">Contraseña:
        <input type="password" name="password" class="form-control"/>
        <?php echo mostrarError($error, "password");?>
    </label>
    </br></br>
    <input type="submit" value="Enviar" name="submit" class="btn btn-success"/>
</form>
<?php require_once("includes/footer.php")?>
