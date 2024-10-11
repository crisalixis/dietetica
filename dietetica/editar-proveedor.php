<?php 
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['email'])){
            $alerta='<p class="msg-error">Todos los datos son obligarios.</p>';   
        }else{
            
            $idproveedor = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $contacto  = $_POST['contacto'];
            $telefono   = $_POST['telefono'];
            $direccion  = $_POST['direccion'];
            $email    = $_POST['email'];

            $sql_update = mysqli_query($conexion, "UPDATE proveedor SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion', email = '$email 'WHERE codproveedor = $idproveedor");


                if($sql_update > 0){
                    $alerta='<p class="msg-save">Los datos fueron actualizados correctamente.</p>';   
                }else{
                    $alerta='<p class="msg-error">Error al actualizar los datos.</p>';   
                }
    }
    }

    //Mostrar Datos
    if(empty($_GET['id'])){
        header("Location: lista-proveedor.php");
    }
    $idproveedor = $_GET['id'];

    $sql = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $idproveedor and estado = 1");
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header("Location: lista-proveedor.php");
    }else{
        while($data = mysqli_fetch_array($sql)){
            $idproveedor = $data['codproveedor'];
            $proveedor  = $data['proveedor'];
            $contacto  = $data['contacto'];
            $telefono = $data['telefono'];
            $direccion   = $data['direccion'];
            $email     = $data['email'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar datos del proveedor</title>
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
                <input type="hidden" name="id" value="<?php echo $idproveedor ?>">
                <label for="proveedor">Nombre del proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor" value="<?php echo $proveedor ?>">
                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del contacto" value="<?php echo $contacto ?>">
                <label for="Telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono ?>">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección completa" value="<?php echo $direccion ?>">
                <label for="email">Correo Electronico</label>
                <input type="correo" name="email" id="email" placeholder="Correo Electronico" value="<?php echo $email ?>">
                <input type="submit" value="Guardar proveedor" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>