<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WhatsApp QR Code</title>
</head>
<body>
<h1>Escaneie o QR Code para autenticar no WhatsApp</h1>

<div id="qrcode"></div>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

<script>
  var qrCodeData = "{{ $qrCode }}"; // Passando a string base64 do backend
  if (qrCodeData) {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
      text: qrCodeData, // Use o texto ou a string base64 aqui
      width: 128,
      height: 128
    });
  } else {
    document.getElementById("qrcode").innerHTML = "QR Code não disponível.";
  }
</script>

</body>
</html>
