<?php
  header('Access-Control-Allow-Origin: http://localhost:3000');
  header('Access-Control-Allow-Headers: *');
  header('Access-Control-Allow-Methods: POST');

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  const HOST = '';
  const USERNAME = '';
  const PASSWORD = '';

  const ADDRESS = '';
  const NAME = '';

  sleep(1);

  $request = json_decode(file_get_contents('php://input'));   

  if (empty($request->name)) {
    exit(json_encode(['error' => 'Por favor preencha o campo Nome!']));
  }

  if (empty($request->email)) {
    exit(json_encode(['error' => 'Por favor preencha o campo E-mail!']));
  }

  if (empty($request->phone)) {
    exit(json_encode(['error' => 'Por favor preencha o campo Telefone!']));
  }

  if (empty($request->message)) {
    exit(json_encode(['error' => 'Por favor preencha o campo Mensagem!']));
  }

  $name = filter_var($request->name, FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
  $phone = filter_var($request->phone, FILTER_SANITIZE_SPECIAL_CHARS);
  $message = filter_var($request->message, FILTER_SANITIZE_SPECIAL_CHARS);

  $mail = new PHPMailer(true);

  try {
    //$mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host = HOST;
    $mail->SMTPAuth = true;
    $mail->Username = USERNAME;
    $mail->Password = PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($email, $name);
    $mail->addAddress(ADDRESS, NAME);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Novo contato do site';
    $mail->Body = '<b>Nome:</b> ' . $name . '<br>' . 
                  '<b>E-mail:</b> ' . $email . '<br>' .
                  '<b>Telefone:</b> ' . $phone . '<br>' .
                  '<b>Mensagem:</b> ' . $message;

    $mail->send();

    exit(json_encode(['success' => true]));
  } catch (Exception $e) {
    exit(json_encode(['error' => 'Ocorreu um erro desconhecido, tente novamente mais tarde']));
  }