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
		<?php

            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("Location: lista-usuario.php");
            }
        ?>
        <h1><i class="fas fa-user-group"></i> Lista de usuarios</h1>
        <a href="registro-usuario.php" class="btn-new"><i class="fas fa-user-plus"></i> Crear usuario</a>
        <form action="buscar-usuario.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda ?>">
            <button type="submit"class="btn-search"><i class="fas fa-magnifying-glass"></i></button>
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
                $rol = '';
                if($busqueda == 'administrador'){

                    $rol = " OR rol LIKE '%1%' ";

                }else if($busqueda == 'supervisor'){

                    $rol = " OR rol LIKE '%2%' ";

                }else if($busqueda == 'vendedor' || $busqueda == 'ven' || $busqueda == 've' || $busqueda == 'v' || $busqueda == 'dor' || $busqueda == 'd' || $busqueda == 'de' || $busqueda == 'en' || $busqueda == 'r'){

                    $rol = " OR rol LIKE '%3%' ";

                }

                $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM usuario WHERE (idusuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' $rol) AND estado = 1");
                
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


                $query = mysqli_query($conexion, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE (u.idusuario LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%' OR u.correo LIKE '%$busqueda%' OR u.usuario LIKE '%$busqueda%' OR r.rol LIKE '%$busqueda%') AND estado = 1 ORDER BY idusuario ASC LIMIT $desde, $por_pagina");

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
        <?php
            if($total_registro != 1){
        ?>
        <div class="paginador">
            <ul>
                <?php
                    if($pagina != 1){

                ?>
                <li><a href="?pagina=<?php echo 1 ?>&busqueda=<?php echo $busqueda?>"><i class="fas fa-backward-step"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1 ?>&busqueda=<?php echo $busqueda?>"><i class="fas fa-angles-left"></i></a></li>
                <?php                        
                }   
                    for ($i=1; $i <= $total_paginas; $i++){
                        if($i == $pagina){
                            echo '<li class="pageSelected">'.$i.'</li>';
                        }else{
                            echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                        }
                    }
                
                    if($pagina != $total_paginas){

                ?>
                
                <li><a href="?pagina=<?php echo $pagina + 1 ?>"><i class="fas fa-angles-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas ?>"><i class="fas fa-forward-step"></i></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
	</section>
	<?php include "includes/footer.php" ?>
</body>
</html>