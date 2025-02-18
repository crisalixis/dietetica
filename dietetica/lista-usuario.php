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
    <title>Sistema de Ventas</title>
</head>
<body>
    <?php include "includes/header.php" ?>
	<section id="container">
		
        <h1><i class="fas fa-user-group"></i> Lista de usuarios</h1>
        <a href="registro-usuario.php" class="btn-new"><i class="fa-solid fa-user-plus"></i> Crear usuario</a>
        <form action="buscar-usuario.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit"class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php 
                //Paginador
                $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM usuario WHERE estado = 1");
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


                $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE estado = 1 ORDER BY idusuario ASC LIMIT $desde, $por_pagina");
                $result = mysqli_num_rows($query);

                if($result > 0){
                    while ($data = mysqli_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $data['idusuario'] ?></td>
                            <td><?php echo $data['nombre'] ?></td>
                            <td><?php echo $data['correo'] ?></td>
                            <td><?php echo $data['usuario'] ?></td>
                            <td><?php echo $data['rol'] ?></td>
                            <td>
                                <a href="editar-usuario.php?id=<?php echo $data['idusuario'] ?>" style="color: #126e00; padding: 5px;"><i class="fas fa-pencil"></i></a>
                                <?php if($data['idusuario'] != 1){ ?>
                                    <a href="eliminar-usuario.php?id=<?php echo $data['idusuario'] ?>" style="color: #b11919; padding: 5px;"><i class="fas fa-trash"></i></a>
                                <?php } ?>
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