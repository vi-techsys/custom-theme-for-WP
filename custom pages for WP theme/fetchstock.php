<?php
include_once('mssconfig.php');
$main_group = $_POST['main_group'];
$sub_group = $_POST['sub_group'];
$stock = $_POST['stock'];
$quan = $_POST['quan'];
$name = $_POST['name'];
$code = $_POST['code'];
$textbox1 = $_POST['textbox1'];
$branch = $_POST['branch'];
$sql = '';
$where = ' where Iblok = ' . 0;
if ($stock != 0) {
    $where  .= ' and stokstcode =' . $stock;
}
$where  .= ' and STKBRID =' . $branch;
switch ($quan) {
    case 0:

        break;
    case 1:
        $where .= ' and stokquan =' . $textbox1;
        break;
    case 2:
        $where .= ' and stokquan >=' . $textbox1;
        break;
    case 3:
        $where .= ' and stokquan <=' . $textbox1;
        break;
}
if (!empty($code)) {
    $where .= ' and Icode="' . trim($code) . '"';
}
if (!empty($name)) {
    $where .= ' and Iname like "%' . trim($name) . '%"';
}
if ($main_group > 0) {
    $where .= ' and Imacat=' . $main_group;
}
if ($sub_group > 0) {
    $where .= ' and Iscat=' . $sub_group;
}
$sql = 'SELECT Stock.stokquan, Stocks.Stockname, Ingr.IID,Ingr.Icode, Ingr.Ibarcode, Ingr.Iname, Ingr.Iutype, Ingr.Iblok, Ingr.Icost, Ingr.Iavgcost FROM Ingr LEFT JOIN (Stock LEFT JOIN Stocks ON Stock.stokstcode=Stocks.StockID) ON Ingr.IID=Stock.stokitcode ' . $where . ' and IID=IMainIID GROUP BY Stock.stokquan, Stocks.Stockname,Ingr.IID, Ingr.Icode, Ingr.Ibarcode, Ingr.Iname, Ingr.Iutype, Ingr.Iblok, Ingr.Icost, Ingr.Iavgcost order by IID';
$stmt =  $mss_conn->prepare($sql);
$stmt->execute();
$brts = $stmt->fetchAll();
$i = 1;
$rows = '';
$total_quan = 0;
$total_p = 0;
foreach ($brts as $b) {
    $total_quan += $b['stokquan'] != null ? $b['stokquan'] : 0;
    $rows .= '<tr><td>' . $i . '</td><td>' . $b['Icode'] . '</td>
    <td>' . ($b['Iname'] != null ? $b['Iname'] : 0) . '</td>
    <td>' . ($b['stokquan'] != null ? $b['stokquan'] : 0) . '</td>
    <td>' . ($b['Iavgcost'] != null ? $b['Iavgcost'] : 0) . '</td>
    <td>' . ($b['Iavgcost'] * $b['stokquan']) . '</td>
    <td>' . $b['Stockname'] . '</td>
    </tr>';
}
print_r(array('rows' => $rows, 'total_Q' => $total_quan, 'total_P' => $total_p));
