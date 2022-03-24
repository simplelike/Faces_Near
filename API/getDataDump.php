<?
$p = $_POST['url'];
$return = file_get_contents($p);
echo $return;
?>