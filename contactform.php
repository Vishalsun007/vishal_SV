<?php
if(isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["message"])){
$fullname=$_POST["name"];
$email=strtolower($_POST["email"]);
$message=$_POST["message"];


$domains = array('gmail.com', 'outlook.com', 'yahoo.in', 'yahoo.com', 'hotmail.com');
$pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]*(" . implode('|', $domains) . ")$/i";
if (!preg_match($pattern, $email)) {
echo'Service provider not allowed';   
return 1;
}
else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
echo 'Invalid email format';
return 1;
}
else{
//GRAB REMOTE SERVER DETAILS
date_default_timezone_set('Europe/London');
$date=date('d-m-Y');
$ip=$_SERVER['REMOTE_ADDR'];
$agent=$_SERVER['HTTP_USER_AGENT'];
$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip)); 
$country=$ipdat->geoplugin_countryName;

//CONNECT DB
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";


$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
die("Connection failed"); 

$sql = "INSERT INTO TableName (fullname, email, message, date)
VALUES ('$fullname', '$email', '$message', '$date')";

if (mysqli_query($conn, $sql)) {
$to = "Your_email";
$subject = "Queries";
$txt = "Client: $fullname\r\nEmail: $email\r\nMessage: $message";
$headers = "From: queries@mywebsitename.com" . "\r\n" .
"CC: queries@mywebsitename.com";
mail($to,$subject,$txt,$headers);
echo "success";
} else {
echo "Failed";
}
mysqli_close($conn);
}
}
else{
echo"Direct access disabled";
}
?>