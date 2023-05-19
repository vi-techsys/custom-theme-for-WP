<?php
include_once('mssconfig.php');
$date = $_POST['date'];
$sales_check = $_POST['sales_check'];
$dine_in_check = $_POST['dine_in_check'];
$branch = $_POST['branch'];
$XDET = '';
$XXDET = '';
$XDST = '';
$XXDST = '';
$sql = '';
//load basic data
$sql = 'select * from basicdata';
$stm =  $mss_conn->prepare($sql);
$stm->execute();
$bsc = $stm->fetchAll();
$XDET = empty($bsc[0]['Det']) ? '' : trim($bsc[0]['Det']);
$XXDET .= ' ' . $XDET;
$XDST = empty($bsc[0]['DSt']) ? '' : trim($bsc[0]['DSt']);
$XXDST .= ' ' . $XDST;
$dt1 = new DateTime($date);
$dt2 = new DateTime($dt1->format('Y-m-d') . $XXDST);
//add one hour
$dtinterval = new DateInterval("P1h");
$dt3 = date_add($dt2, $dtinterval);
//delete Temp1
$mss_conn->exec('delete from Temp1 where xucode = ' .  "789621");
$rows = '';
if (!empty($sales_check)) {
    for ($i = 0; $i <= 24; $i++) {
        $xtot = 0;
        $xw = '';
        $xw2 = '';
        $xw3 = '';
        $xw = " where (saledate >= '" . $dt2->format('Y-m-d H:i:s') . "'  and saledate <= '" . $dt3->format('Y-m-d') . "') ";
        $dt2 = $dt3;
        $dt3 = date_add($dt2, $dtinterval);
        if (!empty($dine_in_check)) {
            $xw .= (!empty($branch) ? ' and SaldBRID = ' . $branch : '');
            $xw3  = $xw . ' and (saletype = 1 and saleRtype = 0 )';
            $xw = $xw . ' and saletype = 0';
            $xw = $xw . ' and saleRtype = 0';
            $sql = 'SELECT Sum(SalesDe.saleprice) AS Saltot FROM SalesDe ' . $xw;
            $stm =  $mss_conn->prepare($sql);
            $stm->execute();
            $r2 = $stm->fetchAll();
            $xtot = !empty($r2[0]['Saltot']) ? $r[0]['Saltot'] : 0;
            $sql = 'SELECT Sum(SalesDe.saleprice) AS Saltot FROM SalesDe ' . $xw3;
            $stm =  $mss_conn->prepare($sql);
            $stm->execute();
            $r2 = $stm->fetchAll();
            $xtot += !empty($r2[0]['Saltot']) ? $r[0]['Saltot'] : 0;
        } else {
            $xw2 = (!empty($branch) ? ' and SaldBRID = ' . $branch : '');
            $xw2 = $xw . ' and (saletype = 0 and saleRtype =4)';
            $xw3 = $xw . ' and (saletype = 1 and saleRtype = 0)';
            $xw = $xw . ' and (saletype = 0 and saleRtype = 0)';
            $sql = 'SELECT Sum(SalesDe.saleprice) AS Saltot FROM SalesDe ' . $xw;
            $stm =  $mss_conn->prepare($sql);
            $stm->execute();
            $r2 = $stm->fetchAll();
            $xtot = !empty($r2[0]['Saltot']) ? $r[0]['Saltot'] : 0;
            $sql = 'SELECT Sum(SalesDe.saleprice) AS Saltot FROM SalesDe ' . $xw3;
            $stm =  $mss_conn->prepare($sql);
            $stm->execute();
            $r2 = $stm->fetchAll();
            $xtot += !empty($r2[0]['Saltot']) ? $r[0]['Saltot'] : 0;
            $sql = 'SELECT Sum(SalesDe.saleprice) AS Saltot FROM SalesDe ' . $xw2;
            $stm =  $mss_conn->prepare($sql);
            $stm->execute();
            $r2 = $stm->fetchAll();
            $xtot += !empty($r2[0]['Saltot']) ? $r[0]['Saltot'] : 0;
        }
        $rows .= "<tr><td>${$i}</td><td>{$xtot}</td></tr>";
    }
}
echo json_encode(array('rows' => $rows));
