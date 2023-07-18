<?php
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
{
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate:Basic realm="BRS"');
exit('<h2>Балльно-рейтинговая система</h2>Извините, вы должны ввести имя и пароль, чтобы получить доступ к данной странице');
}

//Соединение с базой данных
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
if ($passw1 != $password or $user_name!='dekan04')
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
include('config.php');
$password=md5($_SERVER['PHP_AUTH_PW']);
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
$q="select id_prep from users where password='$password'";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$prepid=$row['id_prep'];
$q="select famprep, nameprep, otchprep from prep where id_prep=$prepid";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$prepod=$row['famprep']." ".$row['nameprep']." ".$row['otchprep'];
echo "<form action=\"reytp.php\" method=\"post\">";
echo "<strong>Пользователь:</strong>$prepod<br /><br />";
echo "<br /><br />";
//Вывод списка групп
//$q="select id_grup, numgrup from grup where id_grup In (select id_grup from dgpk where id_prep=$prepid group by id_grup)";
$q="select id_grup, numgrup from grup";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo "<strong>Выберите группу:&nbsp&nbsp</strong><select name=\"gruppa\" size=\"1\">";
while ($row=mysql_fetch_array($result)) {
echo "<option value="."$row[id_grup]".">"."$row[numgrup]"."</option>";
}
echo "</select>";
//Конец вывода списка групп
echo "<br /><br />";
echo "<input name=\"knop\" type=\"submit\" value=\"Вывести пропуски занятий\">";
echo "<input name=\"knop\" type=\"submit\" value=\"Вывести рейтинг по группе\" />";
echo "<input name=\"knop\" type=\"submit\" value=\"Просмотр таблиц\" />";
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