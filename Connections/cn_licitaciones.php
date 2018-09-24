<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cn_licitaciones = "localhost";
$database_cn_licitaciones = "oticlicitaciones";
$username_cn_licitaciones = "storelicita";
$password_cn_licitaciones = "33Tw^3tF";
$cn_licitaciones = mysql_pconnect($hostname_cn_licitaciones, $username_cn_licitaciones, $password_cn_licitaciones) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'utf8'");
?>