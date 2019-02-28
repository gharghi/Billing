<?php
include '/home/persianadm/domains/bill.karaneshan.ir/public_html/cron/class.logindb.inc.php';
function userBalance($id)
{
    $DB = new logindb;
    $DB->Select('invoice', 'id', 'customer_id = ' . $id . ' and type = 1 and status = 0 and gen_date <= curdate()');
    $num = $DB->Select_Rows;
    $sum = 0;
    for ($i = 0; $i < $num; $i++) {
        $sum += invoiceBalance($DB->Select_Result[$i]['id']);
    }
    return (int)$sum;
}

//total balance of each invoice
function invoiceBalance($id)
{
    $DB = new logindb;
    $DB2 = new logindb;
    $DB->Select('item', 'SUM(price * quantity) as balance', 'invoice_id = ' . $id);
    $price = $DB->Select_Result[0]['balance'];
    $DB2->Select('invoice', 'vat,discount', 'id = ' . $id);
    $vat = $DB2->Select_Result[0]['vat'];
    $discount = $DB2->Select_Result[0]['discount'];
    if ($price) {
        $afterDiscount = $price - ($price / 100 * $discount);
        return (int)($afterDiscount + ($afterDiscount / 100 * $vat));
    } else {
        return 0;
    }
}

//calculate the new gen_date
function mdate($date, $period)
{
    include_once "/home/persianadm/domains/bill.karaneshan.ir/public_html/inc/jdf.php";
    $date2 = tr_num(jdate("Y-m-d", strtotime($date)));
    $d = explode('-', $date2);
    $new = $d[2] + $period;
    return jalali_to_gregorian($d[0], $d[1], $new, '-');
}

//display current date in jalali
function curdate()
{
    return date("Y-m-d");
}

//send sms
function sendSMS($id, $message)
{
    $DB = new logindb;
    $DB->Select('customer', 'cell', 'id = ' . $id);
    $recipientNumber = $DB->Select_Result[0]['cell'];
    $DB->Select('setting', '*', 'id = 1');
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient($DB->Select_Result[0]["smsurl"]);
    $encoding = "UTF-8";//CP1256, CP1252
    $textMessage = iconv($encoding, 'UTF-8//TRANSLIT', $message);
    $sendsms_parameters = array('username' => $DB->Select_Result[0]["smsuser"], 'password' => $DB->Select_Result[0]["smspass"], 'from' => $DB->Select_Result[0]["smsnumber"], 'to' => array($recipientNumber), 'text' => $textMessage, 'isflash' => false, 'udh' => "", 'recId' => array(0), 'status' => 0);
    $send = $client->SendSms($sendsms_parameters)->SendSmsResult;
    echo "a SMS has been sent to user number: " . $id . " <br />" . PHP_EOL;
    return $send;
}

//send email
function send_email($id, $body)
{
    $DB = new logindb;
    $DB->Select('customer', 'email', 'id = ' . $id);
    $recipientNumber = $DB->Select_Result[0]['email'];
    $DB->Select('setting', '*');
    $body = $DB->Select_Result[0]['mailtext'];
    $body = wordwrap($body, 70);
    $send_from = $DB->Select_Result[0]['sendermail'];
    $emailFromTitle = $DB->Select_Result[0]["mailtitle"];
    echo $mailsended = mail("$recipientNumber", "$emailFromTitle", "$body", "From:$emailFromTitle<$send_from>\r\n" . "Reply-To:$send_from\r\n" . "Content-type: text/html; charset=utf-8" . "X-Mailer: PHP/" . phpversion());
    echo "an email has been sent to user number: " . $id . " <br />" . PHP_EOL;
}

//=================end of defining functions
$DB = new logindb;
$DB->Select('invoice INNER JOIN notification on invoice.notification_id=notification.id', '*,invoice.id as inID,notification.id as nID', 'invoice.type = 1 and invoice.status = 0 and invoice.gen_date >= curdate()');
$num = $DB->Select_Rows;
//------------Sending SMS for before time
for ($i = 0; $i < $num; $i++) {
    $checkWarnDate = mdate($DB->Select_Result[$i]['gen_date'], -$DB->Select_Result[$i]['pre_warn_date']);
    $w = $count = $DB->Select_Result[$i]['reminded_count'];
    $s = $pre_sms = $DB->Select_Result[$i]['pre_sms'];
    $pre_interval = $DB->Select_Result[$i]['pre_interval'];
    if ($pre_sms <= $count) {
        continue;
    }
    if (strtotime($checkWarnDate) > strtotime(curdate())) {
        continue;
    }
    if ((strtotime(mdate($DB->Select_Result[$i]['warn_date'], $pre_interval)) != strtotime(curdate())) && ($DB->Select_Result[$i]['warn_date']) == ('0000-00-00 00:00:00')) {
        continue;
    }
    $DB5 = new logindb;
    $DB5->Select('customer', '*', 'id= ' . $DB->Select_Result[$i]['customer_id']);
    $message = 'همکار عزیز
	فاکتور جدیدی به شماره: ' . $DB->Select_Result[$i]['inID'] . ' برای شما صادر گردیده است.
	لطفا نسبت به پرداخت آن اقدام فرمائید.
	مانده کل بدهی شما: ' . userBalance($DB->Select_Result[$i]['customer_id']) . ' ریال است.
	برای چک کردن حساب خود به آدرس
	http://bill.karaneshan.ir
	نام کاربری: ' . $DB5->Select_Result[0]['username'] . '
	رمزعبور: ' . $DB5->Select_Result[0]['pass'] . '
	با تشکر کارانشان';
    $send = sendSMS($DB->Select_Result[$i]['customer_id'], $message);
    if ($send != 0) {
        $DB1 = new logindb;
        $count++;
        $set['reminded_count'] = $count;
        if ($pre_sms == $count) {
            $set['warn_date'] = curdate();
        } else {
            $set['warn_date'] = '0000-00-00 00:00:00';
        }
        $DB1->update('invoice', $set, 'id = ' . $DB->Select_Result[$i]['inID']);
        echo $pre_sms, '<br>', $count;
    }
}
//------------Sending SMS for after time
$DB = new logindb;
$DB->Select('invoice INNER JOIN notification on invoice.notification_id=notification.id', '*,invoice.id as inID,notification.id as nID', 'invoice.type = 1 and invoice.status= 0 and invoice.gen_date < curdate()');
$num = $DB->Select_Rows;
for ($i = 0; $i < $num; $i++) {
    $count = $DB->Select_Result[$i]['reminded_count'];
    $post_sms = $DB->Select_Result[$i]['post_sms'] + $DB->Select_Result[$i]['pre_sms'];
    $post_interval = $DB->Select_Result[$i]['post_interval'];
    if ($post_sms <= $count) {
        continue;
    }
    if (strtotime($DB->Select_Result[$i]['exp_date']) <= strtotime(curdate())) {
        continue;
    }
    if ((strtotime(mdate($DB->Select_Result[$i]['warn_date'], $post_interval)) > strtotime(curdate())) && ($DB->Select_Result[$i]['warn_date']) != ('0000-00-00 00:00:00')) {
        continue;
    }
    $DB5 = new logindb;
    $DB5->Select('customer', '*', 'id= ' . $DB->Select_Result[$i]['customer_id']);
    $message = 'همکار عزیز
	فاکتور شماره: ' . $DB->Select_Result[$i]['inID'] . ' در انتظار پرداخت است.
	لطفا نسبت به پرداخت آن اقدام فرمائید.
	مانده کل بدهی شما: ' . userBalance($DB->Select_Result[$i]['customer_id']) . ' ریال است.
	برای چک کردن حساب خود به آدرس
	http://bill.karaneshan.ir
	نام کاربری: ' . $DB5->Select_Result[0]['username'] . '
	رمزعبور: ' . $DB5->Select_Result[0]['pass'] . '
	با تشکر کارانشان';
    $send = sendSMS($DB->Select_Result[$i]['customer_id'], $message);
    $DB1 = new logindb;
    $set['warn_date'] = curdate();
    $count++;
    $set['reminded_count'] = $count;
    $DB1->update('invoice', $set, 'id = ' . $DB->Select_Result[$i]['inID']);
}
?>