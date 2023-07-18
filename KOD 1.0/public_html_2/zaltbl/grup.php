<?php
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("1. невозможно выбрать базу данных: <br />".mysql_error());
}
$q="drop table if exists grup";
$result=mysql_query($q);
if(!$result) {
 die("2. Невозможно выполнить запрос: <br />".mysql_error()); 
}
$q="create table grup (id_grup INT NOT NULL AUTO_INCREMENT PRIMARY KEY, numgrup char(5), kurs INT, napr char(10))";
$result=mysql_query($q);
if(!$result) {
 die("3. Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo ("OK-");
$f=fopen('grup.csv','r');
while ($array=fgetcsv($f,1024)) {
$idgrup=$array[0];
$numgrup=$array[1];
$kurs=$array[2];
$napr=$array[3];
echo ("$idgrup");
echo ("$numgrup");
echo ("$kurs");
echo ("$napr");
$idgrup=mysql_real_escape_string($idgrup);
$numgrup=mysql_real_escape_string($numgrup);
$kurs=mysql_real_escape_string($kurs);
$napr=mysql_real_escape_string($napr);
$q="insert into grup values($idgrup,'$numgrup',$kurs,'$napr')";
$result=mysql_query($q);
if(!$result) {
 die("4. Невозможно выполнить запрос: <br />".mysql_error()); 
}
}
fclose($f);
mysql_close($connection);
echo "Таблица была успешно залита"
?>
