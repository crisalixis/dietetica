<?php 

if(empty($_SESSION['active'])) {
        header('location: index.php');
}

?>
<header>
	<div class="header-1">
	        <h1>Sistema Facturación</h1>
                        <div class="optionsBar">
                        <span class="user"><?php echo $_SESSION['user'].' -'.$_SESSION['rol'] ?></span>
                        <img class="photouser" src="img/user.svg" alt="Usuario">
                        <a href="salir.php"><img class="close" src="img/salir.svg" alt="Salir del sistema" title="Salir"></a>
                        </div>
	</div>
        <?php include "nav.php" ?>
</header>
<div class="modal">
       <div class="bodyModal">
                
       </div> 
</div>