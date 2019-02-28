<?php
require("header.php");//checks session and login
$page = 'p2';
include "top.php";//includes html top and menu
?>

<div class="page-header">

    <h1>لیست پرداختها</h1>

</div>

<div class="row">

    <div class="col-lg-12">

        <div class="alert alert-danger" id="error2" style="display:none;">

            خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .

        </div>

        <table class="table table-striped">

            <thead>

            <tr>

                <th>نوع پرداخت</th>

                <th class="hidden-xs">شماره فاکتور</th>

                <th class="hidden-xs">تاریخ پرداخت</th>

                <th>وضعیت</th>

                <th>مبلغ</th>

                <th class="hidden-xs">درگاه پرداخت</th>

                <th>شماره پیگیری</th>

                <th class="hidden-xs">رسید دیجیتالی</th>

                <th class="hidden-xs">دستورها</th>

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
            $DB->Select('payment', '*', 'payer = ' . $_SESSION['userid'] . ' and status > 0  order by date DESC ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $i = 0;
            $totalAmount = 0;
            while ($i < $num) {
                $amount = ($DB->Select_Result[$i]['amount']);
                if ($DB->Select_Result[$i]['status'] == 1) {
                    $totalAmount += $amount;
                }
                ?>

                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['id']; ?>">

                    <th><?php echo findPayment($DB->Select_Result[$i]['type']); ?></th>

                    <th class="hidden-xs"><?php echo ($DB->Select_Result[$i]['invoice_id'] != 0) ? $DB->Select_Result[$i]['invoice_id'] : ' '; ?></th>

                    <td class="hidden-xs"><?php echo infdate($DB->Select_Result[$i]['date']); ?></td>

                    <td><?php echo findStatus($DB->Select_Result[$i]['status']); ?></td>

                    <td><?php echo number_format($DB->Select_Result[$i]['amount']); ?> ریال</td>

                    <td class="hidden-xs"><?php echo($DB->Select_Result[$i]['type'] != 2 ? findBank($DB->Select_Result[$i]['bank']) : $DB->Select_Result[$i]['bank']); ?></td>

                    <td><?php echo $DB->Select_Result[$i]['resnum']; ?></td>

                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['refnum']; ?></td>

                    <td class="hidden-xs"><?php if ($DB->Select_Result[$i]['invoice_id'] != 0) { ?><a
                            href="invoiceview.php?id=<?php echo $DB->Select_Result[$i]['invoice_id']; ?>"
                            class="btn btn-xs btn-info">مشاهده</a><?php } ?></td>

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
                    echo '<li><a href="?p=' . $href . '"><span aria-hidden="true">&laquo;</span><span class="sr-only">قبلی</span></a></li>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if ($pages < 6 || $i == $pages || $i == 1 || ($i > $p - 3 && $i < $p + 3)) {
                        $act = $i == $p ? 'class="active"' : ' ';
                        echo '<li ' . $act . '><a href="?p=' . $i . '">' . $i . '</a></li>';
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
                    echo '<li><a href="?p=' . $href . '"><span aria-hidden="true">&raquo;</span><span class="sr-only">بعدی</span></a></li>';
                }
                ?>

            </ul>

        </nav>

        <div class="actions"><span class="totalRow"> جمع کل پرداختی ها: </span><span
                class="totalRow"><?php echo number_format(totalTranscation()); ?> ریال</span>

        </div>

    </div>

</div>

</div>

</div>

<?php
include("footer.php");
?>

</body></html>