<?php
$to = 'info@balttrans.eu';

 $select = $_POST['list'];

if($select == 'str2') {
  $select = "Грузоперевозки";
}
else if($select == 'str3') {
  $select = "Переезд";
}
else if($select == 'str4') {
  $select = "Вывоз мусора";
}
else if($select == 'str5') {
  $select = "Грузчики/сборщики";
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$radio = $_POST['rad'];

if($radio == 'personal') {
  $radio = "Частный";
}
else if($radio == 'business') {
  $radio = "Бизнес";
}

$startPlace = $_POST['start'];
$startStage = $_POST['start-level'];
$firstElevator = $_POST['start-elevator'];

if($firstElevator == 'yes') {
  $firstElevator = "Есть";
}
else if($firstElevator == 'no') {
  $firstElevator = "Нету";
}

$endPlace = $_POST['finish'];

if(!$endPlace) {
  $endPlace = "---";
}

$finishStage = $_POST['finish-level'];

if(!$finishStage) {
  $finishStage = "---";
}

$secondElevator = $_POST['finish-elevator'];

if(!$secondElevator) {
  $secondElevator = "---";
}

if($secondElevator == 'yes') {
  $secondElevator = "Есть";
}
else if($secondElevator == 'no') {
  $secondElevator = "Нету";
}

$loaders = $_POST['checkbox'];

if(!$loaders) {
  $loaders = "---";
}

$date = $_POST['work-date'];

if(!$date) {
  $date = "---";
}

$time = $_POST['work-time'];

if(!$time) {
  $time = "---";
}

$message = $_POST['textarea'];
$message = htmlspecialchars($message);


 if ( !empty( $_FILES['file']['tmp_name'] ) and $_FILES['file']['error'] == 0 ) {
   $filepath = $_FILES['file']['tmp_name'];
   $filename = $_FILES['file']['name'];
 } else {
   $filepath = '';
   $filename = '';
 }

 $body = "Вид работы: ".$select."\n\n";
 $body .= "Заказчик: ".$name."\n";
 $body .= "Телефон: ".$phone."\n";
 $body .= "Почта: ".$email."\n";
 $body .= "Лицо: ".$radio."\n\n";
 $body .= "Адрес: ".$startPlace."\n";
 $body .= "Этаж: ".$startStage."\n";
 $body .= "Лифт: ".$firstElevator."\n";
 $body .= "Грузчики: ".$loaders."\n\n";
 $body .= "Адрес доставки: ".$endPlace."\n";
 $body .= "Этаж: ".$finishStage."\n";
 $body .= "Лифт: ".$secondElevator."\n\n";
 $body .= "Дата: ".$date."\n";
 $body .= "Время: ".$time."\n\n";
 $body .= "Доп. инфа: ".$message."\n";


 send_mail($to, $body, $filepath, $filename);



// Вспомогательная функция для отправки почтового сообщения с вложением
function send_mail($to, $body, $filepath, $filename)
{
 $subject = 'Заказ пришел';
 $boundary = "--".md5(uniqid(time())); // генерируем разделитель
 $headers = "MIME-Version: 1.0\r\n";
 $headers .="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
 $multipart = "--".$boundary."\r\n";
 $multipart .= "Content-type: text/plain; charset=\"utf-8\"\r\n";
 $multipart .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";

 $body = $body."\r\n\r\n";

 $multipart .= $body;

 $file = '';
 if ( !empty( $filepath ) ) {
   $fp = fopen($filepath, "r");
   if ( $fp ) {
     $content = fread($fp, filesize($filepath));
     fclose($fp);
     $file .= "--".$boundary."\r\n";
     $file .= "Content-Type: application/octet-stream\r\n";
     $file .= "Content-Transfer-Encoding: base64\r\n";
     $file .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
     $file .= chunk_split(base64_encode($content))."\r\n";
   }
 }
 $multipart .= $file."--".$boundary."--\r\n";
 mail($to, $subject, $multipart, $headers);
}

?>
