<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php

$colname_rsListadoUsuarios = "-1";
if (isset($_GET['ID_EMPRESA'])) {
  $colname_rsListadoUsuarios = $_GET['ID_EMPRESA'];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoUsuarios = sprintf("SELECT * FROM listar_maestro_usuarios WHERE ID_EMPRESA = %s ORDER BY NOMBRE ASC", $colname_rsListadoUsuarios);
$rsListadoUsuarios = mysql_query($query_rsListadoUsuarios, $cn_licitaciones) or die(mysql_error());
$row_rsListadoUsuarios = mysql_fetch_assoc($rsListadoUsuarios);
$totalRows_rsListadoUsuarios = mysql_num_rows($rsListadoUsuarios);

?>
<table class="flexme2">
        <tbody>
        <?php do { ?>
          <tr>
                <td><?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['RUT']; ?>-<?php echo $row_rsListadoUsuarios['DV']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['PROVEEDOR']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['NOMDIVISION']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['NOMBRE']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['APELLIDOPAT']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['APELLIDOMAT']; ?></td>
                <td>19-02-12</td>
                <td><?php echo $row_rsListadoUsuarios['PERFIL']; ?></td>
                <td>       	<?php 
				  	if($row_rsListadoUsuarios['ID_ESTUSU']=="1"){
					echo "<img src='img/accept_16.png' class='typeme' title='Usuario Activa' />";
					}else if($row_rsListadoUsuarios['ID_ESTUSU']=="2"){
					echo "<img src='img/dropbox_16.png' class='typeme' title='Usuario Inactivo' />";
					}
					?> 
                </td>
                <td><a href="#" id="<?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?>"><img src="img/edit.png"  class="editme" title="Editar Usuario" /></a></td>
                <td>&nbsp;</td>
              </tr>
              <?php } while ($row_rsListadoUsuarios = mysql_fetch_assoc($rsListadoUsuarios)); ?>
          </tbody>
        </table>