<?php
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['nombre']) || empty($_POST['producto']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0){
            $alerta='<p class="msg-error">Todos los campos son obligatorios.</p>';   
        }else{

            $nombre_proveedor  = $_POST['nombre'];
            $producto   = $_POST['producto'];
            $precio   = $_POST['precio'];
            $cantidad  = $_POST['cantidad'];
            $usuario_id = $_SESSION['idUser'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];

            $imgProducto = 'img-producto.png';

            if($nombre_foto != ''){
                $destino = 'img/uploads/';
                $img_nombre = 'img_' .md5(date('d-m-Y H:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src = $destino.$imgProducto;
            }

            $query = mysqli_query($conexion, "INSERT INTO producto(nombre, descripcion, precio, existencia, usuario_id, foto) VALUES ('$nombre_proveedor','$producto','$precio','$cantidad','$usuario_id', '$imgProducto')");

                if($query){
                    if($nombre_foto != ''){
                        move_uploaded_file($url_temp, $src); 
                    }
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
                    $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, nombre FROM proveedor ORDER BY nombre ASC");
                    $resul_proveedor = mysqli_num_rows($query_proveedor);
                ?>
                <select name="nombre" id="nombre">
                <?php
                    if($resul_proveedor > 0){
                        while ($nombre_proveedor = mysqli_fetch_array($query_proveedor)){
                            //convierte el query en los option
                ?>
                <option value="<?php echo $nombre_proveedor['codproveedor']?>"><?php echo $nombre_proveedor['nombre']?></option>
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