<?php
require("header.php");//checks session and login
$page = 'p8';
include "top.php";//includes html top and menu
?>
<script language="javascript">
    function deluser(id) {
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
            content: "<div>آیا از حذف اطمینان دارید؟</div><div class='actions'><input type='button' value='بلی'  onclick='del(" + id + ");' class='btn btn-primary'>&nbsp;<input type='button' value='خير' class='btn btn-default' onclick='$.fancybox.close();'></div>",
            helpers: {
                overlay: {
                    css: {'background': 'rgba(192, 196, 207, 0.8)'},
                    locked: false
                }
            }
        });
    }
    function del(ID) {
        $.post("delaccount.php", {id: ID}, function (data) {
            if (data == 0) {
                $.fancybox.close();
                $('#error2').fadeIn(600);
            }
            else if (data == 1) {
                $.fancybox.close();
                $('#a' + ID).hide(2000);
                $('#error1').fadeIn(600);
            }
            else if (data == 2) {
                $.fancybox.close();
                $('#error3').fadeIn(600);
            }
            else {
                $.fancybox.close();
                $('#error2').fadeOut(600);
            }
        });
    }
</script>
<div class="page-header">
    <h1>لیست حسابها ها</h1>
</div>
<div class="row">
    <div class="actions" style="margin-left:20px;">
        <input type="button" onClick="document.location.href='addaccount.php';" value="افزودن حساب"
               class="btn btn-primary">
    </div>
    <div class="col-lg-12">
        <div class="alert alert-danger" id="error2" style="display:none;"> خطایی در ارسال پارامتر ها به وجود آمده است!
            دوباره امتحان کنید .
        </div>
        <div class="alert alert-success" id="error1" style="display:none;"> حساب مورد نظر با موفقیت حذف شد .</div>
        <div class="alert alert-danger" id="error3" style="display:none;"> ابتدا میبایست تراكنش هاي انتصاب یافته به حساب
            را حذف نمائید.
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="hidden-xs">شناسه</th>
                <th>نام</th>
                <th>گروه</th>
                <th class="hidden-xs">جمع حقوق</th>
                <th class="hidden-xs">مانده حساب</th>
                <th class="hidden-xs">توضيحات</th>
                <th>عملیات مدیریت</th>
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
            $DB->Select('account', '*', 'display = 1 ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $tTrans = $tSalary = 0;
            $i = 0;
            while ($i < $num) {
                $tSalary += getSalary($DB->Select_Result[$i]['id']);
                $tTrans += getBalance($DB->Select_Result[$i]['id']);
                ?>
                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['id']; ?>">
                    <th class="hidden-xs"><?php echo $DB->Select_Result[$i]['id']; ?></th>
                    <td><?php echo $DB->Select_Result[$i]['name']; ?></td>
                    <td><?php echo findGrp2($DB->Select_Result[$i]['grp']); ?></td>
                    <td class="hidden-xs"><?php echo number_format(getSalary($DB->Select_Result[$i]['id'])); ?></td>
                    <td class="hidden-xs"><?php echo number_format(getBalance($DB->Select_Result[$i]['id'])); ?></td>
                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['descr']; ?> </td>

                    <td><a href="addcache.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>" title="افزودن تراكنش"><img
                                alt="افزودن برداشت" src="../images/add-invoice.png"></a>&nbsp; <a
                            href="editaccount.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>" title="ویرایش "><img
                                alt="ویرایش" src="../images/edit-invoice.png"></a>&nbsp; <a
                            href="accounttrans.php?id=<?php echo $DB->Select_Result[$i]['id']; ?>" title="تراکنشها"><img
                                alt="تراکنشها" src="../images/transactions.png"></a>&nbsp; <a
                            href="javascript:deluser('<?php echo $DB->Select_Result[$i]['id']; ?>')"
                            title="حذف حساب"><img alt="حذف" src="../images/delete.png"></a></td>
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
        <div class="actions"><span class="totalRow">موجودي بانك:</span><span class="totalRow"><?php
                echo $tTrans - $tSalary;
                ?> ریال</span>
        </div>
    </div>
</div>
</div>
<?php
include("../footer.php");
?>
</div>

<!-- /container -->

</body></html>