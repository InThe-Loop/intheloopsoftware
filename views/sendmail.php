<?php

require_once "./app/Controllers/MailController.php";

use app\Controllers\MailController;

// Check for valid CSRF (Cross Site Request Forgery)
if(is_csrf_valid()) {
    $emailer = new MailController();
    $response = $emailer->sendEmail($_REQUEST);

    // Remove XSS (Cross Site Scripting)
    echo json_encode($response);
    exit();
}
$response = [
    'code' => 201,
    'status' => "Failed",
    'text' => "Could not send email due to an invalid CSRF code!",
];
echo json_encode($response);
exit();
