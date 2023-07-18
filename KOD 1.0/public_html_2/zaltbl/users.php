<?php
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("01 Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("02 невозможно выбрать базу данных: <br />".mysql_error());
}
$q="drop table if exists users";
$result=mysql_query($q);
if(!$result) {
 die("1 Невозможно выполнить запрос: <br />".mysql_error()); 
}
$q="create table users (id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,name char(30) NOT NULL,password char(250) NOT NULL,fam char(30) NOT NULL,status char(15)NOT NULL,id_prep INT NOT NULL)";
$result=mysql_query($q);
if(!$result) {
 die("2 Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo ("OK-");
$f=fopen('users.csv','r');
while ($array=fgetcsv($f,1024)) {
$iduser=$array[0];
$name=$array[1];
$password=$array[2];
$fam=$array[3];
$status=$array[4];
$idprep=$array[5];
$password=md5($password);
echo ("$iduser");
echo ("$name");
echo ("$password");
echo ("$fam");
echo ("$status");
echo ("$idprep");
$iduser=mysql_real_escape_string($iduser);
$idkaf=mysql_real_escape_string($name);
$fam=mysql_real_escape_string($fam);
$status=mysql_real_escape_string($status);
$idprep=mysql_real_escape_string($idprep);
$q="insert into users values($iduser, '$name', '$password','$fam','$status',$idprep)";
$result=mysql_query($q);
if(!$result) {
 die("3 Невозможно выполнить запрос: <br />".mysql_error()); 
}
}
fclose($f);
mysql_close($connection);
echo "Таблица была успешно залита"
?>
