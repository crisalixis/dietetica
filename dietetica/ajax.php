<?php
    include 'config/db.php';
    //print_r($_POST)
    if(!empty($_POST)){
        //Extraer datos del producto
        if($_POST['action'] == 'infoProducto'){
            $producto_id = $_POST['producto'];

            $query = mysqli_query($conexion, "SELECT codproducto,descripcion FROM producto WHERE codproducto = $producto_id AND estado = 1");
            $result = mysqli_num_rows($query);

            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }
    }

    exit;
?>