<?php
    session_start();
    if($_SESSION['rol'] != 1){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST)){
        if($_POST['idusuario'] == 1){
            header('Location: lista-usuario.php');
            exit;
        }
        
        $idusuario = $_POST['idusuario'];

        //$query_delete = mysqli_query($conexion, "DELETE FROM usuario WHERE idusuario = $idusuario");
        $query_delete = mysqli_query($conexion, "UPDATE usuario SET estado = 0 WHERE idusuario = $idusuario");

        if($query_delete){
            header('Location: lista-usuario.php');
        }else{
            echo "Error al eliminar";
        }
    }
    
    if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){
        header('Location: lista-usuario.php');
    }else{
        include "config/db.php";

        $idusuario = $_REQUEST['id'];
        $query = mysqli_query($conexion, "SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario =  $idusuario");
        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $nombre  = $data['nombre'];
                $usuario = $data['usuario'];
                $rol     = $data['rol'];
            }
        }else{
                header("location: lista-usuario.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/header.php" ?>
	<section id="container">
		<div class="data-delete">
            <h2>¿Esta seguro de eliminar los siguientes datos?</h2>
            <p>Nombre: <span><?php echo $nombre ?></span></p>
            <p>Usuario: <span><?php echo $usuario ?></span></p>
            <p>Tipo de rol: <span><?php echo $rol ?></span></p>

            <form class="form" action="" method="post">
                <input type="hidden" name="idusuario" value="<?php echo $idusuario ?>">
                <a href="lista-usuario.php" class="btn-cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <button type="submit"class="btn-eliminar"><i class="fas fa-trash"></i> Aceptar</button>
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>