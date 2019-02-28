<title>مدیریت صورت حساب کارانشان</title>

<meta name="description" content="سیستم مدیریت صورتحساب کارانشان">

<meta name="author" content="کارانشان چارسوی فناوری">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->

<!--[if lt IE 9]>

<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

<![endif]-->

<!-- Le styles -->

<link href="../css/bootstrap.rtl.min.css" rel="stylesheet">

<link href="../css/bootstrap-theme.min.css" rel="stylesheet">

<link href="../css/style.css" rel="stylesheet">

<script type="text/javascript" src="../js/jquery.js"></script>

<script type="text/javascript" src="../js/bootstrap.rtl.min.js"></script>

<script type="text/javascript" src="../js/jquery.fancybox.pack.js"></script>

<script type="text/javascript" src="../js/jquery.fancybox.js"></script>
<script type="text/javascript" src="../js/typeMoney.js"></script>
<script type="text/javascript" src="../js/js-persian-cal.min.js"></script>

<link rel="stylesheet" href="../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen"/>

<link rel="stylesheet" href="../css/jquery.fancybox.css" type="text/css" media="screen"/>

<link rel="stylesheet" href="../css/js-persian-cal.css">


<script language="javascript">

    $(document).ready(function () {

        $("#<?php echo $page; ?>").addClass('active');
    });

</script>

</head>
<body>

<nav class="navbar navbar-default  navbar-fixed-top" role="navigation">

    <div class="container">

        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle navigation</span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>

            <a class="navbar-brand" href="dashboard.php">پیشخوان</a></div>

        <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav">

                <li id="p1"><a href="listuser.php">مشتری ها</a></li>

                <li id="p2"><a href="listinvoiceord.php"> فاکتور عادی</a></li>

                <li id="p4"><a href="listinvoiceper.php"> فاکتور دوره ای</a></li>

                <li id="p7"><a href="transaction.php">تراکنش ها</a></li>
                <li id="p8"><a href="listaccount.php">حسابها</a></li>
                <li id="p3"><a href="listnotif.php">قانون ها</a></li>

                <li id="p5"><a href="setting.php">تنظیمات</a></li>

                <li id="p6"><a href="changepassword.php">تغییر رمز</a></li>

                <li><a href="logout.php">
                        <blink>خروج!</blink>
                    </a></li>

            </ul>

        </div>

        <!--/.nav-collapse -->

    </div>

</nav>

<div class="container">

    <div class="jumbotron">