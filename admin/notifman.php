<?php
require("header.php");//checks session and login
//calculate the new gen_date
function mdate($date, $period)
{
    include_once "../inc/jdf.php";
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

//send email
function send_email2($id, $body)
{
    $DB = new database;
    $DB->Select('customer', 'email', 'id = ' . $id);
    $recipientNumber = $DB->Select_Result[0]['email'];
    $DB->Select('setting', '*');
    $body = $DB->Select_Result[0]['mailtext'];
    $body = wordwrap($body, 70);
    $send_from = $DB->Select_Result[0]['sendermail'];
    $emailFromTitle = $DB->Select_Result[0]["mailtitle"];
    echo $mailsended = mail("$recipientNumber", "$emailFromTitle", "$body", "From:$emailFromTitle<$send_from>\r\n" . "Reply-To:$send_from\r\n" . "Content-type: text/html; charset=utf-8" . "X-Mailer: PHP/" . phpversion());
}

//=================end of defining functions
if (isset($_POST['type']) && $_POST['type'] == 1) {
    $DB = new database;
    $id = $DB->Escape($_POST['id']);
    $DB->Select('invoice', '*', 'id = ' . $id);
    //------------Sending SMS for after time
    $DB5 = new database;
    $DB5->Select('customer', '*', 'id= ' . $DB->Select_Result[0]['customer_id']);
    $message = 'همکار عزیز
	مبلغ مانده بدهی شما ' . userBalance($DB->Select_Result[0]['customer_id']) . ' ریال است.
	برای چک کردن حساب خود به آدرس
	http://bill.karaneshan.ir
	با نام کاربری: ' . $DB5->Select_Result[0]['username'] . '
	رمزعبور: ' . $DB5->Select_Result[0]['pass'] . '
	مراجعه بفرمائید.
	با تشکر کارانشان';
    $send = sendSMS(findCell($DB->Select_Result[0]['customer_id']), $message);
    send_email2($DB->Select_Result[0]['customer_id'], $message);
} else {
    $DB = new database;
    $id = $DB->Escape($_POST['id']);
    $DB->Select('invoice', '*', 'id = ' . $id);
    //------------Sending SMS for after time
    $DB5 = new database;
    $DB5->Select('customer', '*', 'id= ' . $DB->Select_Result[0]['customer_id']);
    $message = 'همکار عزیز
	فاکتور شماره: ' . $DB->Select_Result[0]['id'] . ' در انتظار پرداخت است.
	لطفا نسبت به پرداخت آن اقدام فرمائید.
	برای چک کردن حساب خود به آدرس
	http://bill.karaneshan.ir
	نام کاربری: ' . $DB5->Select_Result[0]['username'] . '
	رمزعبور: ' . $DB5->Select_Result[0]['pass'] . '
	با تشکر کارانشان';
    $send = sendSMS(findCell($DB->Select_Result[0]['customer_id']), $message);
    send_email2($DB->Select_Result[0]['customer_id'], $message);
}
?>