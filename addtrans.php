<?php
require("header.php");//checks session and login
$page = 'p7';
//here we do our php actions
if (isset($_POST['sub'])) {
    $DB = new database;
    $refnum = $DB->Escape($_POST['refnum']);
    $bank = $DB->Escape($_POST['bank']);
    $amount = $DB->Escape($_POST['amount']);
    $add = $DB->Insert('payment', 'refnum,bank,payer,amount,status,type', '( "' . $refnum . '","' . $bank . '","' . $_SESSION['userid'] . '","' . $amount . '", "3" , "2" )');
    if ($add) {
        $status = '<div class="alert alert-success">

		پرداختی با موفقیت افزوده شد و پس از تائید به حساب شما منظور خواهد شد.

		</div>';
    } else {
        $status = '<div class="alert alert-danger">

		خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

		</div>';
    }
}
include "top.php";//includes html top and menu
?>

<div class="page-header">

    <h1>افزودن پرداختی به صورت فيش بانكي</h1>

</div>

<div class="row">

    <div class="col-lg-12">


        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">

            <fieldset>

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="bank" class="control-label">بانک</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="bank" id="bank" class="form-control">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="amount" class="control-label">مبلغ</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="amount" id="amount" class="form-control">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="refnum" class="control-label">شماره رسید</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                        <input type="text" size="30" name="refnum" id="refnum" class="form-control"
                               placeholder="شماره فیش واریزی">

                    </div>

                </div>

                <!-- /clearfix -->

                <div class="form-group">

                    <div class="col-sm-offset-2 col-sm-10">

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">

                        &nbsp;

                        <button class="btn btn-default" type="button"
                                onClick="document.location.href='transaction.php'">لغو
                        </button>

                    </div>

                </div>

            </fieldset>

        </form>

    </div>

</div>

</div>

<?php
include("footer.php");
?>

</div>

<!-- /container -->

</body></html>