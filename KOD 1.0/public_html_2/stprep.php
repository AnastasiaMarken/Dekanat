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
$password=md5($_SERVER['PHP_AUTH_PW']);
$connection=mysql_connect($dbhost, $dbuser, $dbpasswd);
if(!$connection) {
 die("���������� ������������ � ���� ������: <br />".mysql_error());
}
$db_select=mysql_select_db($dbname);
if(!$db_select) {
 die("���������� ������� ���� ������: <br />".mysql_error());
}
$q="select id_prep from users where password='$password'";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$prepid=$row['id_prep'];

$q="select famprep, nameprep, otchprep from prep where id_prep=$prepid";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
$row=mysql_fetch_array($result);
$prepod=$row['famprep']." ".$row['nameprep']." ".$row['otchprep'];
$q="select id_kaf, namekaf from kaf where id_kaf In (select id_kaf from dgpk where id_prep=$prepid group by id_kaf)";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}


echo "<form action=\"new1.php\" method=\"post\"><strong>�������:&nbsp&nbsp</strong><select name=\"kafedra\" size=\"1\">";
while ($row=mysql_fetch_array($result)) {
echo "<option value="."$row[id_kaf]".">"."$row[namekaf]"."</option>";
}
echo "</select>";
echo "<br /><br />";
echo "<strong>�������������:</strong>$prepod<br /><br />";
$q="select id_disc, namedisc from disc where id_disc In (select id_disc from dgpk where id_prep=$prepid group by id_disc)";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
echo "<strong>������� ����������:&nbsp&nbsp</strong><select name=\"disciplina\" size=\"1\">";
while ($row=mysql_fetch_array($result)) {
echo "<option value="."$row[id_disc]".">"."$row[namedisc]"."</option>";
}
echo "</select>";
echo "<br /><br />";
$q="select id_grup, numgrup from grup where id_grup In (select id_grup from dgpk where id_prep=$prepid group by id_grup)";
$result=mysql_query($q);
if(!$result) {
 die("���������� ��������� ������: <br />".mysql_error()); 
}
echo "<strong>������:&nbsp&nbsp</strong><select name=\"gruppa\" size=\"1\">";
while ($row=mysql_fetch_array($result)) {
echo "<option value="."$row[id_grup]".">"."$row[numgrup]"."</option>";
}
echo "</select>";
echo "<br /><br />";
mysql_close($connection);
echo "<strong>�����:</strong><select name=\"mesec\" size\"1\">";
echo "<option value=\"02\">�������</option>";
echo "<option value=\"03\">����</option>";
echo "<option value=\"04\">������</option>";
echo "<option value=\"05\">���</option>";
echo "<option value=\"06\">����</option>";
echo "</select><br /><br />";
echo "<input type=\"hidden\" name=\"prepod\" value=\"$prepid\">";
echo "<input name=\"knop\" type=\"submit\" value=\"���� ������ � �������\">";
echo "<input name=\"knop\" type=\"submit\" value=\"������������� �������\" />";
echo "<input name=\"knop\" type=\"submit\" value=\"������� ������� �� ������\" />";
echo "</form>";
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