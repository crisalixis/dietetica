<?php
    include 'config/db.php';
    session_start();
    //print_r($_POST); exit;
    // exit;
    if(!empty($_POST)){
        //Extraer datos del producto
        if($_POST['action'] == 'infoProducto'){
            $producto_id = $_POST['producto'];

            $query = mysqli_query($conexion, "SELECT codproducto,descripcion FROM producto WHERE codproducto = $producto_id");
            $result = mysqli_num_rows($query);

            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }
        //Agregar productos y procedimiento
        if($_POST['action'] == 'addProduct'){
            if(!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])){
                $cantidad = $_POST['cantidad'];
                $precio   = $_POST['precio'];
                $producto_id = $_POST['producto_id'];
                $usuario_id = $_SESSION['idUser'];

                $query_insert = mysqli_query($conexion, "INSERT INTO entradas (codproducto, cantidad, precio, usuario_id) VALUES ($producto_id, $cantidad, $precio, $usuario_id)");

                if($query_insert){
                    //ejecutar procedimiento almacenado
                    $query_upd = mysqli_query($conexion, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                    $result_pro = mysqli_num_rows($query_upd);

                    if($result_pro > 0){
                        $data = mysqli_fetch_assoc($query_upd);
                        $data['producto_id'] = $producto_id;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }else{
                    echo 'error';
                }
            }else{
                echo 'error';
            }
            exit;
        }
        //Eliminar datos del producto
        if($_POST['action'] == 'delProduct'){
            if(empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])){
                echo 'error';
                exit;
            }else{
                $idproducto = $_POST['producto_id'];

                $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = $idproducto");
        
                if($query_delete){
                    echo 'ok';
                    exit;
                }else{
                    echo 'Error al eliminar';
                    exit;
                }
            }
            echo 'error';
        }
        exit;
    }

    //print_r($_POST);
    exit;
?>