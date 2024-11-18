<?php
    session_start();
    if($_SESSION['rol'] != 1) {
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST)){
        if(empty($_POST['idproveedor'])) {
            header("location: lista-proveedor.php");
        }

        $idproveedor = $_POST['idproveedor'];

        $query_delete = mysqli_query($conexion, "DELETE FROM proveedor WHERE codproveedor = $idproveedor");

        if($query_delete){
            header('Location: lista-proveedor.php');
        }else{
            echo "Error al eliminar";
        }
    }
    
    if(empty($_REQUEST['id'])){
        header('Location: lista-proveedor.php');
    }else{
        $idproveedor = $_REQUEST['id']; //el id viene de la URL
        
        $query = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor =  $idproveedor");
        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                $proveedor  = $data['proveedor'];
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
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre del proveedor: <span><?php echo $proveedor ?></span></p>
            <form class="form" action="" method="post">
                <input type="hidden" name="idproveedor" value="<?php echo $idproveedor ?>">
                <a href="lista-proveedor.php" class="btn-cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <button type="submit"class="btn-eliminar"><i class="fas fa-trash"></i> Aceptar</button>
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>