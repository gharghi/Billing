<?php
function getPost()
{
    $DB = new database;
    foreach ($_POST as $variable => $value) {
        global $$variable;
        $$variable = $DB->Escape($value);
    }
}

function findGrp2($grp)
{
    switch ($grp) {
        case 1:
            return 'هيئت مديره';
            break;
        case 2:
            return 'شرکت';
            break;
        case 3:
            return 'كارمندها';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findGrp($grp)
{
    switch ($grp) {
        case 1:
            return 'ISP';
            break;
        case 2:
            return 'شرکت';
            break;
        case 3:
            return 'شخصی';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findtrans($a)
{
    switch ($a) {
        case 1:
            return 'برداشت';
            break;
        case 2:
            return 'پرداخت';
            break;
        case 3:
            return 'حقوق';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findType($type)
{
    switch ($type) {
        case 1:
            return 'عادی';
            break;
        case 2:
            return 'دوره ای';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findStatus($status)
{
    switch ($status) {
        case 0:
            return '<span class="red" >پرداخت نشده</span>';
            break;
        case 1:
            return '<span class="blue" >پرداخت شده</span>';
            break;
        case 2:
            return 'لغو شده';
            break;
        case 3:
            return '<span class="orange" >در انتظار تائید</span>';
            break;
        case 4:
            return '<span class="red" >تائید نشده</span>';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findPayment($status)
{
    switch ($status) {
        case 1:
            return 'پرداخت آنلاین';
            break;
        case 2:
            return 'فیش بانکی';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

function findBank($bank)
{
    switch ($bank) {
        case 'mellat':
            return 'بانک ملت';
            break;
        case 'parsian':
            return 'بانک پارسیان';
            break;
        case 'pasargad':
            return 'بانک پاسارگاد';
            break;
        case 9:
            return 'دستی';
            break;
        default:
            return $bank;
            break;
    }
}

function findPayStatus($status)
{
    switch ($status) {
        case 0:
            return '<p class="red" >ناموفق</p>';
            break;
        case 1:
            return '<p class="blue" >موفق</p>';
            break;
        case 2:
            return 'لغو شده';
            break;
        default:
            return 'نا مشخص';
            break;
    }
}

//total balance of each customer
function userBalance($id)
{
    $DB = new database;
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
    $DB = new database;
    $DB2 = new database;
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

//returns the name of notification by id
function notifName($id)
{
    $DB = new database;
    $DB->Select('notification', 'name', 'id = ' . $id);
    if ($DB->Select_Result[0]['name']) {
        return $DB->Select_Result[0]['name'];
    } else return 'انتخاب نشده';
}

//convert gregorian to jalali
function fdate($date)
{
    include_once "jdf.php";
    return jdate("l Y/m/d", strtotime($date));
}

//convert gregorian to jalali for invoice
function infdate($date)
{
    include_once "jdf.php";
    return jdate("Y/m/d", strtotime($date));
}

//convert gregorian to jalali in diffrent format
function sfdate($date)
{
    include_once "jdf.php";
    return jdate("Y-m-d", strtotime($date));
}

//convert jalali to gregorian
function gdate($date)
{
    include_once "jdf.php";
    $d = explode('-', $date);
    return jalali_to_gregorian($d[0], $d[1], $d[2], '-');
}

//display current date in jalali
function curjdate()
{
    include_once "jdf.php";
    return jdate('Y-n-j');
}

function sendSMS($recipientNumber, $message)
{
    $numbers = is_array($recipientNumber) ? $recipientNumber : explode(',', $recipientNumber);
    ini_set("soap.wsdl_cache_enabled", "0");
    $DB = new database;
    $DB->Select('setting', '*');
    $client = new SoapClient($DB->Select_Result[0]["smsurl"]);
    $encoding = "UTF-8";
    $textMessage = iconv($encoding, 'UTF-8//TRANSLIT', $message);
    $sendsms_parameters = array('username' => $DB->Select_Result[0]["smsuser"], 'password' => $DB->Select_Result[0]["smspass"], 'from' => $DB->Select_Result[0]["smsnumber"], 'to' => $numbers, 'text' => $textMessage, 'isflash' => false, 'udh' => "", 'recId' => array(0), 'status' => 0);
    return $client->SendSms($sendsms_parameters)->SendSmsResult;
}



function displayError($class, $message)
{
    echo '<div class="alert alert-' . $class . '">

				' . $message . '

				</div>';
}

function nlToBr($string)
{
    return str_replace('rn', '<br />', $string);
}

function nlToEnter($string)
{
    return str_replace('rn', '&#13;&#10', $string);
}

function nlTospace($string)
{
    return str_replace('rn', ' ', $string);
}

function addCredit($id, $amount)
{
    $DB = new database;
    $DB->Select('customer', 'id,credit', 'id = ' . $id);
    $credit = $amount + $DB->Select_Result[0]['credit'];
    unset($set);
    $set['credit'] = $credit;
    return $DB->Update('customer', $set, 'id= ' . $id);
}

function totalTranscation()
{
    $DB = new database;
    $DB->Select('payment', 'SUM(amount) as total', 'payer = ' . $_SESSION['userid'] . ' and status = 1 ');
    return $DB->Select_Result[0]['total'];
}

function findCell($id)
{
    $DB = new database;
    $DB->Select('customer', 'cell', 'id = ' . $id);
    return array($DB->Select_Result[0]['cell']);
}

function getSalary($id)
{
    $DB = new database;
    $id = $DB->Escape($id);
    $DB->Free('select SUM(amount) as exp from cache where type = 3 and account_id = ' . $id);
    return $DB->Select_Result[0]['exp'];
}

function getBalance($id)
{
    $DB = new database;
    $id = $DB->Escape($id);
    $DB->Free('select (select coalesce( SUM( amount ) , 0 ) from cache where type = 2 and account_id = ' . $id . ' ) - (select coalesce( SUM( amount ) , 0 ) from cache where type = 1 and account_id = ' . $id . ' ) as exp');
    return $DB->Select_Result[0]['exp'];
}

function resolveInvoice($id)
{
    $DB = new database;
    $id = $DB->Escape($id);
    $DB->Select('invoice', 'invoice_name', 'id= ' . $id);
    return 'فاكتور ' . $DB->Select_Result[0]['invoice_name'];
}

function resolveCus($id)
{
    $DB = new database;
    $id = $DB->Escape($id);
    $DB->Select('customer', 'name', 'id= ' . $id);
    return $DB->Select_Result[0]['name'];
}

function resolveAccount($id)
{
    $DB = new database;
    $id = $DB->Escape($id);
    $DB->Select('account', 'name', 'id= ' . $id);
    return $DB->Select_Result[0]['name'];
}
?>