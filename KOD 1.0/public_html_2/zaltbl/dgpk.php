<?php
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
$q="drop table if exists dgpk";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$q="create table dgpk (id_dgpk INT NOT NULL AUTO_INCREMENT PRIMARY KEY,id_disc INT,id_grup INT,id_prep INT,id_kaf INT)";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo ("OK-");
$f=fopen('dgpk.csv','r');
while ($array=fgetcsv($f,1024)) {
$iddgpk=$array[0];
$iddisc=$array[1];
$idgrup=$array[2];
$idprep=$array[3];
$idkaf=$array[4];
echo ("$iddgpk");
echo ("$iddisc");
echo ("$idgrup");
echo ("$idprep");
echo ("$idkaf");
$iddgpk=mysql_real_escape_string($iddgpk);
$iddisc=mysql_real_escape_string($iddisc);
$idgrup=mysql_real_escape_string($idgrup);
$idprep=mysql_real_escape_string($idprep);
$idkaf=mysql_real_escape_string($idkaf);
$q="insert into dgpk values($iddgpk,$iddisc,$idgrup,$idprep,$idkaf)";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
}
fclose($f);
mysql_close($connection);
echo "Таблица была успешно залита"
?>
