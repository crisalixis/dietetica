<?php
    session_start();
    include "config/db.php";
?>
<!DOCTYPE html>
<html lang="en"></html>
<head>
	<meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Proveedores</title>
    <?php include "includes/header.php" ?>
</head>
<body>
    
	<div id="container">
        <?ph

        $busqueda = strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("Location: lista-historial-entradas.php");
        }
        ?>
        <h1><i class="fas fa-cubes"></i> Historial de entradas</h1>
        <form action="buscar-historial-entrada.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda ?>">
            <button type="submit"class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <table>
            <tr>
                <th>Correlativo</th>
                <th>Producto</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Precio</th>
            </tr>
            <?php 
                //Paginador
                $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM entradas WHERE(correlativo LIKE '%$busqueda%' OR fecha LIKE '%$busqueda%')");
                
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'];

                $por_pagina = 9;
                
                if(empty($_GET['pagina'])){
                    $pagina = 1;
                }else{
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);


                //$query = mysqli_query($conexion, "SELECT e.correlativo, p.codproducto, e.fecha, e.cantidad, e.precio FROM entradas e INNER JOIN producto p ON e.codproducto = p.codproducto ORDER BY e.correlativo ASC LIMIT $desde, $por_pagina");
                //$query = mysqli_query($conexion, "SELECT correlativo, codproducto, fecha, cantidad, precio FROM entradas ORDER BY correlativo ASC LIMIT $desde, $por_pagina");
                $query = mysqli_query($conexion, "SELECT * FROM entradas WHERE(correlativo LIKE '%$busqueda%' OR fecha LIKE '%$busqueda%') ORDER BY correlativo ASC LIMIT $desde, $por_pagina");
                
                $result = mysqli_num_rows($query);

                if($result > 0){
                    while ($data = mysqli_fetch_array($query)){
                        $formato = 'Y-m-d H:i:s';
                        $fecha = DateTime::createFromFormat($formato, $data["fecha"]);
                    ?>
                        <tr>
                            <td><?php echo $data['correlativo'] ?></td>
                            <td><?php echo $data['codproducto'] ?></td>
                            <td><?php echo $fecha->format('d-m-Y')?></td>
                            <td><?php echo $data['cantidad'] ?></td>
                            <td><?php echo $data['precio'] ?></td>
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