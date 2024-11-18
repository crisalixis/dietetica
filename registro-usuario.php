<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])){
            $alerta='<p class="msg-error">No se ingreso ningún dato.</p>';   
        }else{

            $nombre = $_POST['nombre'];
            $email  = $_POST['correo'];
            $user   = $_POST['usuario'];
            $clave  = $_POST['clave'];
            $rol    = $_POST['rol'];

            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ");

            $result = mysqli_fetch_array($query);

            if($result > 0){
                $alerta='<p class="msg-error">El correo o el usuario ya existen.</p>';   
            }else{
                $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave,rol) VALUES('$nombre','$email','$user','$clave','$rol')");

                if($query_insert){
                    $alerta='<p class="msg-save">Los datos fueron almacenados correctamente.</p>';  
                }else{
                    $alerta='<p class="msg-error">Error al almacenar los datos.</p>';   
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Registro Usuarios</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/header.php" ?>
    
	<section id="container">
		<div class="form-register">
            <h1><i class="fa-solid fa-user-plus"></i> Registro usuario</h1>
            <hr>
            <div class="alerta"><?php echo isset($alerta) ? $alerta :'' ?></div>

            <form action="" method="post" class="form">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo electronico">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario">
                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave de acceso">
                <label for="rol">Tipo Usuarios</label>

                <?php

                    $query_rol = mysqli_query($conexion, "SELECT * FROM rol");
                    $result_rol = mysqli_num_rows($query_rol);
                    
                    ?>
                <select name="rol" id="rol">
                    <?php
                        if($result_rol > 0){
                            while($rol = mysqli_fetch_array($query_rol)) {
                    ?>    
                            <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option> 
                    <?php
                            }
                        }
                    ?>
                </select>
                <input type="submit" value="Crear usuario" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>