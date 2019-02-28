<?php
require("header.php");//checks session and login
$page = 'p1';
include "top.php";//includes html top and menu
$DB = new database;
if (isset($_GET['id'])) {
    $id = $DB->Escape($_GET['id']);
    $amount = invoiceBalance($id);
} else {
    $amount = userBalance($_SESSION['userid']);
    $id = 0;
}
if (isset($_POST['pcredit'])) {
    $DB->Select('customer', 'credit', 'id= ' . $_SESSION['userid']);
    $credit = $DB->Select_Result[0]['credit'] - $amount;
    if ($credit < 0) {
        displayError('error', 'موجودی شما کافی نیست!');
        exit;
    }
    $set['credit'] = $credit;
    $updateCredit = $DB->Update('customer', $set, 'id =' . $_SESSION['userid']);
    if ($updateCredit) {
        if ($id > 0) {
            unset($set);
            $set['status'] = 1;
            $set['payment_id'] = 0;
            $updateInvoice = $DB->Update('invoice', $set, 'id =' . $id);
        } else if ($id == 0) {
            unset($set);
            $set['status'] = 1;
            $set['payment_id'] = 0;
            $updateInvoice = $DB->Update('invoice', $set, 'id in ( select id from invoice where customer_id = ' . $_SESSION['userid'] . ' )');
        }
        if ($updateInvoice) {
            displayError('success', '
	  مبلغ ' . $amount . ' ریال از حساب شما کسر گردید
  <br>' . PHP_EOL . 'با تشکر');
            sendSMS('9171110049,9173129242', 'کاربر ' . $_SESSION['user'] . '
  مبلغ ' . $amount . '
 اعتباری پرداخت شد');
        }
    }
} else if (isset($_POST['sub'])) {
    include_once "inc/payment.php";
    echo '<div class="alert alert-success">
			لطفا تا زمان اتصال به درگاه بانک صبور باشید...
			</div>';
    $DB1 = new database;
    $id = $DB1->Escape($_POST['id']);
    $bank = $DB1->Escape($_POST['bank']);
    $resnum = substr(time(), -7) . rand(10, 99);
    if (isset($_POST['userid'])) {
        $DB3 = new database;
        $DB3->Select('invoice', 'id', 'customer_id = ' . $_SESSION['userid'] . ' and gen_date <= curdate()');
        $num = $DB3->Select_Rows;
        for ($i = 0; $i < $num; $i++) {
            $bResult = $DB1->Insert('payment', 'bank,resnum,payer,invoice_id,amount,type', '( "' . $bank . '","' . $resnum . '",' . $_SESSION['userid'] . ',"' . $DB3->Select_Result[$i]['id'] . '","' . $amount . '","1" )');
        }
    } else {
        $bResult = $DB1->Insert('payment', 'bank,resnum,payer,invoice_id,amount,type', '( "' . $bank . '","' . $resnum . '",' . $_SESSION['userid'] . ',"' . $id . '","' . $amount . '","1" )');
    }
    if ($bResult) {
        if ($_POST['bank'] == "mellat") {
            SEND_DATA_TO_MELLAT($amount, $resnum);
        } else if ($_POST['bank'] == "parsian") {
            SEND_DATA_TO_PEC24($amount, $resnum);
        } else if ($_POST['bank'] == "pasargad") {
            SEND_DATA_TO_PASARGAD($amount, $resnum);
        }
    }
}
if (isset($_GET['id'])) {
    $id = $DB->Escape($_GET['id']);
    $DB->Select('invoice', '*', 'customer_id = ' . $_SESSION['userid'] . ' and id = ' . $id);
    if ($DB->Select_Rows == 0) {
        echo "<script language='javascript'> document.location.href='404.php'; </script>";
    }
    $amount = invoiceBalance($DB->Select_Result[0]['id']);
    if (!isset($_GET['id'])) {
        $amount = userBalance($_SESSION['userid']);
    }
}
if (!isset($_POST['sub']) && !isset($_POST['pcredit']) && !isset($_POST['bank'])) {
    ?>

    <div class="page-header">
        <h1>پرداخت صورتحساب</h1>
    </div>
    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ponline" aria-controls="ponline" role="tab"
                                                      data-toggle="tab">پرداخت آنلاین</a></li>
            <li role="presentation"><a href="#pcredit" aria-controls="pcredit" role="tab" data-toggle="tab">پرداخت از
                    اعتبار موجود</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ponline">
                <div class="pad10">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" role="form" class="form-horizontal">
                            <fieldset>
                                <?php
                                if (!isset($_GET['id'])) {
                                    echo '<input  type="hidden"  name="userid" value="' . $_SESSION['userid'] . '" >';
                                    $DB4 = new database;
                                    $DB4->Select('invoice', 'id', 'customer_id = ' . $_SESSION['userid'] . ' and type = 1 and status = 0 and gen_date <= curdate() order by id');
                                    $num = $DB4->Select_Rows;
                                    $id = '';
                                    for ($i = 0; $i < $num; $i++) {
                                        $id .= ' و ' . $DB4->Select_Result[$i]['id'];
                                    }
                                    $id = ltrim($id, ' و ');
                                    $amount = userBalance($_SESSION['userid']);
                                }
                                ?>
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label for="id" class="control-label">شماره فاکتور</label>
                                    </div>
                                    <div class="col-xs-10 inputsize">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control">

                                        <div class="col-xs-10"><?php echo $id; ?> </div>
                                    </div>
                                </div>

                                <!-- /clearfix -->

                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label for="amount" class="control-label">مبلغ قابل پرداخت</label>
                                    </div>
                                    <div class="col-xs-10 inputsize">
                                        <input type="hidden" name="amount" value="<?php echo $amount; ?>"
                                               class="form-control">

                                        <div class="col-xs-10"><?php echo number_format($amount); ?> ریال</div>
                                    </div>
                                </div>

                                <!-- /clearfix -->

                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label for="bank" class="control-label">درگاه پرداخت</label>
                                    </div>
                                    <div class="col-xs-10 inputsize">
                                        <select name="bank" id="bank" class="form-control">
                                            <?php
                                            $DB1 = new database;
                                            $DB1->Select('setting');
                                            if ($DB1->Select_Result[0]['activeparsian'] == 1) {
                                                echo '<option value="parsian">پارسیان </option>';
                                            }
                                            $DB1->Select('setting');
                                            if ($DB1->Select_Result[0]['activepasargad'] == 1) {
                                                echo '<option value="pasargad">پاسارگاد </option>';
                                            }
                                            $DB1->Select('setting');
                                            if ($DB1->Select_Result[0]['activemellat'] == 1) {
                                                echo '<option value="mellat"> ملت</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="پرداخت" name="sub" class="btn btn-primary">
                                        &nbsp;
                                        <button class="btn btn-default" type="reset"
                                                onClick="document.location.href='dashboard.php'">لغو
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="pcredit">
                <div class="pad10">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" role="form" class="form-horizontal">
                            <fieldset>
                                <?php
                                if (!isset($_GET['id'])) {
                                    echo '<input  type="hidden"  name="userid" value="' . $_SESSION['userid'] . '" >';
                                    $DB4 = new database;
                                    $DB4->Select('invoice', 'id', 'customer_id = ' . $_SESSION['userid'] . ' and type = 1 and status = 0 and gen_date <= curdate() order by id');
                                    $num = $DB4->Select_Rows;
                                    $id = '';
                                    for ($i = 0; $i < $num; $i++) {
                                        $id .= ' و ' . $DB4->Select_Result[$i]['id'];
                                    }
                                    $id = ltrim($id, ' و ');
                                    $amount = userBalance($_SESSION['userid']);
                                }
                                ?>
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label for="id" class="control-label">شماره فاکتور</label>
                                    </div>
                                    <div class="col-xs-10 inputsize">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control">

                                        <div class="col-xs-10"><?php echo $id; ?> </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <label for="amount" class="control-label">مبلغ قابل پرداخت</label>
                                    </div>
                                    <div class="col-xs-10 inputsize">
                                        <input type="hidden" name="amount" value="<?php echo $amount; ?>">

                                        <div class="col-xs-10"><?php echo number_format($amount); ?> ریال</div>
                                    </div>
                                </div>

                                <!-- /clearfix -->

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="پرداخت" name="pcredit" class="btn btn-primary">
                                        &nbsp;
                                        <button class=" btn btn-default" type="reset"
                                                onClick="document.location.href='dashboard.php'">لغو
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>
<?php
include("footer.php");
?>

<!-- /container -->

</body></html>