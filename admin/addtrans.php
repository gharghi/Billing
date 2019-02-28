<?php
require("header.php");//checks session and login
$page = 'p7';
//here we do our php actions
if (isset($_POST['sub'])) {
    if (($_POST['customer_id'] == 0) || empty($_POST['amount'])) {
        $update = false;
    } else {
        $DB = new database;
        $refnum = $DB->Escape($_POST['refnum']);
        $bank = $DB->Escape($_POST['bank']);
        $amount = $DB->Escape($_POST['amount']);
        $customer_id = $DB->Escape($_POST['customer_id']);
        $add = $DB->Insert('payment', 'refnum,bank,payer,amount,status,type', '( "' . $refnum . '","' . $bank . '","' . $customer_id . '","' . $amount . '", "1" , "2" )');
        $update = addCredit($customer_id, $amount);
    }
    if ($update) {
        $status = '<div class="alert alert-success">
		پرداختی با موفقیت افزوده شد.
		(<a href="transaction.php?id='.$customer_id.'" >مشاهده تراكنش ها</a>)</div>';
    } else {
        $status = '<div class="alert alert-danger">
		خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .
		</div>';
    }
}
include "top.php";//includes html top and menu
?>

<div class="page-header">
    <?php
    if (isset($_GET['id'])) {
        ?>
        <h1>افزودن پرداختي به<a href="edituser.php?id=<?php

            echo $_GET['id']; ?>" class="titleA">
                <?php echo resolveCus($_GET['id']); ?></a></h1>
        <?php
    }
    else {
        ?>
        <h1>افزودن پرداختی به صورت دستی</h1>
        <?php
    }
    ?>

</div>

<div class="row">

    <div class="col-lg-12">

        <?php if (isset($status)) echo $status; ?>

        <form method="POST" role="form" class="form-horizontal">


            <fieldset>
                <?php

                if (isset($_GET['id'])) {
                    echo '<input type="hidden"  value="'.$_GET['id'].'"
                            name="customer_id">';
                }
                else {
                ?>

                <div class="form-group">

                    <div class="col-xs-2">

                        <label for="customer_id" class="control-label">مشتری</label>

                    </div>

                    <div class="col-xs-10 inputsize">

                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="0">انتخاب کنید</option>
                                <?php
                                $DB2 = new database;
                                $DB2->Select('customer', 'id,name');
                                $num = $DB2->Select_Rows;
                                $i = 0;
                                while ($i < $num) {
                                    echo " <option value='" . $DB2->Select_Result[$i]['id'] . "'>" . $DB2->Select_Result[$i]['name'] . "</option>";
                                    $i++;
                                }
                                ?>
                            </select>


                    </div>

                </div>
                    <?php
                }
                ?>

                <input type="hidden" size="30" name="payer" value="2">

                <!-- /clearfix -->

                <div id="ajax1"></div>

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

                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">&nbsp;

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
include("../footer.php");
?>


</div> <!-- /container -->


</body>

</html>