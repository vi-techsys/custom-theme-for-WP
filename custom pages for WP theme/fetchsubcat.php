<?php
include_once('mssconfig.php');
$maincat = $_POST['maincat'];
$stmt =  $mss_conn->prepare('SELECT * FROM subcat where  scmccode = ' . $maincat);
$stmt->execute();
$brts = $stmt->fetchAll();
$options = '<option value ="0"></option>';
foreach ($brts as $b) {
    $options .= '<option value ="' . $b['Scid'] . '">' . $b['scname'] . '</option>';
}
echo $options;
