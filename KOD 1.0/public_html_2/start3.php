<?php
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
{
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate:Basic realm="BRS"');
exit('<h2>Балльно-рейтинговая система</h2>Извините, вы должны ввести имя и пароль, чтобы получить доступ к данной странице');
}
//СОединение с базой данных
$user_name=trim($_SERVER['PHP_AUTH_USER']);
$password=md5($_SERVER['PHP_AUTH_PW']);
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
$q="select password from users where name='$user_name'";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$passw1=$row['password'];
mysql_close($connection);
if ($passw1 != $password)
{
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate:Basic realm="BRS"');
exit('<h2>Балльно-рейтинговая система</h2>Извините, вы должны ввести правильные имя и пароль, чтобы получить доступ к данной странице');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru" >
<head>
<meta http-eguiv="Content-Type" content="text/html; charset=windows-1251" />
<title></title>
<link rel="stylesheet" type="text/css" href="itiy2.css" />
</head>
<body>
<div id="container">
<div id="header">
  <img src="images/ti.png" alt="Логотип" width="80"/>
<h1>Балльно-рейтинговая система</h1>
  <h3>Факультет информационных технологий и управления СПбГТИ(ТУ)</h3>
</div>
 <div id="topmenu">
<div class="clear">	</div> 
</div>
<?php
ini_set("max_input_vars",10000);
ini_set("max_execution_time",300);
$kafed=$_POST['kafed'];
$prep=$_POST['prep'];
$disc=$_POST['disc'];
$grup=$_POST['grup'];
$countstud=$_POST['countstud'];
$countdatkm=$_POST['countdatkm'];
$studball=$_POST['stball'];
$reytingid2=$_POST['idreyting2'];
$datkont=$_POST['datakont'];
$typmer=$_POST['typemer'];
$reytingid1=explode(";", $reytingid2);
$z=0;
for($i=1;$i<=$countstud;$i++){
	for($j=1;$j<=$countdatkm;$j++){
	$z=$z+1;
	$reytingid[$i][$j]=$reytingid1[$z-1];	
//	echo $reytingid[$i][$j]." ";
	}
//echo "<br />";
}
/*
for($i=1;$i<=$countstud;$i++){
	for($j=1;$j<=$countdatkm;$j++){
		echo $studball[$i][$j]." ";
	}
echo "<br />";
}
*/
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}

for($i=1;$i<=$countstud;$i++){
	for($j=1;$j<=$countdatkm;$j++){
	$q="update reyting set ball=".$studball[$i][$j]." where id_reyting=".$reytingid[$i][$j];
	$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
 $q="update reyting set id_typekm=".$typmer[$j]." where id_reyting=".$reytingid[$i][$j];
	$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос тип контрольного мероприятия:<br />".mysql_error());  
} 
  $q="update reyting set data='".$datkont[$j]."' where id_reyting=".$reytingid[$i][$j];
	$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос дата контрольного мероприятия:<br />".mysql_error());
}
}
}
mysql_close($connection);
echo "Данные успешно заменены!<br />";
echo "<a href=\"index.php\">Перейти на стартовую страницу</a><br /><br />";
echo "<form action=\"outtabp.php\" method=\"post\" />";
echo "<input type=\"hidden\" name=\"disc\" value=\"$disc\" />";
echo "<input type=\"hidden\" name=\"grup\" value=\"$grup\" />";
echo "<input name=\"knop\" type=\"submit\" value=\"Просмотреть всю таблицу\" />";
echo "<input name=\"knop\" type=\"submit\" value=\"Просмотреть итоговый рейтинг\" />";
echo "</form>";
?>
<br /><br />
</div>
<div id="clear"></div>
<div id="footer"> 
  &copy; 2020, Факультет информационных технологий и управления,<br />
	Санкт-Петербургский государственный технологический институт<br /> 
	(Технический университет)
</div>
</body>
</html>