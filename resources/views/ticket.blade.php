<!DOCTYPE html>
<html>
<head>
  <title>Ticket</title>
  <style>
    /* @page {
      size: A6;
      orientation: portrait;
      margin: 0;
    } */

 
    body {
      font-family: Arial, sans-serif;
      background-color: #ffff;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 400px;
    }

    .ticket {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 10px;
      width: 100%;
      height: 70%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .ticket h2 {
      color: #333;
      font-size: 18px;
      margin-top: 0;
      text-align: center;
    }

    .ticket .event {
      margin-bottom: 10px;
      text-align: center;
    }

    .ticket .event p {
      color: #333;
      font-size: 12px;
      margin: 5px 0;
    }

    .ticket .details {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }

    .ticket .details p {
      color: #333;
      font-size: 12px;
      margin: 5px 0;
    }

    .ticket .barcode {
      text-align: center;
    }
    .qr-code {
      text-align: center;
      padding-left: 35%;
    }

  </style>
</head>
<body>
  <div class="ticket">
    <h2>Ticket</h2>
    <div class="event">
      <p>Event Name: {{$event}}</p>
      <p>Date: {{$day}}</p>
    </div>
    <div class="details">
      <p>Issued to: {{$name}}</p>
      <p>Admits: {{$quantity}}</p>
    </div>
    <div class="barcode">
      <p>Ticket ID: {{$id}}</p>
      <div class="qr-code">{!! DNS2D::getBarcodeHTML('www.google.com', 'QRCODE') !!}</div>
      <!-- Add your barcode image or code here -->
      <!-- <img src="barcode.png" alt="Barcode"> -->
    </div>
  </div>
</body>
</html>
