<div class="header">
            <input type="checkbox" id="toggle">
            <label for="toggle"><img class="menu" src="img/menu.svg"></label>
            <nav class="navigation">
                <ul>
                    <li><a href="sistema.php"><i class="fas fa-home"></i>  Inicio</a></li>
                    <?php 
                        if($_SESSION['rol'] == 1){
                    ?>
                    <li><a><i class="fas fa-user-group"></i>  Usuarios ▾ </a>
                        <ul>
                            <li><a href="registro-usuario.php"><i class="fas fa-user-pen"></i> Nuevo Usuario</a></li>
                            <li><a href="lista-usuario.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
                        </ul>
                    </li>
                    <?php  } ?>
                    <?php 
                        if($_SESSION['rol'] == 1){
                    ?>
                    <li><a><i class="fas fa-building"></i> Proveedores ▾ </a>
                        <ul>
                            <li><a href="registro-proveedor.php"><i class="fas fa-plus"></i> Nuevo Proveedor</a></li>
                            <li><a href="lista-proveedor.php"><i class="fas fa-rectangle-list"></i> Lista de Proveedores</a></li>
                        </ul>
                    </li>
                    <?php  } ?>
                    <li><a><i class="fa-solid fa-cubes-stacked"></i> Productos ▾ </a>
                        <ul>
                        <?php 
                        if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
                        ?>
                            <li><a href="registro-producto.php"><i class="fas fa-plus"></i> Nuevo Producto</a></li>
                        <?php } ?>
                            <li><a href="lista-productos.php"><i class="fas fa-cubes"></i> Lista de Productos</a></li>
                        </ul>
                    </li>
                    <li><a href=""><i class="fas fa-file-alt"></i> Historial de entradas ▾ </a>
                        <ul>
                            <li><a href="lista-historial-entradas.php"><i class="fas fa-newspaper"></i> Entradas</a></li>
                        </ul
                    </li>
                </ul>
            </nav>
</div>