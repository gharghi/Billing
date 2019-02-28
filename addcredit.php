<?php
require("header.php");//checks session and login
$page = 'p3';
include "top.php";//includes html top and menu
if (isset($_POST['amount'])) {
    if (isset($_POST['sub'])) {
        include_once "inc/payment.php";
        echo '<div class="alert-message success">

				<p>لطفا تا زمان اتصال به درگاه بانک صبور باشید...</p>

				</div>';
        $DB1 = new database;
        $amount = (int)$DB1->Escape($_POST['amount']);
        $credit = $DB1->Escape($_POST['credit']);
        $bank = $DB1->Escape($_POST['bank']);
        $resnum = substr(time(), -7) . rand(10, 99);
        $bResult = $DB1->Insert('payment', 'bank,resnum,payer,amount,type', '( "' . $bank . '","' . $resnum . '","' . $_SESSION['userid'] . '","' . $amount . '",1 )');
        if ($bResult) {
            if ($_POST['bank'] == "mellat") {
                SEND_DATA_TO_MELLAT($amount, $resnum, $credit);
            } else if ($_POST['bank'] == "parsian") {
                SEND_DATA_TO_PEC24($amount, $resnum, $credit);
            } else if ($_POST['bank'] == "pasargad") {
                SEND_DATA_TO_PASARGAD($amount, $resnum, $credit);
            }
        }
    }
}
if (!isset($_POST['sub'])) {
    ?>

    <div class="page-header">

        <h1>افزایش اعتبار</h1>

    </div>

    <div class="row">

        <div class="col-lg-12">

            <form method="POST" role="form" class="form-horizontal">

                <fieldset>

                    <div class="form-group">

                        <div class="col-xs-2">

                            <label for="amount" class="control-label">مبلغ </label>

                        </div>

                        <div class="col-xs-10 inputsize">

                            <input type="text" class="form-control" name="amount" placeholder="مبلغ به ریال">

                        </div>

                    </div>


                    <input type="hidden" name="credit" value="1">

                    <div class="form-group">

                        <div class="col-xs-2">

                            <label for="bank" class="control-label">درگاه پرداخت</label>

                        </div>

                        <div class="col-xs-10 inputsize">

                            <select name="bank" id="bank" class="form-control">

                                <?php
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

<?php } ?>

</div>

</div>

<?php
include("footer.php");
?>

</body></html>