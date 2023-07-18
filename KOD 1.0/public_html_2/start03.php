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
$kolstud=$_POST['kolstud'];
$idstud2=$_POST['idstud2'];
$idtypemer=$_POST['typmer'];
$datamer=$_POST['datamer'];
$studball=$_POST['stball'];
$idstud=explode(";", $idstud2);
//echo "Кафедра $kafed";
//echo "Преп $prep";
//echo "Дисц $disc";
//echo "Группа $grup";
//echo "id мер $idtypemer";
//echo "Дат мет $datamer";
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
for($i=1;$i<=$kolstud;$i++){
$z=$i-1;
$q="insert into reyting (id_grup, id_kaf, id_disc, id_prep, id_stud, id_typekm, data, ball) values ($grup, $kafed, $disc, $prep, $idstud[$z], $idtypemer,'$datamer',$studball[$i])";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
//echo ("OK-");
//echo "Данные успешно сохранены";
//echo "Балл $studball[$i]";
//echo "id студ $idstud[$z]";
}
mysql_close($connection);



echo "<h2>Данные успешно заменены!</h2>";
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