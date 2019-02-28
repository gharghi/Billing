<?php
require("header.php");//checks session and login
$page = 'p8';
include "top.php";//includes html top and menu
?>
<script language="javascript">
    function deltrans(id) {
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
        $.post("deltrans.php", {id: ID}, function (data) {
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
<?php
$DB = new database;
$id = $DB->Escape($_GET['id']);
?>
<div class="page-header">
         <h1 >ليست پرداختهاي <a href="editaccount.php?id=<?php
            echo  $id; ?>" class="titleA">
                <?php echo resolveAccount($_GET['id']); ?></a></h1 >
</div>
<div class="row">
    <div class="actions" style="margin-left:20px;">
        <input type="button" onClick="document.location.href='addcache.php?id=<?php echo $id; ?>';"
               value="افزودن تراكنش" class="btn btn-primary">
    </div>
    <div class="col-lg-12">
        <div class="alert alert-danger" id="error2" style="display:none;"> خطایی در ارسال پارامتر ها به وجود آمده است!
            دوباره امتحان کنید .
        </div>
        <div class="alert alert-success" id="error1" style="display:none;"> تراكنش با موفقيت حذف گرديد .</div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>نوع تراكنش</th>
                <th class="hidden-xs">تاريخ</th>
                <th>مبلغ</th>
                <th>بانك</th>
                <th class="hidden-xs">توضيحات</th>
                <th>مانده حساب</th>
                <th>دستورها</th>
            </tr>
            </thead>
            <tbody>
            <?php
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
            $DB->Select('cache', '*', 'account_id = ' . $id, 'date DESC' . ' ' . $pagin);
            $totalRows = $DB->TotalRows;
            $num = $DB->Select_Rows;
            $i = 0;
            $totalAmount = getBalance($id);
            $totalSalary = 0;
            while ($i < $num) {
                $amount = $DB->Select_Result[$i]['amount'];
                ?>
                <tr id="<?php echo 'a' . $DB->Select_Result[$i]['id']; ?>" <?php echo $DB->Select_Result[$i]['type'] == 2 ? "class='green'" : " ";
                echo $DB->Select_Result[$i]['type'] == 1 ? "class='red'" : " "; ?>>
                    <td><?php echo findtrans($DB->Select_Result[$i]['type']); ?></td>
                    <td class="hidden-xs"><?php echo fdate($DB->Select_Result[$i]['reg_date']); ?></td>
                    <td><?php echo number_format($DB->Select_Result[$i]['amount']); ?> ریال</td>
                    <td><?php echo $DB->Select_Result[$i]['bank']; ?></td>
                    <td class="hidden-xs"><?php echo $DB->Select_Result[$i]['descr']; ?></td>
                    <td><?php echo number_format($totalAmount); ?> ریال</td>
                    <td><a href="javascript:deltrans('<?php echo $DB->Select_Result[$i]['id']; ?>')"
                           class="btn btn-danger btn-xs" title="حذف تراكنش">حذف</a></td>
                </tr>
                <?php
                if ($DB->Select_Result[$i]['type'] == 2) {
                    $totalAmount -= $amount;
                } else if ($DB->Select_Result[$i]['type'] == 1) {
                    $totalAmount += $amount;
                }
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
        <div class="actions"><span class="totalRow"> مانده حساب: </span><span
                class="totalRow"><?php echo number_format(getBalance($id)); ?> ریال</span> <br/>
            <span class="totalRow"> جمع حقوق: &nbsp;&nbsp;&nbsp; </span><span
                class="totalRow"><?php echo number_format(getSalary($id)); ?> ریال</span>
        </div>
    </div>
</div>
</div>
</div>
<?php
include("../footer.php");
?>
</body></html>