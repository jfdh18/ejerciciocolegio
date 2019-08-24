
<?php
include 'includes/redirect.php';
require_once 'includes/header.php';
function mostrarError($error, $field){
    if(isset($error[$field]) && !empty($field)){
        $alerta='<div class="alert alert-danger">'.$error[$field].'</div>';
    }else{
        $alerta='';
    }
    return $alerta;
}
function setValueField($error,$field, $textarea=false){
    if(isset($error) && count($error)>=1 && isset($_POST[$field])){
        if($textarea != false){
            echo $_POST[$field];
        }else{
            echo "value='{$_POST[$field]}'";
        }
    }
}
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
    if(!empty($_POST["password"]) && strlen($_POST["password"]>=6)){
        $password_validador=true;
    }else{
        $password_validador=false;
        $error["password"]="Introduzca una contraseña de más de seis caracteres";
    }

$image=null;
if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
    if (!is_dir("uploads")) {
        $dir = mkdir("uploads", 0777, true);
    } else {
        $dir = true;
    }
    if ($dir) {
        $filename = time() . "-" . $_FILES["image"]["name"]; //concatenar función tiempo con el nombre de imagen
        $muf = move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $filename); //mover el fichero utilizando esta función
        $image = $filename;
        if ($muf) {
            $image_upload = true;
        } else {
            $image_upload = false;
            $error["image"] = "La imagen no se ha subido";
        }
    }
}

    if(count($error)==0){
        $sql= "INSERT INTO estudiante VALUES(NULL, '{$_POST["documento"]}', '{$_POST["nombre"]}', '{$_POST["apellidos"]}', '{$_POST["direccion"]}', '{$_POST["acudiente"]}', '{$_POST["telefonoacudiente"]}','{$image}', '".sha1($_POST["password"])."');";
        $insert_user=mysqli_query($db, $sql);
    }else{
        $insert_user=false;
    }
}
?>
<h1>Crear Usuarios</h1>
<?php if(isset($_POST["submit"]) && count($error)==0 && $insert_user !=false){?>
    <div class="alert alert-success">
        El usuario se ha creado correctamente !!
    </div>
<?php } ?>
<form action="Crear.php" method="POST" enctype="multipart/form-data">
    <label for="documento">Documento:
        <input type="text" name="documento" class="form-control" <?php setValueField($error, "documento");?>/>
        <?php echo mostrarError($error, "documento");?>
    </label>
    </br></br>
    <label for="nombre">Nombres:
        <input type="text" name="nombre" class="form-control" <?php setValueField($error, "nombre");?>/>
        <?php echo mostrarError($error, "nombre");?>
    </label>
    </br></br>
    <label for="apellidos">Apellidos:
        <input name="apellidos" class="form-control"><?php setValueField($error, "apellidos", true);?></input>
        <?php echo mostrarError($error, "apellidos");?>
    </label>
    </br></br>
    <label for="direccion">Direccion:
        <input type="text" name="direccion" class="form-control" <?php setValueField($error, "direccion");?>/>
        <?php echo mostrarError($error, "direccion");?>
    </label>
    </br></br>
    <label for="acudiente">Acudiente:
        <input type="text" name="acudiente" class="form-control" <?php setValueField($error, "acudiente");?>/>
        <?php echo mostrarError($error, "acudiente");?>
    </label>
    </br></br>
    <label for="telefonoacudiente">Telefono Acudiente:
        <input type="text" name="telefonoacudiente" class="form-control" <?php setValueField($error, "telefonoacudiente");?>/>
        <?php echo mostrarError($error, "telefonoacudiente");?>
    </label>
    </br></br>
    <label for="image">Foto:
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
<?php require_once 'includes/footer.php'; ?>
