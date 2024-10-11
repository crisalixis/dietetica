<?php

$alerta = "";
session_start();
if(!empty($_SESSION['active'])) {
        header('location: sistema.php');
}else{
    if(!empty($_POST)){
       if(empty($_POST['usuario']) || empty($_POST['clave'])){
            $alerta = 'Ingrese su contraseña y usuario';
       }else{
            require_once "config/db.php";

            $user = $_POST['usuario'];
            $pass = $_POST['clave'];

            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$user' AND clave= $pass");
            mysqli_close($conexion);
            $result = mysqli_num_rows($query);

            if($result > 0){
                $data =  mysqli_fetch_array($query);

                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['correo'] = $data['correo'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['rol'];

                header('location: sistema.php');
            }else{
                $alerta = 'El usuario o la clave son incorrectos';
                session_destroy();
            }
       }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema de facturacion Dietetica</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="portada-1">
    <section class="formulario">
        <form action="" method="post">
            <h2>Iniciar sesion</h2>
            <input type="text" class="controlador" name="usuario" placeholder="Usuario">
            <input type="password" class="controlador" name="clave" placeholder="Contraseña">
            <div class="alerta"><?php echo (isset($alerta) ? $alerta : '') ?></div>
            <input type="submit" class="boton" value="Enviar">
        </form>
    </section>
</body>
</html>