<?php
session_start();
include "config/db.php";

// Validación de conexión a la base de datos
if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Lista de Proveedores</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <div id="container">
        <?php
        // Validar y sanitizar la búsqueda
        $busqueda = strtolower(trim($_REQUEST['busqueda'] ?? ''));
        $busqueda = mysqli_real_escape_string($conexion, $busqueda);

        if (empty($busqueda)) {
            header("Location: lista-proveedor.php");
            exit;
        }
        ?>
        <h1><i class="fas fa-cubes"></i> Historial de entradas</h1>
        <form action="buscar-historial-entrada.php" method="get" class="form-search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
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
            // Paginador
            $sql_register = mysqli_query($conexion, "SELECT COUNT(*) as total_registro FROM entradas WHERE (correlativo LIKE '%$busqueda%' OR fecha LIKE '%$busqueda%' OR codproducto LIKE '%$busqueda%')");
            if ($sql_register) {
                $result_register = mysqli_fetch_array($sql_register);
                $total_registro = $result_register['total_registro'] ?? 0;

                $por_pagina = 9;
                $pagina = intval($_GET['pagina'] ?? 1);
                $pagina = max(1, $pagina);

                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                $query = mysqli_query($conexion, "SELECT * FROM entradas WHERE (correlativo LIKE '%$busqueda%' OR fecha LIKE '%$busqueda%' OR codproducto LIKE '%$busqueda%') ORDER BY correlativo ASC LIMIT $desde, $por_pagina");

                if ($query && mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                        $formato = 'Y-m-d H:i:s';
                        $fecha = DateTime::createFromFormat($formato, $data["fecha"]);
                        ?>
                        <tr>
                            <td><?php echo $data['correlativo']; ?></td>
                            <td><?php echo $data['codproducto']; ?></td>
                            <td><?php echo $fecha ? $fecha->format('d-m-Y') : 'Fecha inválida'; ?></td>
                            <td><?php echo $data['cantidad']; ?></td>
                            <td><?php echo $data['precio']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No se encontraron resultados</td></tr>";
                }
            }
            ?>
        </table>
        <?php if ($total_registro > 1) { ?>
        <div class="paginador">
            <ul>
                <?php if ($pagina > 1) { ?>
                <li><a href="?pagina=1&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-backward-step"></i></a></li>
                <li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-angles-left"></i></a></li>
                <?php } ?>
                <?php 
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">'.$i.'</li>';
                    } else {
                        echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                    }
                }
                ?>
                <?php if ($pagina < $total_paginas) { ?>
                <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-angles-right"></i></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-forward-step"></i></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>
