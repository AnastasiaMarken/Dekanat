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
if ($passw1 != $password or $user_name!="dekan04")
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
<link type="text/css" rel="stylesheet" href="css/table.css" />
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
$grup=$_POST['gruppa'];
$knopka=$_POST['knop'];
include('config.php');
//$password=md5($_SERVER['PHP_AUTH_PW']);
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("Невозможно подключиться к базе данных: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("невозможно выбрать базу данных: <br />".mysql_error());
}
// Количество студентов в группе
//echo "Группа $grup";
$q="select id_stud from reyting where id_grup=$grup group by id_stud";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$studid[$i]=$row['id_stud'];
//echo "<br />$studid[$i] <br />";
}
$countstud=$i;
//echo "<br /><br />Количество студентов в группе $countstud";
// Номер группы
$q="select numgrup from grup where id_grup=$grup";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$numgrup=$row['numgrup'];
// Количество дисциплин у группы
$q="select id_disc from reyting where id_grup=$grup group by id_disc";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$discid[$i]=$row['id_disc'];
}
$countdisc=$i;
//echo "<br /><br />Количество дисциплин для группы $countdisc";
if ($knopka=="Вывести рейтинг по группе") 
{
// Старт кнопки 	Вывести рейтинг по группе
// Получаем наименования дисциплин 
for($j=1; $j<=$countdisc; $j++)
{
	$iddisc=$discid[$j];
$q="select namedisc from disc where id_disc=$iddisc";	
$result=mysql_query($q);
if(!$result){
	die("Невозможно выпонить запрос:".mysql_error());
}
$row=mysql_fetch_array($result);
$namedisc[$j]=$row['namedisc'];
//echo "<br />$namedisc[$j]";
}

// Проходим студентов
for($i=1; $i<=$countstud; $i++) 
{
//Получаем ФИО студента
$idstud=$studid[$i];
$q="select famstud, namestud, otchstud from stud where id_stud=$idstud";
$result=mysql_query($q);
if(!$result){
	die("Невозможно выпонить запрос:".mysql_error());
}
$row=mysql_fetch_array($result);
$studfio[$i]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
//echo "$studfio[$i]";

	for($j=1; $j<=$countdisc; $j++)
	{
		$sumbal[$i][$j]=0;
		$disciplina=$discid[$j];
		$q="select ball from reyting where id_disc=$disciplina and id_stud=$idstud";
		$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$k=0;
while ($row=mysql_fetch_array($result)) {
$k=$k+1;
$sumbal[$i][$j]=$sumbal[$i][$j]+$row['ball'];
}
	}
}
//Получены баллы студентов Выводим таблицу
echo "<h2>Итоговые рейтинги по дисциплинам</h2>";
echo "<h2>Группа №: $numgrup</h2>";
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th>N</th>";
echo "<th>Ф.И.О.</th>";
for($j=1; $j<=$countdisc; $j++){
	echo "<th>$namedisc[$j]</th>";	
}
echo "<th>ИТОГО</th>";
echo "</tr>";
	for ($i=1; $i<=$countstud; $i++) {
	echo "<tr>";
	echo "<th>$i</th>";
	echo "<th>$studfio[$i]</th>";
	$itogreyt=0;
for($j=1; $j<=$countdisc; $j++){
	echo "<td>".round($sumbal[$i][$j],1)."</td>";
	$itogreyt=$itogreyt+round($sumbal[$i][$j],1);
}
	echo "<td>$itogreyt</td>";
	echo "</tr>";
	}
echo "</table>";
echo "<a href=\"index.php\">Перейти на стартовую страницу</a><br />";
}
// Конец кнопки 	Вывести рейтинг по группе
if ($knopka=="Вывести пропуски занятий"){
// Старт кнопки 	Вывести пропуски занятий
// Получаем наименования дисциплин 
for($j=1; $j<=$countdisc; $j++)
{
	$iddisc=$discid[$j];
$q="select namedisc from disc where id_disc=$iddisc";	
$result=mysql_query($q);
if(!$result){
	die("Невозможно выпонить запрос:".mysql_error());
}
$row=mysql_fetch_array($result);
$namedisc[$j]=$row['namedisc'];
//echo "<br />$namedisc[$j]";
}
// Проходим студентов
for($i=1; $i<=$countstud; $i++) 
{
//Получаем ФИО студента
$idstud=$studid[$i];
$q="select famstud, namestud, otchstud from stud where id_stud=$idstud";
$result=mysql_query($q);
if(!$result){
	die("Невозможно выпонить запрос:".mysql_error());
}
$row=mysql_fetch_array($result);
$studfio[$i]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
//echo "$studfio[$i]";

	for($j=1; $j<=$countdisc; $j++)
	{
		$sumbal[$i][$j]=0;
		$disciplina=$discid[$j];
		$q="select ball from reyting where id_disc=$disciplina and id_stud=$idstud";
		$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$k=0;
while ($row=mysql_fetch_array($result)) {
$k=$k+1;
$sumprop=$row['ball'];
if($sumprop<0)
$sumbal[$i][$j]=$sumbal[$i][$j]+1;
}
	}
}
//Получены баллы студентов Выводим таблицу
echo "<h2>Итоговые пропуски занятий по дисциплинам</h2>";
echo "<h2>Группа №: $numgrup</h2>";
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th>N</th>";
echo "<th>Ф.И.О.</th>";
for($j=1; $j<=$countdisc; $j++){
	echo "<th>$namedisc[$j]</th>";	
}
echo "<th>ИТОГО</th>";
echo "</tr>";
	for ($i=1; $i<=$countstud; $i++) {
	echo "<tr>";
	echo "<th>$i</th>";
	echo "<th>$studfio[$i]</th>";
$itogprop=0;
	for($j=1; $j<=$countdisc; $j++){
	echo "<td>".round($sumbal[$i][$j],1)."</td>";
	$itogprop=$itogprop+round($sumbal[$i][$j],1);
}
echo "<td>$itogprop</td>";
	echo "</tr>";
	}
echo "</table>";
echo "<a href=\"index.php\">Перейти на стартовую страницу</a><br />";
}	
// Конец кнопки 	Вывести пропуски занятий	
if ($knopka=="Просмотр таблиц"){
// Старт кнопки 	Просмотр таблиц
// Номер группы
$q="select numgrup from grup where id_grup=$grup";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$numgrup=$row['numgrup'];
echo "<strong>Группа №: $numgrup</strong>";
echo "<form action=\"outtabp.php\" method=\"post\" />";
//Вывод списка дисциплин
$q="select id_disc, namedisc from disc where id_disc In (select id_disc from dgpk where id_grup=$grup group by id_disc)";
$result=mysql_query($q);
if(!$result) {
 die("Невозможно выполнить запрос: <br />".mysql_error()); 
}
echo "<strong>Выберите дисциплину:&nbsp&nbsp</strong><select name=\"disc\" size=\"1\">";
while ($row=mysql_fetch_array($result)) {
echo "<option value="."$row[id_disc]".">"."$row[namedisc]"."</option>";
}
echo "</select>";
//Конец вывода списка групп
echo "<input type=\"hidden\" name=\"grup\" value=\"$grup\" /><br /><br />";
echo "<input name=\"knop\" type=\"submit\" value=\"Просмотреть всю таблицу\" />";
echo "</form>";
}
// Конец кнопки 	Просмотр таблиц	
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