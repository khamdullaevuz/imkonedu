<?php

require_once __DIR__ . '/connect.php';

if(!empty($_POST['login']) and !empty($_POST['password'])){
$login = $_POST['login'];
$password = md5($_POST['password']);

$query = "SELECT * FROM admin WHERE `login` = '$login' and `password` = '$password'";
$result = mysqli_query($db, $query);

if(mysqli_num_rows($result) == 1){
	setcookie("login", $login, time() + 3600, '/');
	setcookie("password", $password, time() + 3600, '/');

	header('location: ./');
}else{
	header('location: login.php');
}
}else{
	header('location: login.php');
}