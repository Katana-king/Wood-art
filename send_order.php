<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = htmlspecialchars($_POST['fullName']);
  $email = htmlspecialchars($_POST['email']);
  $phone = htmlspecialchars($_POST['phone']); // Get phone number
  $wilaya = htmlspecialchars($_POST['wilaya']);
  $orderDetails = nl2br(htmlspecialchars($_POST['orderDetails']));
  $total = htmlspecialchars($_POST['total']);

  $mail = new PHPMailer(true);

  try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'brahimiahmed995@gmail.com';
    $mail->Password = 'ijmc ksfz olyn okbc';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinataires
    $mail->setFrom($email, $name);
    $mail->addAddress('brahimiahmed995@gmail.com', 'WoodArt');

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = 'Nouvelle commande WoodArt';
    $mail->Body    = "
      <h2>Nouvelle commande</h2>
      <p><strong>Nom:</strong> $name</p>
      <p><strong>Email:</strong> $email</p>
      <p><strong>Téléphone:</strong> $phone</p> <!-- Add phone to email -->
      <p><strong>Wilaya:</strong> $wilaya</p>
      <p><strong>Détails de la commande:</strong><br>$orderDetails</p>
      <p><strong>Total:</strong> $total</p>
    ";

    $mail->send();
    echo 'Commande envoyée.';
  } catch (Exception $e) {
    http_response_code(500);
    echo "Erreur d'envoi : {$mail->ErrorInfo}";
  }
}
?>