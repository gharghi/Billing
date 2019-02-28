<?php
require("header.php");//checks session and login
$page = 'p1';
include "top.php";//includes html top and menu
include_once "inc/payment.php";
$DB = new database;
if (isset($_GET['gateway'])) {
    $cr = $DB->Escape($_GET['cr']);
    $gateway = $DB->Escape($_GET['gateway']);
    $orderId = $DB->Escape($_GET['orderId']);
    include_once("inc/payment.php");
    $search = $DB->Select('payment', '*', 'resnum= ' . $orderId);
    if ($DB->Select_Rows < 1) {
        displayError("error", "تراکنش فعالی با این کد در سیستم  موجود نمی باشد");
        die();
    }
    if ($gateway == "mellat") {//verify for Mellat returning
        $RefId = $DB->Escape($_POST['RefId']);
        $ResCode = $DB->Escape($_POST['ResCode']);
        $SaleOrderId = $DB->Escape($_POST['SaleOrderId']);
        $SaleReferenceId = $DB->Escape($_POST['SaleReferenceId']);
        $orderkey = $SaleOrderId;
        if (checkDuplicate($orderId, $SaleReferenceId)) {//prevent payment spoofing
            if (VERIFY_PROCCES_MELLAT($orderId, $SaleOrderId, $SaleReferenceId)) {
                if (SETTEL_PROCCES_MELLAT($orderId, $SaleOrderId, $SaleReferenceId)) {
                    $DB2 = new database;
                    $set['refnum'] = $SaleReferenceId;
                    $set['status'] = 1;
                    $updatePayment = $DB->Update('payment', $set, 'resnum= ' . $orderId);
                    if ($updatePayment && $cr == 0) {
                        $DB2->Select('payment', 'invoice_id,id', 'resnum= ' . $orderId);
                        unset($set);
                        $set['status'] = 1;
                        $set['payment_id'] = $DB2->Select_Result[0]['id'];
                        $updateInvoice = $DB->Update('invoice', $set, 'id in ( select invoice_id from payment where resnum = ' . $orderId . ' )');
                        if ($updateInvoice) {
                            displayError('success', '
							عملیات پرداخت با موفقیت انجام گردید
							شماره پیگیری  : ' . $orderId . '

						<br>' . PHP_EOL . 'با تشکر');
                            sendSMS('9171110049,9173129242', 'فاکتور ' . $DB->Select_Result[0]['invoice_id'] . '
						مبلغ ' . $DB->Select_Result[0]['amount'] . '
						درگاه ملت
						پرداخت گردید');
                        } else {
                            displayError('error', 'تائید تراکنش با مشکل واجه شد. مبلغ به حساب شما باز خواهد گشت');
                        }
                    } else if ($updatePayment && $cr == 1) {
                        $DB2->Select('payment', '*', 'resnum = ' . $orderId);
                        unset($set);
                        $DB3 = new database;
                        $DB3->Select('customer', 'credit', 'id=' . $_SESSION['userid']);
                        $credit = $DB3->Select_Result[0]['credit'];
                        $credit = $credit + $DB2->Select_Result[0]['amount'];
                        $set['credit'] = $credit;
                        $updateInvoice = $DB->Update('customer', $set, 'id =' . $_SESSION['userid']);
                        if ($updateInvoice) {
                            displayError('success', '
							عملیات پرداخت با موفقیت انجام گردید
							شماره پیگیری  : ' . $orderId . '

						<br>' . PHP_EOL . 'با تشکر');
                            sendSMS('9171110049,9173129242', 'کاربر ' . $_SESSION['user'] . '

						مبلغ ' . $DB->Select_Result[0]['amount'] . '
						درگاه ملت
						پرداخت گردید');
                        } else {
                            displayError('danger', 'تائید تراکنش با مشکل واجه شد. مبلغ به حساب شما باز خواهد گشت');
                        }
                    } else displayError('danger', 'خطا در ثبت اطلاعات حساب');
                }
            }
        } else displayError('danger', 'شناسه برگشتی از بانک اعتبار ندارد');
    } elseif ($gateway == "parsian") {
        $au = $DB->Escape($_GET["au"]);
        if (checkDuplicate($orderId, $au)) {
            if (VERIFY_PROCCES_Parsian($au)) {
                $DB2 = new database;
                $set['refnum'] = $au;
                $set['status'] = 1;
                $updatePayment = $DB->Update('payment', $set, 'resnum= ' . $orderId);
                if ($updatePayment && $cr == 0) {
                    $DB2->Select('payment', 'invoice_id,id', 'resnum= ' . $orderId);
                    unset($set);
                    $set['status'] = 1;
                    $set['payment_id'] = $DB2->Select_Result[0]['id'];
                    $updateInvoice = $DB->Update('invoice', $set, 'id in ( select invoice_id from payment where resnum = ' . $orderId . ' )');
                    if ($updateInvoice) {
                        displayError('success', '
					  عملیات پرداخت با موفقیت انجام گردید
					  شماره پیگیری  : ' . $orderId . '
					  <br>' . PHP_EOL . 'با تشکر');
                        sendSMS('9171110049,9173129242', 'فاکتور ' . $DB->Select_Result[0]['invoice_id'] . '

					  مبلغ ' . $DB->Select_Result[0]['amount'] . '
					  درگاه پارسیان
					  پرداخت گردید');
                    }
                } else if ($updatePayment && $cr == 1) {
                    $DB2->Select('payment', '*', 'resnum = ' . $orderId);
                    unset($set);
                    $DB3 = new database;
                    $DB3->Select('customer', 'credit', 'id=' . $_SESSION['userid']);
                    $credit = $DB3->Select_Result[0]['credit'];
                    $credit = $credit + $DB2->Select_Result[0]['amount'];
                    $set['credit'] = $credit;
                    $updateInvoice = $DB->Update('customer', $set, 'id =' . $_SESSION['userid']);
                    if ($updateInvoice) {
                        displayError('success', '
					عملیات پرداخت با موفقیت انجام گردید
					شماره پیگیری  : ' . $orderId . '
				<br>' . PHP_EOL . 'با تشکر');
                        sendSMS('9171110049,9173129242', 'کاربر ' . $_SESSION['user'] . '

				مبلغ ' . $DB->Select_Result[0]['amount'] . '
				درگاه ملت
				پرداخت گردید');
                    } else {
                        displayError('danger', 'تائید تراکنش با مشکل واجه شد. مبلغ به حساب شما باز خواهد گشت');
                    }
                } else displayError('danger', 'خطا در ثبت اطلاعات حساب');
            }
        }
    } elseif ($gateway == "pasargad") {
        $retprice = $DB->Escape($_GET['price']);
        $retorderid = $DB->Escape($_GET['orderId']);
        $iN = $DB->Escape($_GET['iN']);
        $ref = $DB->Escape($_GET['tref']);
        $retdate = $DB->Escape($_GET['iD']);
        if (checkDuplicate($retorderid, $ref)) {
            if (VERIFY_PASARGAD($_GET['tref'], $orderId, $retdate, $retprice)) {
                $DB2 = new database;
                $set['refnum'] = $ref;
                $set['status'] = 1;
                $updatePayment = $DB->Update('payment', $set, 'resnum= ' . $retorderid);
                if ($updatePayment && $cr == 0) {
                    $DB2->Select('payment', 'invoice_id,id', 'resnum= ' . $retorderid);
                    unset($set);
                    $set['status'] = 1;
                    $set['payment_id'] = $DB2->Select_Result[0]['id'];
                    $updateInvoice = $DB->Update('invoice', $set, 'id in ( select invoice_id from payment where resnum = ' . $retorderid . ' )');
                    if ($updateInvoice) {
                        displayError('success', '
						عملیات پرداخت با موفقیت انجام گردید
						شماره پیگیری  : ' . $orderId . '

					<br>' . PHP_EOL . 'با تشکر');
                        sendSMS('9171110049,9173129242', 'فاکتور ' . $DB->Select_Result[0]['invoice_id'] . '

					مبلغ ' . $DB->Select_Result[0]['amount'] . '
					درگاه پاسارگاد
					پرداخت گردید');
                    } else {
                        displayError('danger', 'تائید تراکنش با مشکل واجه شد. مبلغ به حساب شما باز خواهد گشت');
                    }
                } else if ($updatePayment && $cr == 1) {
                    $DB2->Select('payment', '*', 'resnum = ' . $retorderid);
                    unset($set);
                    $DB3 = new database;
                    $DB3->Select('customer', 'credit', 'id=' . $_SESSION['userid']);
                    $credit = $DB3->Select_Result[0]['credit'];
                    $credit = $credit + $DB2->Select_Result[0]['amount'];
                    $set['credit'] = $credit;
                    $updateInvoice = $DB->Update('customer', $set, 'id =' . $_SESSION['userid']);
                    if ($updateInvoice) {
                        displayError('success', '
					عملیات پرداخت با موفقیت انجام گردید
					شماره پیگیری  : ' . $orderId . '

				<br>' . PHP_EOL . 'با تشکر');
                        sendSMS('9171110049,9173129242', 'کاربر ' . $_SESSION['user'] . '

				مبلغ ' . $DB->Select_Result[0]['amount'] . '
				درگاه ملت
				پرداخت گردید');
                    } else {
                        displayError('danger', 'تائید تراکنش با مشکل واجه شد. مبلغ به حساب شما باز خواهد گشت');
                    }
                } else displayError('danger', 'خطا در ثبت اطلاعات حساب');
            } else displayError('danger', 'عملیات پرداخت به درستی صورت نگرفته است!');
        } else displayError('danger', 'شناسه برگشتی از بانک اعتبار ندارد');
    }
}
?>

<div class="row">

    <a href="dashboard.php"> بازگشت به صفحه اصلی</a>

</div>

</div>

</div>

<?php
include("footer.php");
?>

</body>

</html>