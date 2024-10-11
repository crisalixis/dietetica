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
    <title>Registro Producto</title>
</head>
<body>
    <?php include "includes/header.php" ?>
    
	<section id="container">
		<div class="form-register">
            <h1><i class="fa-solid fa-cubes-stacked"></i> Registro Producto</h1>
            <hr>
            <div class="alerta"><?php echo isset($alerta) ? $alerta :'' ?></div>

            <form action="" method="post" class="form" enctype="multipart/form-data"><!--multipart/form-data sirve para que se puedan adjuntar imagenes o otro tipo de archivo-->
                
                <label for="proveedor">Nombre del proveedor</label>
                
                <?php
                    $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor WHERE estado = 1 ORDER BY proveedor ASC");
                    $resul_proveedor = mysqli_num_rows($query_proveedor);
                ?>
                <select name="proveedor" id="proveedor">
                <?php
                    if($resul_proveedor > 0){
                        while ($proveedor = mysqli_fetch_array($query_proveedor)){
                            //convierte el query en los option
                ?>
                <option value="<?php echo $proveeodr['codproveedor']?>"><?php echo $proveedor['proveedor']?></option>
                <?php
                        }
                    }
                ?>
                </select>

                <label for="producto">Producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto">
                
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto">
                
                <label for="direccion">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del producto">
            
                <div class="photo">
	                <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                        <div id="form_alert"></div>
                </div>

                <input type="submit" value="Guardar Producto" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>