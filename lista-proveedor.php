<?php
    session_start();
    if($_SESSION['rol'] != 1){
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
		
        <h1><i class="fas fa-rectangle-list"></i> Lista de Proveedores</h1>
        <a href="registro-proveedor.php" class="btn-new"><i class="fas fa-plus"></i> Crear proveedor</a>
        <form action="buscar-proveedor.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit"class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Proveedor</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
            <?php 
                //Paginador
                $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM proveedor");
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];

                $por_pagina = 6;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);


                $query = mysqli_query($conexion, "SELECT * FROM proveedor ORDER BY codproveedor ASC LIMIT $desde, $por_pagina");
                $result = mysqli_num_rows($query);

                if($result > 0){
                    while ($data = mysqli_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $data['codproveedor'] ?></td>
                            <td><?php echo $data['nombre'] ?></td>
                            <td><?php echo $data['contacto'] ?></td>
                            <td><?php echo $data['telefono'] ?></td>
                            <td><?php echo $data['direccion'] ?></td>
                            <td><?php echo $data['email'] ?></td>
                            <td>
                                <a href="editar-proveedor.php?id=<?php echo $data['codproveedor'] ?>" style="color: #126e00; padding: 5px;"><i class="fas fa-pencil"></i></a>
                                <a href="eliminar-proveedor.php?id=<?php echo $data['codproveedor'] ?>" style="color: #b11919; padding: 5px;"><i class="fas fa-trash"></i></a>
                            </td>
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