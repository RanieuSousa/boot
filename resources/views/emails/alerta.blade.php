<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $titulo }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }
    .container {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #333;
      font-size: 18px;
      margin-bottom: 20px;
    }
    p {
      font-size: 12px;
      line-height: 1.6;
      color: #555;
    }
    .highlight {
      font-weight: bold;
      color: #007bff;
    }
    .details {
      margin-top: 20px;
      padding: 10px;
      background-color: #f1f1f1;
      border-radius: 4px;
    }
    .details p {
      margin: 5px 0;
    }
  </style>
</head>
<body>

<div class="container">

  <p class="details">{!! $mensagem !!}</p>

</div>

</body>
</html>
