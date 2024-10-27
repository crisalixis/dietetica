<?php
    session_start();
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2 and $_SESSION['rol'] != 3){
        header("location: sistema.php");
    }
    include "config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Proveedores</title>
</head>
<body>
    <?php include "includes/header.php" ?>
	<section id="container">
		<?php
            $busqueda = '';
            $search_proveedor= '';
            if(empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])){
                header("location: lista-productos.php");
            }
            if(!empty($_REQUEST['busqueda'])){
                $busqueda = strtolower($_REQUEST['busqueda']); //convierte a minuscula
            }
            if(!empty($_REQUEST['proveedor'])){
                $search_proveedor = $_REQUEST['proveedor'];
            }
        ?>
        <h1><i class="fas fa-cubes"></i> Lista de Productos</h1>
        <a href="registro-producto.php" class="btn-new"><i class="fas fa-plus"></i> Crear producto</a>
        <form action="buscar-producto.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit"class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <table>
            <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>
                <?php
                    $pro = 0;
                    
                    if(!empty($_REQUEST['proveedor'])){
                        $pro = $_REQUEST['proveedor'];
                    }

                    $query_proveedor = mysqli_query($conexion, "SELECT codproveedor, proveedor FROM proveedor WHERE estado = 1 ORDER BY proveedor ASC");
                    $resul_proveedor = mysqli_num_rows($query_proveedor);
                ?>
                <select name="proveedor" id="search-proveedor">
                <?php
                    if($resul_proveedor > 0){
                        while ($proveedor = mysqli_fetch_array($query_proveedor)){
                            if($pro == $proveedor['codproveedor']){
                ?>
                    <option value="<?php echo $proveedor['codproveedor']?>" selected><?php echo $proveedor['proveedor']?></option>
                <?php
                            }else{
                ?>
                    <option value="<?php echo $proveedor['codproveedor']?>"><?php echo $proveedor['proveedor']?></option>
                <?php
                            }
                        }
                    }
                ?>
                </select>
                </th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
            <?php 
                //Paginador
                $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM producto WHERE estado = 1");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];

                $por_pagina = 5;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);


                $query = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto FROM producto p INNER JOIN proveedor pr ON p.proveedor = pr.codproveedor WHERE p.estado = 1 ORDER BY p.codproducto ASC LIMIT $desde, $por_pagina");
                $result = mysqli_num_rows($query);

                if($result > 0){
                    while ($data = mysqli_fetch_array($query)){
                        if($data['foto'] != 'img-producto.png'){
                            $foto = 'img/uploads/'.$data['foto'];
                        }else{
                            $foto = 'img/'.$data['foto'];
                        }
                    ?>
                        <tr class="row<?php echo $data['codproducto'] ?>" >
                            <td><?php echo $data['codproducto'] ?></td>
                            <td><?php echo $data['descripcion'] ?></td>
                            <td class="precioC"><?php echo $data['precio'] ?></td>
                            <td class="existenciaC"><?php echo $data['existencia'] ?></td>
                            <td><?php echo $data['proveedor'] ?></td>
                            <td><img src="<?php echo $foto ?>" alt="<?php echo $data['descripcion'] ?>" style=" height: 80px; width: 80px; margin: auto"></td>
                            <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){?>
                            <td>
                                <a href="" class="add-product" product="<?php echo $data['codproducto'] ?>" style="color: #002f6e; padding: 5px;"><i class="fas fa-plus"></i></a>
                                <a href="editar-producto.php?id=<?php echo $data['codproducto'] ?>" style="color: #126e00; padding: 5px;"><i class="fas fa-pencil"></i></a>
                                <a href="" class="del-product" product="<?php echo $data['codproducto'] ?>" style="color: #b11919; padding: 5px;"><i class="fas fa-trash"></i></a>
                            </td>
                            <?php }?>
                        </tr>
                <?php
                    }
                }
                ?>
        </table>
        <div class="paginador">
            <ul>
                <?php
                    if($pagina != 1){

                ?>
                <li><a href="?pagina=<?php echo 1 ?>"><i class="fas fa-backward-step"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1 ?>"><i class="fas fa-angles-left"></i></a></li>
                <?php                        
                }   

                    for ($i=1; $i <= $total_paginas; $i++){
                        if($i == $pagina){
                            echo '<li class="pageSelected">'.$i.'</li>';
                        }else{
                            echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                        }
                    }
                
                    if($pagina != $total_paginas){

                ?>
                
                <li><a href="?pagina=<?php echo $pagina + 1 ?>"><i class="fas fa-angles-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas ?>"><i class="fas fa-forward-step"></i></a></li>
                <?php } ?>
            </ul>
        </div>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>