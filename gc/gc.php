<?php
/*
  Simple IPN processing script
  based on code from the "PHP Toolkit" provided by PayPal
 * Author  : Contussupport
 * Modified Date: 14/02/2013
 * Modified By: Selvakumar
 */

$basePath = str_replace('gc', '', dirname(__FILE__));
require_once $basePath . 'app/Mage.php';
Mage::app('default');

$url        = 'https://www.paypal.com/cgi-bin/webscr';
$postdata   = '';
foreach ($_POST as $i => $v) {
    $postdata .= $i . '=' . urlencode($v) . '&';
}
$postdata  .= 'cmd=_notify-validate';

$web        = parse_url($url);
if ($web['scheme'] == 'https') {
    $web['port']  = 443;
    $ssl          = 'ssl://';
}
else {
    $web['port']  = 80;
    $ssl          = '';
}
$fp = @fsockopen($ssl . $web['host'], $web['port'], $errnum, $errstr, 30);
if (!$fp) {
    echo $errnum . ': ' . $errstr;
} else {
    fputs($fp, "POST " . $web['path'] . " HTTP/1.1\r\n");
    fputs($fp, "Host: " . $web['host'] . "\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($postdata) . "\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    fputs($fp, $postdata . "\r\n\r\n");

    while (!feof($fp)) {
        $info[] = @fgets($fp, 1024);
    }
    fclose($fp);

    $info = implode(',', $info);    
    if (eregi('VERIFIED', $info)) {

        //Collecting data from Paypal
        $date           = date("Y-m-d", strtotime(urldecode($_POST["payment_date"])));
        $payerId        = $_POST["option_name1"];
        $payerEmail     = $_POST["option_name2"];
        $transactionId  = $_POST["txn_id"];
        $itemName       = $_POST['item_name'];
        $itemId         = $_POST['item_number'];
        $amount         = $_POST["payment_gross"];
        $paymentStatus  = $_POST["payment_status"];
        $payerStatus    = $_POST["payer_status"];
        $currency       = $_POST["mc_currency"];

        if ($paymentStatus == 'Completed' || $paymentStatus == 'Processing') {
            $connection     = Mage::getSingleton('core/resource')->getConnection('core_write');
            $tableName      = Mage::getSingleton('core/resource')->getTableName('deal_payment');
            $insertQuery    = "INSERT INTO $tableName VALUES ('','$payerId','$payerEmail','$itemId','$amount','$date','$paymentStatus','yes')";
            $connection->query($insertQuery);
            
            $product_details = Mage::getModel('catalog/product')->load($itemId);
            $product_details->setStatus(1);
            $product_details->save();
            
        }
        // yes valid, f.e. change payment status
    } else {
        // invalid, log error or something
    }
}
?>