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
$q="drop table if exists prep";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$q="create table prep (id_prep INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_kaf INT NOT NULL, famprep char(30), nameprep char(30), otchprep char(30))";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo ("OK-");
$f=fopen('prep.csv','r');
while ($array=fgetcsv($f,1024)) {
$idprep=$array[0];
$idkaf=$array[1];
$famprep=$array[2];
$nameprep=$array[3];
$otchprep=$array[4];
echo ("$idprep");
echo ("$idkaf");
echo ("$famprep");
echo ("$nameprep");
echo ("$otchprep");
$idprep=mysql_real_escape_string($idprep);
$idkaf=mysql_real_escape_string($idkaf);
$famprep=mysql_real_escape_string($famprep);
$nameprep=mysql_real_escape_string($nameprep);
$otchprep=mysql_real_escape_string($otchprep);
$q="insert into prep values($idprep,$idkaf,'$famprep','$nameprep','$otchprep')";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
}
fclose($f);
mysql_close($connection);
echo "Таблица была успешно залита"
?>
