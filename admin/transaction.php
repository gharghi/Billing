<?php
require("header.php");//checks session and login
$page = 'p7';
include "top.php";//includes html top and menu
?>
<script language="javascript">
    function delPayment(id) {

        $.fancybox({

            modal: false,

            minWidth: 300,

            autoHeight: true,

            scrolling: 'no',

            openEffect: 'none',

            openSpeed: 100,

            closeEffect: 'none',

            closeSpeed: 100,

            preload: true,

            overlayShow: true,

            content: "<div>آیا از حذف اطمینان دارید؟</div><div class='actions'><input type='button' value='بلی'  onclick='del(" + id + ");' class='btn primary'>&nbsp;<button class='btn' type='button' onclick='$.fancybox.close();'>خیر</button></div>",

            helpers: {


                overlay: {

                    css: {'background': 'rgba(192, 196, 207, 0.8)'},

                    locked: false

                }

            }

        });

    }


    function del(ID) {

        $.post("delpayment.php", {id: ID}, function (data) {

            if (data == 0) {

                $.fancybox.close();

                $('#error2').fadeIn(600);

            }

            else if (data == 1) {

                $.fancybox.close();

                location.reload();

            }

            else {

                $.fancybox.close();

                $('#error2').fadeOut(600);

            }

        });

    }

    function savePayment(id) {

        $.fancybox({

            modal: false,

            minWidth: 300,

            autoHeight: true,

            scrolling: 'no',

            openEffect: 'none',

            openSpeed: 100,

            closeEffect: 'none',

            closeSpeed: 100,

            preload: true,

            overlayShow: true,

            content: "<div>آیا از ثبت اطمینان دارید؟</div><div class='actions'><input type='button' value='بلی'  onclick='save(" + id + ");' class='btn primary'>&nbsp;<button class='btn' type='button' onclick='$.fancybox.close();'>خیر</button></div>",

            helpers: {


                overlay: {

                    css: {'background': 'rgba(192, 196, 207, 0.8)'},

                    locked: false

                }

            }

        });

    }


    function save(ID) {

        $.post("savepayment.php", {id: ID}, function (data) {

            if (data == 0) {

                $.fancybox.close();

                $('#error2').fadeIn(600);

            }

            else if (data == 1) {

                $.fancybox.close();

                location.reload();

            }

            else {

                $.fancybox.close();

                $('#error2').fadeOut(600);

            }

        });

    }
</script>
<div class="page-header">

    <?php
    $id='';
     if (isset($_GET['id'])) {
        $id=$_GET['id'];
        ?>
        <h1 >ليست پرداختهاي <a href="edituser.php?id=<?php
            $DB = new database;
            echo  $DB->Escape($_GET['id']); ?>" class="titleA">
                <?php echo resolveCus($_GET['id']); ?></a></h1 >
        <?php
    }
    else {
        ?>
        <h1>ليست پرداختها</h1>
        <?php
    }
    ?>




</div>
<div class="row">
    <div class="actions" style="margin-left:20px;">
        <input type="button" onClick="document.location.href='addtrans.php?id=<?php echo $_GET['id']; ?>';"
               value="افزودن پرداختی"
               class="btn btn-primary">
    </div>
    <div class="col-lg-12">
        <div class="alert alert-danger" id="error2" style="display:none;"> خطایی در ارسال پارامتر ها به وجود آمده است!
            دوباره امتحان کنید .
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>نوع</th>
                <th>ذينفع</th>
                <th class="hidden-xs">تاریخ پرداخت</th>
                <th>وضعیت</th>
                <th>مبلغ فاکتور</th>
                <th class="hidden-xs">درگاه پرداخت</th>
                <th class="hidden-xs">پیگیری داخلی</th>
                <th class="hidden-xs">پیگیری بانک</th>
                <th>دستورها</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $DB = new database;
            if (!isset($_GET['p']) || empty($_GET['p'])) {
                $lim = 1;
            } else {
                $lim = $DB->Escape($_GET['p']);
            }
            if ($lim == 'All') {
                $pagin = ' ';
            } else {
                $start = $lim * $_SESSION['resultperpage'];
                $start -= $_SESSION['resultperpage'];
                $end = $_SESSION['resultperpage'];
                $pagin = 'LIMIT ' . $start . ',' . $end;
            }
            $customer = '';
            $id;
            if (isset($_GET['id'])&&!empty($_GET['id'])) {
                $id = $DB->Escape($_GET['id']);
                $customer = 'payer = ' . $DB->Escape($_GET['id']) . ' and status > 0 ORDER BY date DESC';
                $DB2= new database;
                $DB2->Select('payment','SUM(amount) as amnt', 'status > 0 and payer = '.$id);
                $totalAmount = $DB2->Select_Result[0]['amnt'];
            } else {
                $customer = "status > 0 ORDER BY date DESC ";
                $DB2= new database;
                $DB2->Select('payment','SUM(amount) as amnt', 'status > 0');
                $totalAmount = $DB2->Select_Result[0]['amnt'];
            }
            $DB->Select('payment', '*', $customer . ' ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $i = 0;
            while ($i < $num) {
                ?>
                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['id']; ?>">
                    <td><?php echo findPayment($DB->Select_Result[$i]['type']); ?></td>
                    <th><?php echo ($DB->Select_Result[$i]['invoice_id'] != 0) ? resolveInvoice($DB->Select_Result[$i]['invoice_id']) : resolveCus($DB->Select_Result[$i]['payer']); ?></th>
                    <td class="hidden-xs"><?php echo fdate($DB->Select_Result[$i]['date']); ?></td>
                    <td><?php echo findStatus($DB->Select_Result[$i]['status']); ?></td>
                    <td><?php echo number_format($DB->Select_Result[$i]['amount']); ?> ریال</td>
                    <td class="hidden-xs"><?php echo findBank($DB->Select_Result[$i]['bank']); ?></td>
                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['resnum']; ?></td>
                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['refnum']; ?></td>
                    <td><?php if ($DB->Select_Result[$i]['invoice_id'] != 0) { ?>
                            <a href="viewinvoiceord.php?id=<?php echo $DB->Select_Result[$i]['invoice_id']; ?>"
                               class="btn btn-success btn-xs">مشاهده</a>
                        <?php } else if ($DB->Select_Result[$i]['payer'] != 0) { ?>
                            <a href="edituser.php?id=<?php echo $DB->Select_Result[$i]['payer']; ?>"
                               class="btn btn-success btn-xs">مشاهده</a>
                        <?php } ?>
                        <?php if ($DB->Select_Result[$i]['type'] == 2 && $DB->Select_Result[$i]['status'] == 3) { ?>
                            <a href="javascript:savePayment(<?php echo $DB->Select_Result[$i]['id']; ?>,<?php echo $DB->Select_Result[$i]['payer']; ?>)"
                               class="btn btn-info btn-xs">ثبت</a> <a
                                href="javascript:delPayment(<?php echo $DB->Select_Result[$i]['id']; ?>)"
                                class="btn btn-danger btn-xs">عدم تائید</a>
                        <?php } ?></td>
                </tr>
                <?php
                $i++;
            } ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php
                $p = isset($_GET['p']) ? $_GET['p'] : 1;
                $fdot = $ldot = 0;
                $pages = ceil($totalRows / $_SESSION['resultperpage']);
                if ($p > 1) {
                    $href = $p - 1;
                    echo '<li><a href="?id=' . $id . '&p=' . $href . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">قبلی</span></a></li>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if ($pages < 6 || $i == $pages || $i == 1 || ($i > $p - 3 && $i < $p + 3)) {
                        $act = $i == $p ? 'class="active"' : ' ';
                        echo '<li ' . $act . '><a href="?id=' . $id . '&p=' . $i . '">' . $i . '</a></li>';
                    } else {
                        if ($fdot == 0) {
                            echo '<li class="disabled"><a >...</a></li>';
                            $fdot = 1;
                        }
                        if ($fdot == 1 && $i > $p && $ldot == 0 && $p >= 5) {
                            echo '<li  class="disabled"><a >...</a></li>';
                            $ldot = 1;
                        }
                    }
                }
                if ($p < $pages) {
                    $href = $p + 1;
                    echo '<li><a href="?id=' . $id . '&p=' . $href . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">بعدی</span></a></li>';
                }
                ?>
            </ul>
        </nav>
        <div class="actions"><span class="totalRow"> جمع کل پرداختی ها: </span><span
                class="totalRow"><?php echo number_format($totalAmount); ?> ریال</span></div>
    </div>
</div>
</div>
</div>
<?php
include("../footer.php");
?>
</body></html>