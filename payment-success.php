<?php
if(isset($_GET['oid']) && isset($_GET['rp_payment_id']) && isset($_GET['rp_signature'])){
    $order_id = $_GET['oid'];
    $payment_id = $_GET['rp_payment_id'];
    $signature = $_GET['rp_signature'];

    // Verify the payment using Razorpay's API (this can be done with the Razorpay SDK or manually)
    $razorpay_test_key = 'rzp_test_IeOPwe3oX6C2ad'; // Your test key
    $razorpay_test_secret_key = 'veDSMUvadOwmetFq74YiOHhA'; // Your test secret key

    $auth = base64_encode($razorpay_test_key . ":" . $razorpay_test_secret_key);
    $url = "https://api.razorpay.com/v1/payments/{$payment_id}/verify";

    // Initiate cURL to verify the payment
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic $auth"
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $payment_details = json_decode($response);

    // Check if payment verification was successful
    if($payment_details->status == 'captured'){
        echo "<h1>Payment Success</h1>";
        echo "<p>Order ID: " . $order_id . "</p>";
        echo "<p>Payment ID: " . $payment_id . "</p>";
        echo "<p>Amount: " . $payment_details->amount / 100 . " INR</p>";
        // Store payment details in your database here

    } else {
        echo "<h1>Payment Verification Failed</h1>";
        echo "<p>Order ID: " . $order_id . "</p>";
        echo "<p>Payment ID: " . $payment_id . "</p>";
    }
}
?>
