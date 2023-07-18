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
$q="drop table if exists stud";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$q="create table stud (id_stud INT NOT NULL AUTO_INCREMENT PRIMARY KEY, id_grup INT NOT NULL, famstud char(30), namestud char(30), otchstud char(30))";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo ("OK-");
$f=fopen('stud.csv','r');
while ($array=fgetcsv($f,1024)) {
$idstud=$array[0];
$idgroup=$array[1];
$famstud=$array[2];
$namestud=$array[3];
$otchstud=$array[4];
echo ("$idstud");
echo ("$idgroup");
echo ("$famstud");
echo ("$namestud");
echo ("$otchstud");
$idstud=mysql_real_escape_string($idstud);
$idgroup=mysql_real_escape_string($idgroup);
$famstud=mysql_real_escape_string($famstud);
$namestud=mysql_real_escape_string($namestud);
$otchstud=mysql_real_escape_string($otchstud);
$q="insert into stud values($idstud,$idgroup,'$famstud','$namestud','$otchstud')";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
}
fclose($f);
mysql_close($connection);
echo "Таблица была успешно залита"
?>
