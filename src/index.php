<?php

require(__DIR__ . '/../vendor/autoload.php');

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load('../../.env');

$log = new Monolog\Logger('info');
$log->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/info.log', Monolog\Level::Info));

if ($_POST && $_POST["name"] && $_POST["email"] && $_POST["complaint"]) {
    $log->info('Recieved:', ['Name' => $_POST["name"], 'Email' => $_POST["email"], 'Complaint' => $_POST["complaint"]]);
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.strato.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'stuff@pjotrclaassen.nl';
        $mail->Password = $_ENV['MAILPASS'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

        $mail->setFrom('stuff@pjotrclaassen.nl', 'Pjotr Claassen');
        $mail->addAddress($_POST['email'], $_POST['name']);
        $mail->addCC('stuff@pjotrclaassen.nl');

        $mail->Subject = 'Uw klacht is in behandeling';
        $mail->Body = $_POST["name"] . ' - ' . $_POST["email"] . ' - ' . $_POST["complaint"];
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form method="post" style="display: flex; flex-direction: column;">
        <label for="nameInput">Name:</label>
        <input id="nameInput" name="name" type="text" placeholder="Name" required>
        <label for="mailInput">Name:</label>
        <input id="mailInput" name="email" type="email" placeholder="Email" required>
        <label for="complaintInput">Name:</label>
        <textarea id="complaintInput" name="complaint" placeholder="Complaint" required></textarea>
        <button type="submit">Submit</button>
    </form>
</body>

</html>
