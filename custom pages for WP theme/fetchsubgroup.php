<?php
include_once('mssconfig.php');
$maingroup = $_POST['maingroup'];
$stmt =  $mss_conn->prepare('SELECT * FROM IScat where ISmID =' . $maingroup);
$stmt->execute();
$brts = $stmt->fetchAll();
$options = '<option value ="0"></option>';
foreach ($brts as $b) {
    $options .= '<option value ="' . $b['ISID'] . '">' . $b['ISname'] . '</option>';
}
echo $options;
