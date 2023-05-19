<?php
include_once('mssconfig.php');
$main_category = $_POST['main_category'];
$sub_category = $_POST['sub_category'];
$stock = $_POST['stock'];
$order = $_POST['order'];
$user = $_POST['user'];
$branch = $_POST['branch'];
$datefrom = $_POST['datefrom'];
$dateto = $_POST['dateto'];
$sql = '';
$xword = '';
switch ($order) {
    case 0;
        $xword = '';
        break;
    case 1:
        $xword = ' quan, ';
        break;
    case 2:
        $xword = ' quan DESC, ';
        break;
}
$where = '';
if ($stock == 0) {
    $where .= ' where salestcode >= ' . 0;
} else {
    $where .= ' where salestcode >= ' . $stock;
}
if ($main_category == 0) {
    $where .= ' and itmcat >=' . "0";
} else {
    $where .= ' and itmcat =' . $main_category;
}
if ($sub_category == 0) {
    $where .= ' and itscat >= ' . "0";
} else {
    $where .= ' and itscat = ' . $sub_category;
}
if ($user == 0) {
    $where .= ' and saleuser >= ' . "0";
} else {
    $where .= ' and saleuser = ' . $user;
}
$where .= $branch > 0 ? ' and SaldBRID = ' . $branch : '';
if (!empty($datefrom)) {
    $where .= ' and (saledate >="' . $datefrom . '" and saledate <= "' . $dateto . '") ';
}

//delete Temp1
$mss_conn->exec('delete from Temp1 where xucode = ' .  "789621");
//Temp1 recs
$sql = 'SELECT Users.Uname, Stocks.Stockname, Items.itname,Items.itID,Items.itcode, Items.itmcat, Items.itscat, SalesDe.saleRtype,SalesDe.saleprice, SalesDe.salequan, SalesDe.saledate, SalesDe.saleitcode, SalesDe.salestcode, SalesDe.saleuser FROM ((SalesDe LEFT JOIN Items ON SalesDe.saleitcode = Items.itID) LEFT JOIN Stocks ON SalesDe.salestcode = Stocks.StockID) LEFT JOIN Users ON SalesDe.saleuser = Users.UID ' . $where;
$stmt =  $mss_conn->prepare($sql);
$stmt->execute();
$brts = $stmt->fetchAll();
foreach ($brts as $b) {
    if ($b['saleRtype'] == 0 || $b['saleRtype'] == 4) {
        $mss_conn->exec('insert into Temp1 (txt4, txt1,nom2,nom3,txt2,xucode) values("' . (empty($b['itcode']) ? "" : $b['itcode']) . '","' . (empty($b['itname']) ? "" : $b['itname']) . '","' . (empty($b['salequan']) ? 0 : $b['salequan']) . '","' . (empty($b['saleprice']) ? 0 : $b['saleprice']) . '","' . (empty($b['Stockname']) ? "" : $b['Stockname']) . '", "789621")');
    } else {
        $mss_conn->exec('insert into Temp1 (txt4, txt1,nom4,nom3,txt2,xucode) values("' . (empty($b['itcode']) ? "" : $b['itcode']) . '","' . (empty($b['itname']) ? "" : $b['itname']) . '","' . (empty($b['salequan']) ? 0 : $b['salequan']) . '","' . (empty($b['saleprice']) ? 0 : $b['saleprice']) . '","' . (empty($b['Stockname']) ? "" : $b['Stockname']) . '", "789621")');
    }
}
$sql = '';
$stm =  $mss_conn->prepare($sql);
$stm->execute();
$brt = $stm->fetchAll();

$i = 1;
$rows = 'SELECT Temp1.xucode ,Temp1.txt1, Temp1.txt2,Temp1.nom5,  Temp1.txt4, Sum(Temp1.nom2) AS quan, Sum(Temp1.nom3) AS price,Sum(Temp1.nom4) AS quanr FROM Temp1 GROUP BY Temp1.txt1,Temp1.xucode, Temp1.txt2,Temp1.txt4, Temp1.nom1,Temp1.nom5 Having xucode =  789621 order by  ' . $xword . ' nom1,nom5';
$total_sales = 0;
$total_p = 0;
$total_salesR = 0;
foreach ($brt as $b) {
    $total_sales += $b['quan'] != null ? $b['quan'] : 0;
    $total_salesR += $b['quanr'] != null ? $b['quanr'] : 0;
    $total_p += $b['Price'] != null ? $b['Price'] : 0;
    $rows .= '<tr><td>' . $i . '</td><td>' . ($b['txt4'] != null ? $b['txt4'] : "") . '</td>
    <td>' . ($b['txt1'] != null ? $b['txt1'] : "") . '</td>
    <td>' . ($b['quan'] != null ? $b['quan'] : 0) . '</td>
    <td>' . ($b['quanr'] != null ? $b['quanr'] : 0) . '</td>
    <td>' . ($b['Price'] != null ? $b['Price'] : 0) . '</td>
    <td>' . $b['txt2'] != null ? $b['txt2'] : "" . '</td>
    </tr>';
}
print_r(array('rows' => $rows, 'total_S' => $total_sales, 'total_SR' => $total_salesR, 'total_P' => $total_p));
