<?php
//echo "--->empresa: ".$_SESSION['MM_Empresa']."<br>";
//echo "--->perfil: ".$_SESSION['MM_UserGroup'];

$linkusuarios = "";

switch($_SESSION['MM_UserGroup']){
case "1":
	$linkusuarios = "panelusuarios.php";
	break;
case "2":
	$linkusuarios = "panelusuarios.php?ID_EMPRESA=".$_SESSION['MM_Empresa'];
	break;
case "3":
	$linkusuarios = "";
	break;

}

?>
<ul id="jsddm">
	<li><a href="#" class="primero">USUARIOS</a>
		<ul>
	        <li><a href="perfil.php?ID_USUARIO=<?php echo $_SESSION['MM_UserID']; ?>">MI PERFIL</a></li>
			<!--<li><a href="#">CREAR NUEVO USUARIO</a></li>
			<li><a href="#">VER PERFILES</a></li>
            <li><a href="#">SESIONES ACTUALES</a></li>-->
            <?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?><li><a href="<?php echo $linkusuarios; ?>">LISTAR USUARIOS</a></li><?php } ?>
		</ul>
	</li>
    <?php if(in_array($_SESSION['MM_UserGroup'], array("1"))){ ?>
	<li><a href="#">EMPRESAS</a>
		<ul>
<!--			<li><a href="#">CREAR NUEVA EMPRESA</a></li>
			<li><a href="#">CREAR NUEVA DIVISION</a></li>
			<li><a href="#">ASIGNAR ADMINISTRADOR</a></li>
			<li><a href="#">HABILITAR PROVEEDORES</a></li>-->
			<li><a href="panelempresas.php">LISTAR EMPRESAS</a></li>
		</ul>
	</li>
    <?php } ?>
	<li><a href="#">LICITACIONES</a>
    	<ul>
			<!--<li><a href="#">CREAR NUEVA LICITACION</a></li>-->
			<?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?><li><a href="actividades.php">CALENDARIO</a></li><?php } ?>
			<li><?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?>
    <a href="panellicitaciones.php">LISTAR LICITACIONES</a>
    <?php }elseif(in_array($_SESSION['MM_UserGroup'], array("3"))){ ?>
    <a href="panellicitacionesproveedores.php">LISTAR LICITACIONES</a>
    <?php } ?>
            
            </li>
		</ul>
    </li>
    <li>
    <?php if(in_array($_SESSION['MM_UserGroup'], array("1"))){ ?>
    <a href="panel.php">PANEL DE CONTROL</a>
    <?php }elseif(in_array($_SESSION['MM_UserGroup'], array("3"))){ ?>
    <a href="panel_proveedor.php">PANEL DE CONTROL</a>
    <?php }elseif(in_array($_SESSION['MM_UserGroup'], array("2"))){ ?>
    <a href="panel_empresa.php">PANEL DE CONTROL</a>
    <?php } ?>
        <ul>
        <li>
    <?php if(in_array($_SESSION['MM_UserGroup'], array("1"))){ ?>
    <a href="noticias.php">NOTICIAS</a>
    <?php } ?>
    </li>
    </ul>
    </li>

	<li><a href="salir.php">SALIR</a></li>
</ul>
<div class="clear"> </div>