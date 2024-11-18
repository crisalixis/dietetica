<?php 
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])){
            $alerta='<p class="msg-error">No se ingreso ningún dato.</p>';   
        }else{
            
            $idusuario = $_POST['idusuario'];
            $nombre = $_POST['nombre'];
            $email  = $_POST['correo'];
            $user   = $_POST['usuario'];
            $clave  = $_POST['clave'];
            $rol    = $_POST['rol'];

            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE (usuario = '$user' AND idusuario != $idusuario) OR (correo = '$email' AND idusuario != $idusuario)");
            $result = mysqli_fetch_array($query);


            if($result > 0){
                $alerta='<p class="msg-error">El correo o el usuario ya existen.</p>';   
            }else{
                if(empty($_POST['clave'])){
                    $sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol' WHERE idusuario = $idusuario");
                }else{
                    $sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre', correo = '$email', clave = '$clave', usuario = '$user', rol = '$rol' WHERE idusuario = $idusuario");

                }

                if($sql_update > 0){
                    $alerta='<p class="msg-save">Los datos fueron actualizados correctamente.</p>';   
                }else{
                    $alerta='<p class="msg-error">Error al actualizar los datos.</p>';   
                }
            }
        }
    }

    //Mostrar Datos
    if(empty($_GET['id'])){
        header("Location: lista-usuario.php");
    }
    $iduser = $_GET['id'];

    //$sql = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE idusuario = $iduser");
    $sql = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE idusuario = $iduser");
    
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header("Location: lista-usuario.php");
    }else{
        $option = '';
        while($data = mysqli_fetch_array($sql)){
            $iduser  = $data['idusuario'];
            $nombre  = $data['nombre'];
            $correo  = $data['correo'];
            $usuario = $data['usuario'];
            $idrol   = $data['idrol'];
            $rol     = $data['rol'];

            if($idrol == 1){
               $option = '<option value="'.$idrol.'" select>'.$rol.'</option>'; 
            }else if($idrol == 2){
               $option = '<option value="'.$idrol.'" select>'.$rol.'</option>'; 
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar datos del usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/header.php" ?>
    
	<section id="container">
		<div class="form-register">
            <h1><i class="fas fa-pencil"></i> Actualizar datos</h1>
            <hr>
            <div class="alerta"><?php echo isset($alerta) ? $alerta :'' ?></div>

            <form action="" method="post" class="form">
                <input type="hidden" name="idusuario" id="id" value="<?php echo $iduser ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre ?>">
                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo electronico" value="<?php echo $correo ?>">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario?>">
                <label for="clave">Constraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Contraseña" value="">
                <label for="rol">Tipo Usuarios</label>

                <?php
                    include "config/db.php";
                    $query_rol = mysqli_query($conexion, "SELECT * FROM rol");
                    $result_rol = mysqli_num_rows($query_rol);
                    
                    ?>
                <select name="rol" id="rol" class="notItemOne">
                    <?php
                        echo $option;
                        if($result_rol > 0){
                            while($rol = mysqli_fetch_array($query_rol)) {
                    ?>    
                            <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option> 
                    <?php
                            }
                        }
                    ?>
                </select>
                <input type="submit" value="Actualizar datos" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>