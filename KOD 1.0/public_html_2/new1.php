<?php
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
{
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate:Basic realm="BRS"');
exit('<h2>�������-����������� �������</h2>��������, �� ������ ������ ��� � ������, ����� �������� ������ � ������ ��������');
}
//���������� � ����� ������
$user_name=trim($_SERVER['PHP_AUTH_USER']);
$password=md5($_SERVER['PHP_AUTH_PW']);
include('config.php');
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("���������� ������������ � ���� ������: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("���������� ������� ���� ������: <br />".mysql_error());
}
$q="select password from users where name='$user_name'";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$passw1=$row['password'];
mysql_close($connection);
if ($passw1 != $password)
{
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate:Basic realm="BRS"');
exit('<h2>�������-����������� �������</h2>��������, �� ������ ������ ���������� ��� � ������, ����� �������� ������ � ������ ��������');
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
  <img src="images/ti.png" alt="�������" width="80"/>
<h1>�������-����������� �������</h1>
  <h3>��������� �������������� ���������� � ���������� ������(��)</h3>
</div>
 <div id="topmenu">
<div class="clear">	</div> 
</div>
<?php
include('config.php');
$kafedra=$_POST['kafedra'];
$disciplina=$_POST['disciplina'];
$gruppa=$_POST['gruppa'];
$mesec=$_POST['mesec'];
$prepod=$_POST['prepod'];
$knopka=$_POST['knop'];
//echo "�������: $kafedra";
//echo "����������: $disciplina";
//echo "������: $gruppa";
//echo "�����: $mesec";
//echo "������������� $prepod<br />";
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("���������� ������������ � ���� ������: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("���������� ������� ���� ������: <br />".mysql_error());
}
//knopka input
if ($knopka=="���� ������ � �������") {
$q="select namekaf from kaf where id_kaf=$kafedra";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������:<br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$namekaf=$row['namekaf'];
echo "<strong>�������:</strong> $namekaf";
$q="select namedisc from disc where id_disc=$disciplina";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������:<br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$namedisc=$row['namedisc'];
echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<strong>������� ����������:</strong> $namedisc";
$q="select famprep, nameprep, otchprep from prep where id_prep=$prepod";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$fioprep=$row['famprep']." ".$row['nameprep']." ".$row['otchprep'];
echo "<br /><strong>�������������:&nbsp</strong> $fioprep";
$q="select numgrup from grup where id_grup=$gruppa";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$grup=$row['numgrup'];
echo "&nbsp&nbsp<strong>������ �:&nbsp</strong> $grup";

$q="select id_stud, famstud, namestud, otchstud from stud where id_grup=$gruppa";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$studid[$i]=$row['id_stud'];
$studfio[$i]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
$stball[$i]=0;
}
$kolstud=$i;
$q="select id_typekm, namekm from typekm";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$kmtypeid[$i]=$row['id_typekm'];
$kmtype[$i]=$row['namekm'];
}
$koltypekm=$i;
mysql_close($connection);
echo "<form method=\"post\" action=\"start03.php\" >";
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th>���� �������</th>";
$datzan="00-".$mesec."-2021";
echo "<td><input type='text' name='datamer' value=$datzan><td>"; 
echo "</tr>";
echo "<tr>";
echo "<th>��� �������</th>";
echo "<td><select name=\"typmer\" size=\"1\">";
for ($i=1;$i<=$koltypekm;$i++) {
echo "<option value=$kmtypeid[$i]>$kmtype[$i]</option>";
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr><th>�.�.�.</th><th>����</th></tr>";
for ($i=1;$i<=$kolstud;$i++) {
echo "<tr><th>$studfio[$i]</th>";
echo "<td><input type=\"text\" name=\"stball[$i]\" value=\"$stball[$i]\" /></td>";
echo "</tr>";
}
echo "</table>";
$idstud2=implode(";",$studid);
echo "<input type=\"hidden\" name=\"kafed\" value=\"$kafedra\" />";
echo "<input type=\"hidden\" name=\"prep\" value=\"$prepod\" />";
echo "<input type=\"hidden\" name=\"disc\" value=\"$disciplina\" />";
echo "<input type=\"hidden\" name=\"grup\" value=\"$gruppa\" />";
echo "<input type=\"hidden\" name=\"kolstud\" value=\"$kolstud\" />";
echo "<input type=\"hidden\" name=\"idstud2\" value=\"$idstud2\" />";
echo "<br />";
echo "<input type=\"submit\" value=\"��������� ��������� ������\" name=\"submit\" />";
echo "</form>";
}
//knopka edit table
if($knopka=="������������� �������"){
// ���������� ��������� � ������
$q="select id_stud from reyting where id_grup=$gruppa and id_disc=$disciplina group by id_stud";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������:<br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$studid[$i]=$row['id_stud'];
// echo "$studid[$i] <br />";
}
$countstud=$i;
if ($countstud==0){
echo "<h2>������� �� ���������.</h2>";
exit;
}
// echo "���������� ���������: $countstud <br />";
// ���������� ����������� � ��������� ������
$studid1=$studid[1];
// echo "C������1 id: $studid1 <br />";
$q="select id_typekm, data from reyting where id_grup=$gruppa and id_disc=$disciplina and id_stud=$studid1 and data LIKE '%-$mesec-%'";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
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
// echo "���������� ��� � �����������: $countdatkm <br />";
// echo "����� ��������: <br />";
$q="select id_reyting, ball from reyting where id_grup=$gruppa and id_disc=$disciplina and id_stud=$studid1 and data LIKE '%-$mesec-%'";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
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
// echo "���������� ������: $kolball";
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$q="select famstud, namestud, otchstud  from stud where id_stud=$idstud";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$studfio[$i]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
// echo "$studfio[$i] <br />";
}
for ($i=1; $i<=$countdatkm; $i++) {
$q="select namekm from typekm where id_typekm=$kontid[$i]";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$namekm[$i]=$row['namekm'];
// echo "$namekm[$i] ";
}
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$summa[$i]=0;
$q="select id_reyting, ball from reyting where id_grup=$gruppa and id_disc=$disciplina and id_stud=$idstud and data LIKE '%-$mesec-%'";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$j=0;
while ($row=mysql_fetch_array($result)) {
$j=$j+1;
$idreyting[$i][$j]=$row['id_reyting'];

$stball[$i][$j]=$row['ball'];
$summa[$i]=$summa[$i]+(double)$stball[$i][$j];
}
// echo "<br />";
}
$testrt=$j;
$q="select id_typekm, namekm from typekm";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$kmtypeid[$i]=$row['id_typekm'];
$kmtype[$i]=$row['namekm'];
}
$koltypekm=$i;
mysql_close($connection);
if ($testrt==0) {
echo "<h2>� ������� �� ��������� ����� ��� ������</h2>";
 exit;
}
echo "<form method=\"post\" action=\"start3.php\" >";
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th></th>";
echo "<th></th>";
	for ($j=1; $j<=$countdatkm; $j++) {
	echo "<td><select name=\"typemer[$j]\" size=\"1\">";
	echo "<option value=$kontid[$j]>$namekm[$j]</option>";
	for ($i=1;$i<=$koltypekm;$i++) {
	echo "<option value=$kmtypeid[$i]>$kmtype[$i]</option>";
	}
	echo "</select></td>";
	//$namekm[$j]</th>";
	}
echo "</tr>";
echo "<th>�.�.�.</th>";
echo "<th>�����</th>";
for ($j=1; $j<=$countdatkm; $j++) {
	echo "<td><input type=\"text\" name=\"datakont[$j]\" value=\"".$datakont[$j]."-2020\" /></td>";
	}
echo "</tr>";
	for ($i=1; $i<=$countstud; $i++) {
	echo "<tr>";
	echo "<th>$studfio[$i]</th>";
	echo "<th>".round($summa[$i],1)."</th>";
		for ($j=1; $j<=$countdatkm; $j++) {
		echo "<td><input type=\"text\" name=\"stball[$i][$j]\" value=\"".round($stball[$i][$j],1)."\" /></td>";
		}
	echo "</tr>";
	}
echo "</table>";
$z=0;
for ($i=1;$i<=$countstud;$i++)
{
	for($j=1;$j<=$countdatkm;$j++){
	$z=$z+1;
	$idreyting1[$z]=$idreyting[$i][$j];
}
}
$idreyting2=implode(";", $idreyting1);
echo "<input type=\"hidden\" name=\"kafed\" value=\"$kafedra\" />";
echo "<input type=\"hidden\" name=\"prep\" value=\"$prepod\" />";
echo "<input type=\"hidden\" name=\"disc\" value=\"$disciplina\" />";
echo "<input type=\"hidden\" name=\"grup\" value=\"$gruppa\" />";
echo "<input type=\"hidden\" name=\"countstud\" value=\"$countstud\" />";
echo "<input type=\"hidden\" name=\"countdatkm\" value=\"$countdatkm\" />";
echo "<input type=\"hidden\" name=\"idreyting2\" value=\"$idreyting2\" />";
echo "<br />";
echo "<input type=\"submit\" value=\"�����\" name=\"submit\" />";
echo "</form>";
}
// knopka output potok
if($knopka=="������� ������� �� ������"){
//����������� ID �����
$q="select id_grup from reyting where id_prep=$prepod and id_disc=$disciplina group by id_grup";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$groupid[$i]=$row['id_grup'];
}
$countgroup=$i;
//������ ����� �� �������
$kolst=0;$ksum=0;
for ($gr=1; $gr<=$countgroup; $gr++){
$grup=$groupid[$gr];
// ���������� ��������� � ������
$q="select id_stud from reyting where id_grup=$grup and id_disc=$disciplina group by id_stud";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$i=0;
while ($row=mysql_fetch_array($result)) {
$i=$i+1;
$studid[$i]=$row['id_stud'];
// echo "$studid[$i] <br />";
}
$countstud=$i;
// echo "���������� ���������: $countstud <br />";
// ���������� ����������� �����
$studid1=$studid[1];
// echo "C������1 id: $studid1 <br />";
$q="select id_typekm, data from reyting where id_grup=$grup and id_disc=$disciplina and id_stud=$studid1";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
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
// echo "���������� ��� � �����������: $countdatkm <br />";
// echo "����� ��������: <br />";
$q="select id_reyting, ball from reyting where id_grup=$grup and id_disc=$disciplina and id_stud=$studid1";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
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
// echo "���������� ������: $kolball";
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$q="select famstud, namestud, otchstud from stud where id_stud=$idstud";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$kolst=$kolst+1;
$studfio[$kolst]=$row['famstud']."&nbsp".substr($row['namestud'],0,1)."&nbsp".substr($row['otchstud'],0,1);
// echo "$studfio[$i] <br />";
}
for ($i=1; $i<=$countdatkm; $i++) {
$q="select namekm from typekm where id_typekm=$kontid[$i]";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$namekm[$i]=$row['namekm'];
// echo "$namekm[$i] ";
}
for ($i=1; $i<=$countstud; $i++) {
$idstud=$studid[$i];
$ksum=$ksum+1;
$summa[$ksum]=0;
$q="select id_reyting, ball from reyting where id_grup=$grup and id_disc=$disciplina and id_stud=$idstud";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$j=0;
while ($row=mysql_fetch_array($result)) {
$j=$j+1;
$idreyting[$i][$j]=$row['id_reyting'];
$stball[$i][$j]=$row['ball'];
$summa[$ksum]=$summa[$ksum]+(double)$stball[$i][$j];
// echo ($idreyting[$i][$j]." ");
}
// echo "<br />";
}
}
//����� ����� �� �������
mysql_close($connection);
//����� ���� ��������� ������
for ($z1=1; $z1<=$ksum-1; $z1++){
$k1=$z1;
for ($i=$z1; $i<=$ksum; $i++){
if ($summa[$k1]<$summa[$i]){
$k1=$i;
}
}
$l1=$summa[$k1];
$l2=$studfio[$k1];
//$l3=$grtmp[$k1];
$summa[$k1]=$summa[$z1];
$studfio[$k1]=$studfio[$z1];
//$grtmp[$k1]=$grtmp[$z1];
$summa[$z1]=$l1;
$studfio[$z1]=$l2;
//$grtmp[$z1]=$l3;
}
echo "<table class=\"formdata\">";
echo "<tr>";
echo "<th>N</th>";
echo "<th>�.�.�.</th>";
//echo "<th>������</th>";
echo "<th>�����</th>";
echo "</tr>";
	for ($i=1; $i<=$ksum; $i++) {
	echo "<tr>";
	echo "<th>$i</th>";
	echo "<th>$studfio[$i]</th>";
	//echo "<th>$grtmp[$i]</th>";
	echo "<th>".round($summa[$i],1)."</th>";
	echo "</tr>";
	}
echo "</table>";
echo "<a href=\"index.php\">������� �� ��������� ��������</a><br />";
}
?>
<br /><br />
</div>
<div id="clear"></div>
<div id="footer"> 
  &copy; 2020, ��������� �������������� ���������� � ����������,<br />
	�����-������������� ��������������� ��������������� ��������<br /> 
	(����������� �����������)
</div>
</body>
</html>