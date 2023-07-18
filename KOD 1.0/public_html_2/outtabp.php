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
<link type="text/css" rel="stylesheet" href="css/table.css" />
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
$disc=$_POST['disc'];
$grup=$_POST['grup'];
$knopka=$_POST['knop'];
// echo "$disc";
// echo "$grup";
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
$q="select numgrup from grup where id_grup=$grup";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$numgrup=$row['numgrup'];
echo "<strong>Группа №:</strong> $numgrup";
$q="select namedisc from disc where id_disc=$disc";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$disciplina=$row['namedisc'];
echo "<br /><br /><strong>Дисциплина:</strong> $disciplina<br />";
$q="select famprep, nameprep, otchprep from prep where id_prep In (select id_prep from dgpk where id_grup=$grup and id_disc=$disc)";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$famprep=$row['famprep'];
$nameprep=$row['nameprep'];
$otchprep=$row['otchprep'];
echo "<br /><strong>Преподаватель:</strong> $famprep $nameprep $otchprep<br />";
// Количество студентов в группе
$q="select id_stud from reyting where id_grup=$grup and id_disc=$disc group by id_stud";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$studid[$i]=$row['id_stud'];
// echo "$studid[$i] <br />";
}
$countstud=$i;
// echo "Количество студентов: $countstud <br />";
// Количество мероприятий всего
$studid1=$studid[1];
// echo "Cтудент1 id: $studid1 <br />";
$q="select id_typekm, data from reyting where id_grup=$grup and id_disc=$disc and id_stud=$studid1";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$kontid[$i]=$row['id_typekm'];
$datakont[$i]=substr($row['data'],0,5);
// echo "$kontid[$i] <br />";
// echo "$datakont[$i] <br />";
}
$countdatkm=$i;
// echo "Количество дат и мероприятий: $countdatkm <br />";
// echo "Баллы студента: <br />";
$q="select id_reyting, ball from reyting where id_grup=$grup and id_disc=$disc and id_stud=$studid1";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$reytid[$i]=$row['id_reyting'];
$ball[$i]=$row['ball'];
// echo "$reytid[$i] <br />";
// echo "$ball[$i] <br />";
}
$kolball=$i;
// echo "Количество баллов: $kolball";
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$q="select famstud, namestud, otchstud  from stud where id_stud=$idstud";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$studfio[$i]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
// echo "$studfio[$i] <br />";
}
for ($i=1; $i<=$countdatkm; $i++) {
$q="select namekm from typekm where id_typekm=$kontid[$i]";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$namekm[$i]=$row['namekm'];
// echo "$namekm[$i] ";
}
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$summa[$i]=0;
$q="select id_reyting, ball from reyting where id_grup=$grup and id_disc=$disc and id_stud=$idstud";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$j=0;
while ($row=mysql_fetch_array($result)) {
$j=$j+1;
$idreyting[$i][$j]=$row['id_reyting'];
$stball[$i][$j]=$row['ball'];
$summa[$i]=$summa[$i]+(double)$stball[$i][$j];
// echo ($idreyting[$i][$j]." ");
}
// echo "<br />";
}
mysql_close($connection);
if ($knopka=="Просмотреть всю таблицу"){
// Начало кнопки 
// Просмотреть всю таблицу
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th></th>";
echo "<th></th>";
	for ($j=1; $j<=$countdatkm; $j++) {
	echo "<th>$namekm[$j]</th>";
	}
echo "</tr>";
echo "<tr>";
echo "<th>Ф.И.О.</th>";
echo "<th>ИТОГО</th>";
for ($j=1; $j<=$countdatkm; $j++) {
	echo "<th>$datakont[$j]</th>";
	}
echo "</tr>";
	for ($i=1; $i<=$countstud; $i++) {
	echo "<tr>";
	echo "<th>$studfio[$i]</th>";
	echo "<th>".round($summa[$i],1)."</th>";
		for ($j=1; $j<=$countdatkm; $j++) {
		echo "<td>".round($stball[$i][$j],1)."</td>";
		}
	echo "</tr>";
	}
echo "</table>";
// Конец кнопки 
// Просмотреть всю таблицу
}
else
{
// Начало Кнопки 
// Просмотреть итоговый рейтинг
for ($z1=1; $z1<=$countstud-1; $z1++){
$k1=$z1;
for ($i=$z1; $i<=$countstud; $i++){
if ($summa[$k1]<$summa[$i]){
$k1=$i;
}
}
$l1=$summa[$k1];
$l2=$studfio[$k1];
$summa[$k1]=$summa[$z1];
$studfio[$k1]=$studfio[$z1];
$summa[$z1]=$l1;
$studfio[$z1]=$l2;
}
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th>Ф.И.О.</th>";
echo "<th>ИТОГО</th>";
echo "</tr>";
	for ($i=1; $i<=$countstud; $i++) {
	echo "<tr>";
	echo "<th>$studfio[$i]</th>";
	echo "<th>".round($summa[$i],1)."</th>";
	echo "</tr>";
	}
echo "</table>";
//  Конец Кнопки 
// Просмотреть итоговый рейтинг
}
echo "<a href=\"index.php\">Перейти на стартовую страницу</a><br />";
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