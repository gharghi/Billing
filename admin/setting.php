<?php
require("header.php");//checks session and login
$page = 'p5';
//here we do our php actions
if (isset($_POST['sub'])) {
    getPost();
    $DB2 = new database;
    $set['smsuser'] = $smsuser;
    $set['smspass'] = $smspass;
    $set['smsurl'] = isset($_POST['smsurl']) ? $smsurl : "http://sangchin.net/post/send.asmx?wsdl";
    $set['smsnumber'] = $smsnumber;
    $set['sendermail'] = $sendermail;
    $set['sendermailname'] = $sendermailname;
    $set['mailtitle'] = $mailtitle;
    $set['mailtext'] = $mailtext;
    $set['payment_text'] = $payment_text;
    $set['mellat_terminalId'] = $mellat_terminalId;
    $set['mellat_userName'] = $mellat_userName;
    $set['mellat_userPassword'] = $mellat_userPassword;
    $set['parsian_PIN'] = $parsian_PIN;
    if (isset($_POST['activepasargad'])) $set['activepasargad'] = $activepasargad; else $set['activepasargad'] = 0;
    if (isset($_POST['activemellat'])) $set['activemellat'] = $activemellat; else $set['activemellat'] = 0;
    if (isset($_POST['activeparsian'])) $set['activeparsian'] = $activeparsian; else $set['activeparsian'] = 0;
    $set['pasargad_merchant'] = $pasargad_merchant;
    $set['pasargad_terminal'] = $pasargad_terminal;
    $set['pasargad_key'] = $pasargad_key;
    $set['site_URL'] = $site_URL;
    $set['postaladdress'] = $postaladdress;
    $set['indescrow1'] = $indescrow1;
    $set['indescrow2'] = $indescrow2;
    $set['indescrow3'] = $indescrow3;
    $set['inname'] = $inname;
    $set['invat_num'] = $invat_num;
    $set['inregnum'] = $inregnum;
    $set['instateaddr'] = $instateaddr;
    $set['inaddr'] = $inaddr;
    $set['inpostcode'] = $inpostcode;
    $set['incity'] = $incity;
    $set['intel'] = $intel;
    $set['infax'] = $infax;
    $edit = $DB2->Update('setting', $set, ' id = 1');
    if ($edit) {
        $status = '<div class="alert alert-success">
			تنظیمات سایت با موفقیت بروز شد.
			</div>';
    } else {
        $status = '<div class="alert alert-danger">
			خطایی در ارسال پارامتر ها به وجود آمده است! دوباره امتحان کنید .
			</div>';
    }
}
$DB = new database;
$DB->Select('setting', '*', 'id = 1');
include "top.php";//includes html top and menu
?>

<div class="page-header">
    <h1>تنظیمات سیستم</h1>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info"> برای فعال سازی سامانه پیامک می بایست از سایت sangchin.ir نام کاربری و رمز عبور
            داشته باشید


            از نوشتن متن طولانی برای پیامک ها خود داری کنید


            برای غیر فعال سازی و یا تغییر سیستم پیامک به <a href="help.php">راهنمای سیستم</a> مراجعه کنید.
        </div>
        <?php if (isset($status)) echo $status; ?>
        <form method="POST" role="form" class="form-horizontal">
            <input type="hidden" name="go" value="update">
            <fieldset>
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#sms" aria-controls="sms" role="tab"
                                                                  data-toggle="tab">پيامك</a></li>
                        <li role="presentation"><a href="#email" aria-controls="email" role="tab" data-toggle="tab">ايميل</a>
                        </li>
                        <li role="presentation"><a href="#gateway" aria-controls="gateway" role="tab" data-toggle="tab">درگاه
                                پرداخت</a></li>
                        <li role="presentation"><a href="#cargo" aria-controls="cargo" role="tab" data-toggle="tab">ارسال
                                بار</a></li>
                        <li role="presentation"><a href="#invoice" aria-controls="invoice" role="tab" data-toggle="tab">صدور
                                فاكتور</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="sms">
                            <div class="pad10">&nbsp;</div>
                            <h2>تنظیمات پیامک</h2>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="smsnumber">شماره پیامک</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['smsnumber']; ?>" type="text"
                                           size="30" name="smsnumber" id="smsnumber" class="form-control"
                                           placeholder="200000003031" dir="ltr">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="smsuser">نام کاربری</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['smsuser']; ?>" type="text" size="30"
                                           name="smsuser" id="smsuser" class="form-control" placeholder="username"
                                           dir="ltr">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="smspass">رمز عبور</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['smspass']; ?>" type="text" size="30"
                                           name="smspass" id="smspass" class="form-control" placeholder="Password"
                                           dir="ltr">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="smsurl">آدرس سرور پيامك</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['smsurl']; ?>" type="text" size="30"
                                           name="smsurl" id="smsurl" class="form-control"
                                           placeholder="در صورت عدم اطلاع خالي بگذاريد">
                                </div>
                            </div>
                            <!-- /clearfix -->

                        </div>
                        <div role="tabpanel" class="tab-pane" id="email">
                            <div class="pad10">&nbsp;</div>
                            <h2>تنظیمات ایمیل</h2>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="sendermail">ایمیل ارسال کننده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['sendermail']; ?>" type="text"
                                           size="30" name="sendermail" id="sendermail" class="form-control"
                                           placeholder="mail@domain.com" dir="ltr">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="sendermailname">نام ارسال کننده ایمیل</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['sendermailname']; ?>" type="text"
                                           size="30" name="sendermailname" id="sendermailname" class="form-control"
                                           placeholder="حسابداری شرکت">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="mailtitle">موضوع ایمیل</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['mailtitle']; ?>" type="text"
                                           size="30" name="mailtitle" id="mailtitle" class="form-control"
                                           placeholder="صدور فاکتور جدید">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="mailtext">متن ایمیل</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="mailtext" id="mailtext"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['mailtext']); ?></textarea>
                                    <span class="help-block"> متن ایمیل که میخواهید ارسال شود را اینجا وارد کنید </span>
                                </div>
                            </div>
                            <!-- /clearfix -->
                        </div>
                        <div role="tabpanel" class="tab-pane" id="gateway">
                            <div class="pad10">&nbsp;</div>
                            <h2>تنظیمات درگاه پرداخت</h2>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="site_URL">آدرس کامل وبسایت</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['site_URL']; ?>" type="text"
                                           size="30" name="site_URL" id="site_URL" class="form-control"
                                           placeholder="http://karaneshan.ir">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="activeparsian">پارسیان</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="1" type="checkbox" size="30" name="activeparsian"
                                           id="activeparsian" <?php if ($DB->Select_Result[0]['activeparsian'] == 1) echo "checked"; ?> >
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="parsian_PIN">پین کد بانک پارسیان</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['parsian_PIN']; ?>" type="text"
                                           size="30" name="parsian_PIN" id="parsian_PIN" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="activepasargad">پاسارگاد</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="1" type="checkbox" size="30" name="activepasargad"
                                           id="activepasargad" <?php if ($DB->Select_Result[0]['activepasargad'] == 1) echo "checked"; ?> >
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="pasargad_merchant">شماره مشتری پاسارگاد</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['pasargad_merchant']; ?>" type="text"
                                           size="30" name="pasargad_merchant" id="pasargad_merchant"
                                           class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="pasargad_terminal">شماره ترمینال پاسارگاد</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['pasargad_terminal']; ?>" type="text"
                                           size="30" name="pasargad_terminal" id="pasargad_terminal"
                                           class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="pasargad_key">کلید رمز پاسارگاد</label>
                                </div>
                                <div class="col-xs-10 inputsize">
            <textarea name="pasargad_key" id="pasargad_key" class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['pasargad_key']); ?></textarea>
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="activemellat">ملت</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="1" type="checkbox" size="30" name="activemellat"
                                           id="activemellat" <?php if ($DB->Select_Result[0]['activemellat'] == 1) echo "checked"; ?> >
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="mellat_terminalId">شماره ترمینال بانک ملت</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['mellat_terminalId']; ?>" type="text"
                                           size="30" name="mellat_terminalId" id="mellat_terminalId"
                                           class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="mellat_userName">نام کاربری بانک ملت</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['mellat_userName']; ?>" type="text"
                                           size="30" name="mellat_userName" id="mellat_userName" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="mellat_userPassword">رمز عبور بانک ملت</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['mellat_userPassword']; ?>"
                                           type="text" size="30" name="mellat_userPassword" id="mellat_userPassword"
                                           class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="payment_text">متن پرداخت موفق</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="payment_text" id="payment_text"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['payment_text']); ?></textarea>
                                    <span class="help-block"> متن بعد از پرداخت موفق را اینجا وارد کنید. </span></div>
                            </div>
                            <!-- /clearfix -->
                        </div>
                        <div role="tabpanel" class="tab-pane " id="cargo">
                            <div class="pad10">&nbsp;</div>
                            <h2>تنظیمات ارسال بار</h2>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="postaladdress">آدرس فرستنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="postaladdress" id="postaladdress"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['postaladdress']); ?></textarea>
                                    <span class="help-block"> آدرس, تلفن و نام فرستنده را اینجا درج کنید. </span></div>
                            </div>
                            <!-- /clearfix -->
                        </div>
                        <div role="tabpanel" class="tab-pane" id="invoice">
                            <div class="pad10">&nbsp;</div>
                            <h2>تنظیمات صدور فاکتور</h2>

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="inname">نام فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['inname']; ?>" type="text" size="30"
                                           name="inname" id="inname" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="invat_num">کد اقتصادی فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['invat_num']; ?>" type="text"
                                           size="30" name="invat_num" id="invat_num" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="inregnum">شماره ثبت/ملی</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['inregnum']; ?>" type="text"
                                           size="30" name="inregnum" id="inregnum" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="inpostcode">کد پستی فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['inpostcode']; ?>" type="text"
                                           size="30" name="inpostcode" id="inpostcode" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="incity">شهر فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['incity']; ?>" type="text" size="30"
                                           name="incity" id="incity" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="intel">تلفن فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['intel']; ?>" type="text" size="30"
                                           name="intel" id="intel" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="infax">فکس فروشنده</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <input value="<?php echo $DB->Select_Result[0]['infax']; ?>" type="text" size="30"
                                           name="infax" id="infax" class="form-control">
                                </div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="indescrow2">توضیحات خط 1</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="indescrow1" id="indescrow1"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['indescrow1']); ?></textarea>
                                    <span class="help-block"> </span></div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="indescrow2">توضیحات خط 2</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="indescrow2" id="indescrow2"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['indescrow2']); ?></textarea>
                                    <span class="help-block"> </span></div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="indescrow3">توضیحات خط 3</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="indescrow3" id="indescrow3"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['indescrow3']); ?></textarea>
                                    <span class="help-block"> </span></div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="instateaddr">آدرس استان</label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="instateaddr" id="instateaddr"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['instateaddr']); ?></textarea>
                                    <span class="help-block"> </span></div>
                            </div>
                            <!-- /clearfix -->

                            <div class="form-group">
                                <div class="col-xs-2">
                                    <label for="inaddr">آدرس </label>
                                </div>
                                <div class="col-xs-10 inputsize">
                                    <textarea rows="3" name="inaddr" id="inaddr"
                                              class="form-control"><?php echo nlToEnter($DB->Select_Result[0]['inaddr']); ?></textarea>
                                    <span class="help-block"> </span></div>
                            </div>
                            <!-- /clearfix -->
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="ذخیره" name="sub" class="btn btn-primary">
                        &nbsp;
                        <button class="btn btn-default" type="button" onClick="document.location.href='dashboard.php'">
                            لغو
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
</div>
</div>
<?php
include("../footer.php");
?>
</body></html>