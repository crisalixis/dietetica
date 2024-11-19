<?php
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: sistema.php");
    }
    include "config/db.php";

    if(!empty($_POST))
    {
        $alerta='';
        if(empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['precio']) || empty($_POST['id']) || empty($_POST['foto-actual']) || empty($_POST['foto-remove'])){
            $alerta='<p class="msg-error">Todos los campos son obligatorios.</p>';   
        }else{
            $codproducto = $_POST['id'];
            $nombre_proveedor  = $_POST['nombre'];
            $descripcion   = $_POST['descripcion'];
            $precio   = $_POST['precio'];
            $imgProducto  = $_POST['foto-actual'];
            $imgRemove = $_POST['foto-remove'];

            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_temp = $foto['tmp_name'];

            $upd = '';

            if($nombre_foto != ''){
                $destino = 'img/uploads/';
                $img_nombre = 'img_' .md5(date('d-m-Y H:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src = $destino.$imgProducto;
            }else{
                if($_POST['foto-actual'] != $_POST['foto-remove']){
                    $imgProducto = 'img-producto.png';
                }
            }

            $query_update = mysqli_query($conexion, "UPDATE producto SET descripcion = '$descripcion', nombre = $nombre_proveedor, precio = $precio, foto = '$imgProducto' WHERE codproducto = $codproducto");

                if($query_update){

                    if(($nombre_foto != '' && ($_POST['foto-actual'] != 'img-producto.png')) || ($_POST['foto-actual'] != $_POST['foto-remove'])){ 
                        unlink('img/uploads/'.$_POST['foto-actual']); //elimina
                    }

                    if($nombre_foto != ''){
                        move_uploaded_file($url_temp, $src); //crea la foto
                    }

                    $alerta='<p class="msg-save">Los datos fueron actualizados correctamente.</p>';  
                }else{
                    $alerta='<p class="msg-error">Error al actualizar los datos.</p>';   
                }
            }
        }

        //Validar producto si existe
        if(empty($_REQUEST['id'])){
            header("location: lista-productos.php"); //si no existe se redirecciona
        }else{
            $id_producto = $_REQUEST['id']; //viene de la url del archivo lista
            
            if(!is_numeric($id_producto)){
                header("location: lista-productos.php"); //si no es numero se redirecciona
            }

            $query_producto = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion, p.precio, p.foto,  pr.codproveedor, pr.nombre FROM producto p INNER JOIN proveedor pr ON p.nombre = pr.codproveedor WHERE p.codproducto = $id_producto");
            $result_producto = mysqli_num_rows($query_producto);

            $foto = '';
            $classRemove = 'notBlock';

            if($result_producto > 0){
                $data_producto = mysqli_fetch_assoc($query_producto); //toma el array de datos y lo almacena en la variable
                
                if($data_producto['foto'] != 'img-producto.png'){
                    $classRemove = '';
                    $foto = '<img id="img" src="img/uploads/'.$data_producto['foto'].'" alt="Producto">';
                }
            }else{
                header("location: lista-productos.php"); //si no esta activo el archivo se redirecciona
            }
        }


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar Producto</title>
</head>
<body>
    <?php include "includes/header.php" ?>
    
	<section id="container">
		<div class="form-register">
            <h1><i class="fa-solid fa-cubes-stacked"></i> Actualizar Producto</h1>
            <hr>
            <div class="alerta"><?php echo isset($alerta) ? $alerta :'' ?></div>

            <form action="" method="post" class="form" enctype="multipart/form-data"><!--multipart/form-data sirve para que se puedan adjuntar imagenes o otro tipo de archivo-->
                <input type="hidden" name="id" value="<?php echo $data_producto['codproducto']?>">
                <input type="hidden" id="foto-actual" name="foto-actual" value="<?php echo $data_producto['foto']?>">
                <input type="hidden" id="foto-remove" name="foto-remove" value="<?php echo $data_producto['foto']?>">
                
                <label for="proveedor">Nombre del proveedor</label>
                <?php
                    $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, nombre FROM proveedor ORDER BY nombre ASC");
                    $resul_proveedor = mysqli_num_rows($query_proveedor);
                ?>
                <select name="proveedor" id="proveedor" class="notItemOne">
                    <option value="<?php echo $data_producto['codproveedor']?>" selected><?php echo $data_producto['nombre']?></option>
                <?php
                
                    if($resul_proveedor > 0){
                        while ($proveedor = mysqli_fetch_array($query_proveedor)){
                            //convierte el query en los option
                ?>
                    <option value="<?php echo $proveedor['codproveedor']?>"><?php echo $proveedor['nombre']?></option>
                <?php
                        }
                    }
                ?>
                </select>

                <label for="descripcion">Producto</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']?>">
                
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio del producto" value="<?php echo $data_producto['precio']?>">
            
                <div class="photo">
	                <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto <?php echo $classRemove ?>">X</span>
                        <label for="foto"></label>
                        <?php echo $foto ?>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                        <div id="form_alert"></div>
                </div>

                <input type="submit" value="Actualizar Producto" class="btn-save">
            </form>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>