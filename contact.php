<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';  // PHPMailer



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize input
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST["message"] ?? ''));

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit;
    }

    // Create a PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'brahimiahmed995@gmail.com';  // Your Gmail address
        $mail->Password = 'ijmc ksfz olyn okbc';  // Your Gmail app password (if 2FA enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom($email, $name);  // Sender's email and name
        $mail->addAddress('brahimiahmed995@gmail.com');  // Replace with your email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Nouveau message de Wood Art';
        $mail->Body    = "Nom: $name<br>Email: $email<br><br>Message:<br>$message";

        // Send email
        if ($mail->send()) {
            echo "Merci pour votre message. Nous vous répondrons bientôt.";
        } else {
            echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
        }
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode de requête non valide.";
}
?>
