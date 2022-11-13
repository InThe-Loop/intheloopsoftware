<?php
date_default_timezone_set('Africa/Johannesburg');
if(!is_csrf_valid() || empty($_REQUEST)) {
    echo '<center style="margin-top: 15%; color: #ff0000;"><h1>Error!</h1>';
    echo '<h3>Sorry, something went wrong. Please try again! Redirecting...</h3></center>';
    echo '<meta http-equiv="refresh" content="5;url=/" />';
    exit();
}
require_once './vendor/autoload.php';
require_once './app/Controllers/MailController.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use app\Controllers\MailController;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);

$quote_number = 0000000;
for ($i = 0; $i < 6; $i++) {
    $quote_number .= mt_rand(0,9);
}

$issue_date = date("D dS \o\\f F Y \a\\t H:iA");
$valid_date = date("D dS \o\\f F Y \a\\t H:iA", strtotime(date("Y-m-d") . " + 15 days"));

$image = './public/img/in_the_loop.png';
$imageData = base64_encode(file_get_contents($image));
$logo_src = 'data:' . mime_content_type($image) . ';base64,' . $imageData;

$desc = explode(".", trim($_REQUEST['requirements']));
$description = '';
if (is_array($desc)) {
    foreach($desc as $item) {
        if (strlen($item) > 0) {
            $description .= ucfirst("<li>{$item}</li>");
        }
    }
}
else {
    $description = $desc;
}
if ($_REQUEST['type'] == "starter") {
    $unit_price = '120.00';
    $hours = '42.00';
    $pay_now = '2,500.00';
    $final_pay = '2,500.00';
    $total = '5,000.00';
    $monthly_premium = '100.00';
    $std_features = '
        <li>3GB space</li>
        <li>Full website development (5 web pages)</li>
        <li>25 domain emails available</li>
        <li>A custom business logo</li>
        <li>Free domain name registration (.com not included)</li>
        <li>A contact us page with a email form linked to your own email</li>
        <li><strong>NB:</strong> Any new changes (additions or removals) after go-live will incur a cost of R100.00 per hour to implement</li>
    ';
}
elseif ($_REQUEST['type'] == "business") {
    $unit_price = '120.00';
    $hours = '84.00';
    $pay_now = '5,000.00';
    $final_pay = '5,000.00';
    $total = '10,000.00';
    $monthly_premium = '140.00';
    $std_features = '
        <li>5GB space</li>
        <li>Full website development (10 web pages)</li>
        <li>50 domain emails available</li>
        <li>A custom business logo</li>
        <li>Authentication and login system (basic auth)</li>
        <li>Free domain name registration (.com not included)</li>
        <li>A contact us page with a email form linked to your own email</li>
        <li><strong>NB:</strong> Any new changes (additions or removals) after go-live will incur a cost of R100.00 per hour to implement</li>
    ';
}
elseif ($_REQUEST['type'] == "elite") {
    $unit_price = '120.00';
    $hours = '168.00';
    $pay_now = '10,000.00';
    $final_pay = '10,000.00';
    $total = '20,000.00';
    $monthly_premium = '200.00';
    $std_features = '
        <li>10GB space</li>
        <li>Full website development (unlimited pages)</li>
        <li>100 domain emails available</li>
        <li>A custom business logo</li>
        <li>Shopping cart system</li>
        <li>Stock management admin panel</li>
        <li>Payment system (With custom integration with your bank)</li>
        <li>Authentication and login system (Facebook and/or Google login)</li>
        <li>Free domain name registration (.com not included)</li>
        <li>A contact us page with a email form linked to your own email</li>
        <li><strong>NB:</strong> Any new changes (additions or removals) after go-live will incur a cost of R100.00 per hour to implement</li>
    ';
}
?>

<!DOCTYPE html />
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">

		<title>InTheLoop Software Solutions&copy;</title>
		<meta content="InTheLoop Software Solutions" name="description" />
		
		<!-- Favicons -->
		<link href="./public/img/in_the_loop.png" rel="icon" />
		<link href="./public/img/in_the_loop.png" rel="apple-touch-icon" />
        
        <!-- Vendor CSS Files -->
		<link href="./public/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="./public/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
		<link href="./public/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
	</head>
<?php
$html = '
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />
<link href="./public/css/style.css" rel="stylesheet" />
<style type="text/css">
    h1, h2, h3, h4, h5, h6 {
        line-height: 1;
        font-weight: lighter;
    }
    table {
        font-size: 15px !important;
    }
    th.head-bg {
        color: #fff;
        background-color: #ff3333;
    }
    th {
        padding: 5px !important;
        font-weight: lighter;
        color: #fff !important;
    }
    .grey-bg {
        color: #fff;
        background-color: #d7d7d7;
    }
    .grey-line {
        height: 1px;
        border-bottom: solid 1px #d7d7d7;
    }
    .line {
        height: 1px;
    }
    .w-80 {
        width: 80%;
    }
    .px-2 {
        padding: 5px 10px !important;
    }
    .text-end {
        text-align: right !important;
    }
    .text-center {
        text-align: center !important;
    }
    .mt-5 {
        margin-top: 20px !important;
    }
    .mb-5 {
        margin-bottom: 20px !important;
    }
    .mb-0 {
        margin-bottom: 0 !important;
    }
    .m-0 {
        margin: 0 !important;
    }
</style>
<body>
    <table cellpadding="0" cellspacing="0" width="100%" class="table-responsive mt-3">
        <tbody>
            <tr>
                <td></td>
                <td colspan="4">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td class="col">
                                    <img alt="InTheLoop Logo" height="150" src="' . $logo_src . '" />
                                </td>
                                <td class="col text-end">
                                    <h2>Quote #</strong>: ITL-' . $quote_number . '</h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="col">
                                    <p>
                                        <strong>Trading Address:</strong> 199 Uys Rd, Rynfield, Benoni, 1501<br />
                                        <strong>Co. Registration:</strong> K2021905542<br />
                                        <strong>Contact:</strong> 068 103 7459<br />
                                        <strong>Email:</strong> services@intheloop.tech<br />
                                    </p>
                                </td>
                                <td class="col text-end">
                                    <p>
                                        <strong>Prepared For:</strong> ' . ucwords($_REQUEST['name']) . '<br />
                                        <strong>Contact:</strong> ' . $_REQUEST['phone']  . '<br />
                                        <strong>Email:</strong> ' . strtolower($_REQUEST['email'])  . '<br />
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" class="col">
                    <p class="mt-5">
                        <strong>Date Issued:</strong> ' . $issue_date . ' (SAST)<br />
                        <strong>Validity:</strong> This quotation is valid until ' . $valid_date . ' (SAST)
                    </p>
                </td>
                <td></th>
            </tr>
            <tr>
                <td></th>
                <td colspan="4"><h3 class="mt-5 mb-0">Package type: <strong>' . ucfirst($_REQUEST['type']) . '</strong></h3></td>
                <td></th>
            </tr>
            <tr>
                <th></th>
                <th class="head-bg col text-end">#</th>
                <th class="head-bg col">
                    Item description
                </th>
                <th class="head-bg col">
                    Total&nbsp;hours
                </th>
                <th class="head-bg col text-end">
                    Unit&nbsp;price
                </th>
                <th></th>
            </tr>
            <tr><td colspan="6">&nbsp;</tr>
            <tr>
                <td></td>
                <td class="col text-center">1</td>
                <td class="col w-80">
                    <p>Your requirements</p>
                    ' . $description  . '
                    <p>Standard features</p>
                    <ul>
                        ' . $std_features . '
                    </ul>
                </td>
                <td class="col px-2">' . $hours . ' hrs</td>
                <td class="col text-end px-2">R' . $unit_price . '</td>
                <td></td>
            </tr>
            <tr class="head-bg">
                <th></th>
                <th class="head-bg col">#</th>
                <th class="head-bg col">
                    Item description
                </th>
                <th class="head-bg col">
                    Total&nbsp;hours
                </th>
                <th class="head-bg col text-end">
                    Unit&nbsp;price
                </th>
                <th></th>
            </tr>
            <tr><td colspan="6" class="line"></tr>
            <tr>
                <td></td>
                <td colspan="2" class="col grey-line px-2">Due now (50%)</td>
                <td colspan="2" class="col grey-bg text-end px-2">
                    <h3 class="m-0">R' . $pay_now . '</h3>
                </td>
                <td></td>
            </tr>
            <tr><td colspan="6" class="line"></tr>
            <tr>
                <td></td>
                <td colspan="2" class="col px-2">Due before go-live (50%)</td>
                <td colspan="2" class="col grey-bg text-end px-2">
                    <h3 class="m-0">R' . $final_pay . '</h3>
                </td>
                <td></td>
            </tr>
            <tr><td colspan="6" class="line"></tr>
            <tr>
                <th></th>
                <td colspan="2" class="col grey-bg px-2">Total (100)%</th>
                <th colspan="2" class="col px-2 grey-bg text-end">
                    <h3 class="m-0">R' . $total . '</h3>
                </th>
                <th></th>
            </tr>
            <tr><td colspan="6" class="line"></tr>
            <tr>
                <td></td>
                <td colspan="2" class="col grey-line px-2">Monthly hosting fee</th>
                <td colspan="2" class="col px-2 grey-bg text-end">
                    <h3 class="m-0">R' . $monthly_premium . '</h3>
                </td>
                <td></td>
            </tr>
            <tr><td colspan="6" class="mt-5">&nbsp;</tr>
            <tr>
                <td></td>
                <td colspan="4" class="col">
                    <h4 class="mt-5">Terms and conditions</h4>
                    Acceptance of our terms of this quotation means that you agree with:
                    <ul>
                        <li>A 50% deposit before or on the grant of service</li>
                        <li>A final 50% deposit that will be due on or before system go live</li>
                    </ul>
                    <p class="mt-5 mb-5 text-center">THANK YOU FOR YOUR CO-OPERATION.</p>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>';

$filename = 'quote_' . $quote_number . '.pdf';
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

if (strtolower($_REQUEST['send-email']) === 'yes') {
    $file = $dompdf->output();
    file_put_contents($filename, $file);
    $_REQUEST['subject'] = 'Auto generated quotation: ' . ucfirst($_REQUEST['type']) . ' package';
    $_REQUEST['message'] = '<p>Auto generated quotation from InTheLoop Software Solutions.<p>';
    $emailer = new MailController();
    $response = $emailer->sendEmail($_REQUEST, $filename);
?>

<!-- JS Files -->
<script src="./public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./public/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#emailModal").modal("show");
        $(".close-modal").on("click", function() {
            $("#emailModal").modal("hide");
        });
    });
</script>
<?php
}
else {
    ob_end_clean();
    $dompdf->stream($filename);
}
?>
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="bfModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-dark" id="myModalLabel">InTheLoop Quotation&trade;</h4>
            </div>
            <div class="modal-body text-dark text-center">
                <h3>
<?php
    echo $response['text'];
    echo '<meta http-equiv="refresh" content="5;url=/" />';
?>
                </h3>
			</div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default text-dark close-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</html>
