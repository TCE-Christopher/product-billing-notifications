<?php // INSTRUCTIONS: put code above <div class="header"> ?>


<?php
//banner background color
$ccBoxColor = "#eee";

//banner text color
$ccTextColor = "#3ebcb0";

//banner "click here" button color
$bannerBtnColor = "#7aabc2";

//banner "click here" button text color
$BtnTextColor = "#f16f48";

$time = $_SERVER['REQUEST_TIME'];
$timeout_duration = 1800;

if (isset($_SESSION['LAST_ACTIVITY']) &&
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = $time;

if($_GET['TCE'] == 1) {
        echo $_SESSION['LAST_ACTIVITY'];
}

$ccGetBillingId = mysql($w['database'], "SELECT
        `value`
    FROM
        `users_meta`
    WHERE
         `key` = 'clientID'
    AND
        `database_id` = '" . $_COOKIE[userid] . "'
");
$billingUserId = mysql_fetch_assoc($ccGetBillingId);

$billingDbConnect = mysql_connect('localhost','newy6884_billing_dev', '0vsZF4lKJCs5KljdqnJpTupqK46YGx');
mysql_select_db('newy6884_billing');

$billingUnpaidId = mysql_query("SELECT
        *
    FROM
        `tblinvoices`
    WHERE
        userid = '" . $billingUserId['value'] . "'");

$unpaidFlag = 0;
while ($ccRow = mysql_fetch_assoc($billingUnpaidId)) {
    if($ccRow['status'] == 'Unpaid') {
        $unpaidFlag = 1;
        break;
    }
}
if ($unpaidFlag == 1 && $_SESSION['unpaid_banner_status'] == '') { ?>
    <style media="screen">
        #unpaid-invoice {
            display: inline-block;
            width: 100%;
            background-color: <?php echo $ccBoxColor; ?>;
            padding: 10px;
        }
        .close-x {
            float: right;
            font-size: 19px;
            color: #7aabc5;
        }
        .uib-text {
            display: inline-block;
            width: calc(100% - 30px);
        }

        #unpaid-invoice > span > a {
            color: <?php echo $ccTextColor; ?>;
        }
        .upb-color {
            color: <?php echo $BtnTextColor; ?>;
            background-color: <?php echo $bannerBtnColor; ?>;
            border-color: <?php echo $bannerBtnColor; ?>;
        }
        @media(max-width:768px) {
            #unpaid-invoice {
                position: fixed;
                z-index: 99;
                margin-top: 50px;
                border-bottom: 1px solid #d8d8d8;
            }
            body .header {
                padding-top: 20px;
            }
        }
    </style>
    <div id="unpaid-invoice" class="xs-text-center">
        <span class="bold">
            <a href="/account/billing" class="uib-text">
                You currently have an unpaid invoice on your account. Please <object><a href="/account/billing" class="btn btn-primary">click here</a></object> to complete your payment.
            </a>
            <a href="#" id="close-unpaid-invoice-banner">
                <i class="fa fa-times close-x" aria-hidden="true"></i>
            </a>
        </span>
    </div>
<?php
}
?>
