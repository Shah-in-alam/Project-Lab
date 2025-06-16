<?php
require_once __DIR__ . '/../../load_env.php';

loadEnv(__DIR__ . '/../.env');

$paymentLink = $_ENV['STRIPE_PAYMENT_LINK'] ?? '#';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Pay Now</title>
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      background: #1c1c2a;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 0 20px rgba(0,0,0,0.6);
    }
    .button {
      background: #635bff;
      color: white;
      padding: 15px 30px;
      font-size: 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .button:hover {
      background: #5146f0;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Pay $100 for your tattoo</h2>
    <button class="button" onclick="window.location.href='<?php echo $paymentLink; ?>'">Pay Now</button>
  </div>
</body>
</html>


