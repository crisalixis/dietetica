<?php
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])){
            $alerta='<p class="msg-error">Todos los campos son obligatorios.</p>';   
        }else{

            $proveedor  = $_POST['proveedor'];
            $contacto   = $_POST['contacto'];
            $telefono   = $_POST['telefono'];
            $direccion  = $_POST['direccion'];
            $usuario_id = $_SESSION['idUser'];

            $query = mysqli_query($conexion, "INSERT INTO proveedor(proveedor, contacto, telefono, direccion, usuario_id ) VALUES ('$proveedor','$contacto','$telefono','$direccion','$usuario_id')");

                if($query){
                    $alerta='<p class="msg-save">Los datos fueron almacenados correctamente.</p>';  
                }else{
                    $alerta='<p class="msg-error">Error al almacenar los datos.</p>';   
                }
            }
        }

        
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Registro Proveedor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/header.php" ?>
    
	<section id="container">
		<div class="form-register">
            <h1><i class="fa-solid fa-building"></i> Registro Proveedor</h1>
            <hr>
            <div class="alerta"><?php echo isset($alerta) ? $alerta :'' ?></div>

            <form action="" method="post" class="form">
                <label for="proveedor">Nombre del proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor">
                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del contacto">
                <label for="Telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Telefono">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección completa">
                <label for="email">Correo Electronico</label>
                <input type="correo" name="email" id="email" placeholder="Correo Electronico">
                <input type="submit" value="Guardar proveedor" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>