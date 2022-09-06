<?php

/**
 * @description Telegram bot library
 * @author Elbek Khamdullaev <elbekh75@gmail.com>
 * @license free and open source
 */

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once __DIR__ . '/controller/TelegramBot.php';

use app\TelegramBot;

$bot = new TelegramBot("API_KEY");
$mysqli = new mysqli("localhost", "user", "password", "dbname");
$mysqli->set_charset("utf8mb4");
define('CLICK_KEY', "CLICK_KEY");
$admin = 717404897;

$hostmail = "register@imkonedu.uz";
$update = $bot->getData('php://input');
if(empty($update)) exit;
if($update['message'])
$message = $update['message'];
else
$message = $update['callback_query']['message'];
$chat_id = $message['chat']['id'];
$user_id = $message['from']['id'];
$username = $message['from']['username'];
$name = $message['from']['first_name'].' '.$message['from']['last_name'];
$text = $message['text'];
$message_id = $message['message_id'];
$callback_query = $update['callback_query'];
$callback_id = $callback_query['id'];
$callback_data = $callback_query['data'];
$pre_check_id = $update['pre_checkout_query']['id'];
$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
$result = $mysqli->query($query);
$info = $result->fetch_assoc();
$step = $info['step'];
$languser = $info['lang'];
$query = "SELECT * FROM lang";
$result = $mysqli->query($query);
$userlang = [];
while($langchange = $result->fetch_assoc()){
	$userlang[$langchange['name']] = $langchange[$languser];
}

if(isset($pre_check_id)){
	$bot->answerPreCheckoutQuery($pre_check_id, true);
}

$lang = $bot->buildInlineKeyboard([
	[["🇷🇺 Русский","ru"],["🇺🇿 O’zbek","uz"]]
]);

$main = $bot->buildInlineKeyboard([
	[[$userlang['profile'], "profile"],[$userlang['courses'], "courses"]],
	[[$userlang['social'], "social"],[$userlang['platform'], "platform"]],
	[[$userlang['app'], "app"],[$userlang['about'], "about"]],
	[[$userlang['q&a'], "q&a"]]
]);

$login = $bot->buildInlineKeyboard([
	[[$userlang['registerbutton'], "register"]],
	[[$userlang['loginbutton'], "login"]]
]);

if($text == "/start"){
	if(!$info){
	$mysqli->query("INSERT INTO users (`user_id`) VALUES ('$chat_id')");
	}

	$bot->sendMessage($chat_id, [
		'text'=>"🇷🇺 Выберите язык!
🇺🇿 Iltimos tilni tanlang!",
		'reply_markup'=>$lang
	]);

}

if($callback_data == "uz"){
	$mysqli->query("UPDATE `users` SET `lang` = 'uz' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	$step = $info['step'];
	$lang = $info['lang'];
	$query = "SELECT * FROM lang";
	$result = $mysqli->query($query);
	$userlang = [];
	while($langchange = $result->fetch_assoc()){
		$userlang[$langchange['name']] = $langchange[$lang];
	}
	$channel_id = -1001323537788;
	$result = $bot->getChatMember($channel_id, $chat_id)->result->status;
	if(!($result == "member" or $result == "administrator" or $result == "creator")){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Botga xabar yuborish uchun <a href='https://t.me/ImkonEducation'>Imkon Education</a> kanaliga a’zo bo’lishingiz kerak!",
		'parse_mode'=>"HTML",
		'reply_markup'=>$bot->buildInlineKeyboardCustom([
		[['text'=>"Davom ettirish", 'callback_data'=>'channel']]
		])
	]);
	}else{
	if($info['code']=="1"){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Iltimos ma’lumot turini tanlang!",
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['profile'], "profile"],[$userlang['courses'], "courses"]],
			[[$userlang['social'], "social"],[$userlang['platform'], "platform"]],
			[[$userlang['app'], "app"],[$userlang['about'], "about"]],
			[[$userlang['q&a'], "q&a"]]
		])
	]);
	}else{
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Siz tizimga kirish bo’limidasiz! ",
		'reply_markup'=>$bot->buildInlineKeyboard([
			[["Ro’yhatdan o’tish", "register"]],
			[["Tizimga Kirish", "login"]]
		])
	]);
	}
	}
}

if($callback_data == "ru"){
	$mysqli->query("UPDATE `users` SET `lang` = 'ru' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	$step = $info['step'];
	$lang = $info['lang'];
	$query = "SELECT * FROM lang";
	$result = $mysqli->query($query);
	$userlang = [];
	while($langchange = $result->fetch_assoc()){
		$userlang[$langchange['name']] = $langchange[$lang];
	}
	$channel_id = -1001323537788;
	$result = $bot->getChatMember($channel_id, $chat_id)->result->status;
	if(!($result == "member" or $result == "administrator" or $result == "creator")){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Чтобы отправить сообщение боту, вам необходимо подписаться на канал <a href='https://t.me/ImkonEducation'>Imkon Education</a>!",
		'parse_mode'=>"HTML",
		'reply_markup'=>$bot->buildInlineKeyboardCustom([
		[['text'=>"Продолжить", 'callback_data'=>'channel']]
		])
	]);
	}else{
	if($info['code']=="1"){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Пожалуйста, выберите тип информации",
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['profile'], "profile"],[$userlang['courses'], "courses"]],
			[[$userlang['social'], "social"],[$userlang['platform'], "platform"]],
			[[$userlang['app'], "app"],[$userlang['about'], "about"]],
			[[$userlang['q&a'], "q&a"]]
		])
	]);
	}else{
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>"Вы находитесь в разделе входа в систему!",
		'reply_markup'=>$bot->buildInlineKeyboard([
			[["Зарегистрироваться", "register"]],
			[["Авторизоваться", "login"]]
		])
	]);
	}
	}
}

if($callback_data == "channel"){
	$channel_id = -1001323537788;
	$result = $bot->getChatMember($channel_id, $chat_id)->result->status;
	if($result == "member" or $result == "administrator" or $result == "creator"){
	if($info['code']=="1"){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['main'],
		'reply_markup'=>$main
	]);
	}else{
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['login'],
		'reply_markup'=>$login
	]);	
	}
	}else{
	$bot->answerCallback($callback_id, $userlang['nomember'], true);
	}
}

// ---------------------- change data & profile --------------------\\

if($callback_data == "profile"){
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

if($callback_data == "back"){
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['main'],
		'reply_markup'=>$main
	]);
	exit;
}

if($callback_data == "break"){
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);

	exit;
}

if($callback_data == "phone"){
	$mysqli->query("UPDATE `users` SET `step` = 'phone' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	$text = str_replace('{phone}', $phone, $userlang['phoneadd']);
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$bot->buildInlineKeyboard([
		[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if($step == "phone"){
	if(strlen($text)== 9 and is_numeric($text)){
	$mysqli->query("UPDATE `users` SET `phone` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->deleteMessage($chat_id, $message_id);
	$bot->deleteMessage($chat_id, --$message_id);
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['phoneincorrect']
	]);	
	}
}

if($callback_data == "name"){
	$mysqli->query("UPDATE `users` SET `step` = 'name' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];	
	$text = str_replace('{name}', $name, $userlang['nameadd']);
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$bot->buildInlineKeyboard([
		[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if($step == "name"){
	$mysqli->query("UPDATE `users` SET `name` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->deleteMessage($chat_id, $message_id);
	$bot->deleteMessage($chat_id, --$message_id);
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

if($callback_data == "surname"){
	$mysqli->query("UPDATE `users` SET `step` = 'surname' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];	
	$text = str_replace('{surname}', $surname, $userlang['surnameadd']);
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$bot->buildInlineKeyboard([
		[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if($step == "surname"){
	$mysqli->query("UPDATE `users` SET `surname` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->deleteMessage($chat_id, $message_id);
	$bot->deleteMessage($chat_id, --$message_id);
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

if($callback_data == "young"){
	$mysqli->query("UPDATE `users` SET `step` = 'young' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];	
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['youngadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
		[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if($step == "young"){
	if(substr_count($text, '.')==2){
	$ex = explode(".",$text);
	if(strlen($ex[0])==2 and strlen($ex[1])==2 and strlen($ex[2])==4){
	$mysqli->query("UPDATE `users` SET `young` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->deleteMessage($chat_id, $message_id);
	$bot->deleteMessage($chat_id, --$message_id);
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['yosherror']
	]);	
	}
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['yosherror']
	]);	
	}
}

if($callback_data == "type"){
		
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['faoliyatadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['student'], "tipestudent"],[$userlang['schoolboy'], "tipeschoolboy"]],
			[[$userlang['teacher'], "tipeteacher"],[$userlang['dasturchi'], "tipedasturchi"]],
			[[$userlang['tadbirkor'], "tipetadbirkor"],[$userlang['ishchi'], "tipeishchi"]],
			[[$userlang['investor'], "tipeinvestor"],[$userlang['dizayner'], "tipedizayner"]],
			[[$userlang['montajer'], "tipemontajer"],[$userlang['operator'], "tipeoperator"]],
			[[$userlang['rejisser'], "tiperejisser"],[$userlang['bankir'], "tipebankir"]],
			[[$userlang['fotograf'], "tipefotograf"],[$userlang['bloger'], "tipebloger"]],
			[[$userlang['muhandis'], "tipemuhandis"],[$userlang['architector'], "tipearchitector"]],
			[[$userlang['oshpaz'], "tipeoshpaz"],[$userlang['ishsiz'], "tipeishsiz"]],
			[[$userlang['doctor'], "tipedoctor"],[$userlang['harbiy'], "tipeharbiy"]],
			[[$userlang['quruvchi'], "tipequruvchi"],[$userlang['hisobchi'], "tipehisobchi"]],
			[[$userlang['freelancer'], "tipefreelancer"],[$userlang['jurnalist'], "tipejurnalist"]],
			[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if(mb_stripos($callback_data, "tipe") !== false){
	$tipe = str_replace('tipe', '', $callback_data);
	$tipe = $userlang[$tipe];
	$mysqli->query("UPDATE `users` SET `type` = '$tipe' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");

	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

if($callback_data == "province"){
		
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['provinceadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['toshkentsh'], "viltoshkentsh"],[$userlang['toshkentv'], "viltoshkentv"]],
			[[$userlang['andijon'], "vilandijon"],[$userlang['buxoro'], "vilbuxoro"]],
			[[$userlang['jizzax'], "viljizzax"],[$userlang['qashqadaryo'], "vilqashqadaryo"]],
			[[$userlang['navoi'], "vilnavoi"],[$userlang['namangan'], "vilnamangan"]],
			[[$userlang['samarqand'], "vilsamarqand"],[$userlang['surxondaryo'], "vilsurxondaryo"]],
			[[$userlang['sirdaryo'], "vilsirdaryo"],[$userlang['fargona'], "vilfargona"]],
			[[$userlang['xorazm'], "vilxorazm"],[$userlang['qoraqalpoq'], "vilqoraqalpoq"]],
			[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if(mb_stripos($callback_data, "vil") !== false){
	$vil = str_replace('vil', '', $callback_data);
	$vil = $userlang[$vil];
	$mysqli->query("UPDATE `users` SET `province` = '$vil' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

if($callback_data == "jinsi"){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['jinsadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
		[[$userlang['male'], "gendermale"]],
		[[$userlang['female'], "genderfemale"]],
		[["⬅️ ". $userlang['back'], "break"]]
		])
	]);
}

if(mb_stripos($callback_data, "gender") !== false){
	$jinsi = str_replace('gender', '', $callback_data);
	$jinsi = $userlang[$jinsi];
	$mysqli->query("UPDATE `users` SET `jinsi` = '$jinsi' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM users WHERE `user_id` = '$chat_id'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();

	if($info['name'])
	$name = $info['name'];
	else $name = $userlang['empty'];
	if($info['surname'])
	$surname = $info['surname'];
	else $surname = $userlang['empty'];
	if($info['phone'])
	$phone = $info['phone'];
	else $phone = $userlang['empty'];	
	if($info['province'])
	$province = $info['province'];
	else $province = $userlang['empty'];	
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['type'])
	$type = $info['type'];
	else $type = $userlang['empty'];
	if($info['young'])
	$young = $info['young'];
	else $young = $userlang['empty'];
	if($info['jinsi'])
	$jinsi = $info['jinsi'];
	else $jinsi = $userlang['empty'];

	$text = str_replace(['{phone}', '{name}', '{surname}', '{province}', '{type}', '{young}', '{jinsi}'], [$phone, $name, $surname, $province, $type, $young, $jinsi], $userlang['info']);

	$profilemenu = $bot->buildInlineKeyboard([
	[[$userlang['phone']." 📝", "phone"],[$userlang['name']." 📝", "name"]],
	[[$userlang['surname']." 📝", "surname"],[$userlang['province']." 📝", "province"]],
	[[$userlang['type']." 📝", "type"],[$userlang['young']." 📝", "young"]],
	[[$userlang['jinsi']." 📝", "jinsi"]],
	[["⬅️ ". $userlang['back'], "back"]]
	]);

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$text,
		'reply_markup'=>$profilemenu
	]);
}

//----------------------- login & register --------------------------------\\

if($callback_data == "register"){
	$mysqli->query("UPDATE `users` SET `step` = 'ism' WHERE `user_id` = '$chat_id'");
	$bot->deleteMessage($chat_id, $message_id);
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['ismadd']
	]);
	exit;
}

if($callback_data == "login"){
	$mysqli->query("UPDATE `users` SET `step` = 'login' WHERE `user_id` = '$chat_id'");
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['emailadd']
	]);
	exit;
}

if($step == "login" and isset($text)){
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

	if (preg_match($pattern, $text) === 1) {
	$query = "SELECT * FROM users WHERE `email` = '$text'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info){
	$code = substr(str_shuffle('1234567890'),0,6);
	$mysqli->query("UPDATE `users` SET `code` = '$code' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `email` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'emailconfirm' WHERE `user_id` = '$chat_id'");

	$to = $text;
	$subject = "ImkonEdu bot tasdiqlash";
	$txt = "Tasdiqlash uchun kod: " . $code;
	$headers = "From: " . $hostmail;
	mail($to,$subject,$txt,$headers);

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailconfirm']
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailnotfound'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['registerbutton'], "register"]]
		])
	]);	
	}
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailincorrect']
	]);	
	}
}

if($step == "emailconfirm"){
	if($text == $info['code']){
	$result = $mysqli->query("SELECT * FROM `users` WHERE `code` = '$text' AND `user_id` = '$chat_id'");
	$info = $result->fetch_assoc();
	$email = $info['email'];
	$mysqli->query("DELETE FROM `users` WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `user_id` = '$chat_id' WHERE `email` = '$email'");
	
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['loginsuccess']
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['main'],
		'reply_markup'=>$main
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailnotcorrect'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['otheremail'], "login"]]
		])
	]);
	}
}

if($step == "ism"){
	$mysqli->query("UPDATE `users` SET `name` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'familiya' WHERE `user_id` = '$chat_id'");

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['familiyaadd']
	]);
}

if($step == "familiya"){
	$mysqli->query("UPDATE `users` SET `surname` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'telefon' WHERE `user_id` = '$chat_id'");

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['telefonadd']
	]);
}

if($step == "telefon"){
	if(strlen($text)== 9 and is_numeric($text)){
	$mysqli->query("UPDATE `users` SET `phone` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['viloyatadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['toshkentsh'], "prvtoshkentsh"],[$userlang['toshkentv'], "prvtoshkentv"]],
			[[$userlang['andijon'], "prvandijon"],[$userlang['buxoro'], "prvbuxoro"]],
			[[$userlang['jizzax'], "prvjizzax"],[$userlang['qashqadaryo'], "provqashqadaryo"]],
			[[$userlang['navoi'], "prvnavoi"],[$userlang['namangan'], "prvnamangan"]],
			[[$userlang['samarqand'], "prvsamarqand"],[$userlang['surxondaryo'], "prvsurxondaryo"]],
			[[$userlang['sirdaryo'], "prvsirdaryo"],[$userlang['fargona'], "prvfargona"]],
			[[$userlang['xorazm'], "prvxorazm"],[$userlang['qoraqalpoq'], "prvqoraqalpoq"]]
		])
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['phoneincorrect'],
	]);
	}
}

if(mb_stripos($callback_data, "prv") !== false){
	$prov = str_replace('prv', '', $callback_data);
	$prov = $userlang[$prov];
	$mysqli->query("UPDATE `users` SET `province` = '$prov' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'yosh' WHERE `user_id` = '$chat_id'");

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['yoshadd']
	]);
}

if($step == "yosh"){
	if(substr_count($text, '.')==2){
	$ex = explode(".",$text);
	if(strlen($ex[0])==2 and strlen($ex[1])==2 and strlen($ex[2])==4){
	$mysqli->query("UPDATE `users` SET `young` = '$text' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['jinsadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['male'], "gnmale"]],
			[[$userlang['female'], "gnfemale"]],
		])
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['yosherror']
	]);	
	}
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['yosherror']
	]);	
	}
}

if(mb_stripos($callback_data, "gn") !== false){
	$jinsi = str_replace('gn', '', $callback_data);
	$jinsi = $userlang[$jinsi];
	$mysqli->query("UPDATE `users` SET `jinsi` = '$jinsi' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['faoliyatadd'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['student'], "tpestudent"],[$userlang['schoolboy'], "tpeschoolboy"]],
			[[$userlang['teacher'], "tpeteacher"],[$userlang['dasturchi'], "tpedasturchi"]],
			[[$userlang['tadbirkor'], "tpetadbirkor"],[$userlang['ishchi'], "tpeishchi"]],
			[[$userlang['investor'], "tpeinvestor"],[$userlang['dizayner'], "tpedizayner"]],
			[[$userlang['montajer'], "tpemontajer"],[$userlang['operator'], "tpeoperator"]],
			[[$userlang['rejisser'], "tperejisser"],[$userlang['bankir'], "tpebankir"]],
			[[$userlang['fotograf'], "tpefotograf"],[$userlang['bloger'], "tpebloger"]],
			[[$userlang['muhandis'], "tpemuhandis"],[$userlang['architector'], "tpearchitector"]],
			[[$userlang['oshpaz'], "tpeoshpaz"],[$userlang['ishsiz'], "tpeishsiz"]],
			[[$userlang['doctor'], "tpedoctor"],[$userlang['harbiy'], "tpeharbiy"]],
			[[$userlang['quruvchi'], "tpequruvchi"],[$userlang['hisobchi'], "tpehisobchi"]],
			[[$userlang['freelancer'], "tpefreelancer"],[$userlang['jurnalist'], "tpejurnalist"]],
		])
	]);
}

if(mb_stripos($callback_data, "tpe") !== false){
	$tpe = str_replace('tpe', '', $callback_data);
	$tpe = $userlang[$tpe];
	$mysqli->query("UPDATE `users` SET `type` = '$tpe' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'regemail' WHERE `user_id` = '$chat_id'");

	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['emailadd']
	]);
}

if($step == "regemail"){
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

	if (preg_match($pattern, $text) === 1) {
	$query = "SELECT * FROM users WHERE `email` = '$text'";
	$result = $mysqli->query($query);
	$info = $result->fetch_assoc();
	if($info['code']!=="1"){
	$code = substr(str_shuffle('1234567890'),0,6);
	$mysqli->query("UPDATE `users` SET `code` = '$code' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `step` = 'regemailconfirm' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `email` = '$text' WHERE `user_id` = '$chat_id'");
	$to = $text;
	$subject = "ImkonEdu bot tasdiqlash";
	$txt = "Tasdiqlash uchun kod: " . $code;
	$headers = "From: " . $hostmail;
	mail($to,$subject,$txt,$headers);

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailconfirm']
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailfound']
	]);	
	}
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailincorrect']
	]);	
	}
}

if($step == "regemailconfirm"){
	if($text == $info['code']){
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$mysqli->query("UPDATE `users` SET `code` = '1' WHERE `user_id` = '$chat_id'");
	
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['loginsuccess']
	]);

	$bot->sendMessage($chat_id, [
		'text'=>$userlang['main'],
		'reply_markup'=>$main
	]);
	}else{
	$bot->sendMessage($chat_id, [
		'text'=>$userlang['emailnotcorrect'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[[$userlang['otheremail'], "login"]]
		])
	]);
	}
}

if($callback_data == "about"){
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['abouttext'],
	'parse_mode'=>"markdown",
	'reply_markup'=>$bot->buildInlineKeyboard([
		[["⬅️ ". $userlang['back'], "back"]]
	])
	]);
}

/* ----------------------------------------- */
if($callback_data == "courses"){
	$query = "SELECT * FROM course_category ORDER BY id DESC LIMIT 5 OFFSET 0";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['name_'.$languser], "coursecategory".$row['id']]];
	}

	$res = $mysqli->query("SELECT * FROM course_category")->num_rows;
	$count = ceil($res/5);

	$page = 0;
	$echopage = 1;

	if(!($echopage == $count) and $res !== 0)
	$button[] = [["•","*"],["$echopage / $count","*"],[">","nextusercat$page"]];


	$button[] = [["⬅️ ". $userlang['back'], "back"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursecategorychange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}

if(mb_stripos($callback_data, "nextusercat")!== false){
	$page = str_replace("nextusercat", "", $callback_data);
	$page += 1;
	$echopage = $page+1;
	$offset = $page * 5;
	$query = "SELECT * FROM course_category ORDER BY id DESC LIMIT 5 OFFSET $offset";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['name_' . $languser], "coursecategory".$row['id']]];
	}

	$count = ceil($mysqli->query("SELECT * FROM course_category")->num_rows/5);

	if($echopage == $count)
	$button[] = [["<","prevusercat$page"],[$echopage ." / $count","*"],["•","*"]];
	else
	$button[] = [["<","prevusercat$page"],[$echopage ." / $count","*"],[">","nextusercat$page"]];

	$button[] = [["⬅️ ". $userlang['back'], "back"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursecategorychange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}

if(mb_stripos($callback_data, "prevusercat")!== false){
	$page = str_replace("prevusercat", "", $callback_data);
	$page -= 1;
	$echopage = $page+1;
	$offset = $page * 5;
	$query = "SELECT * FROM course_category ORDER BY id DESC LIMIT 5 OFFSET $offset";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['name_' . $languser], "coursecategory".$row['id']]];
	}

	$count = ceil($mysqli->query("SELECT * FROM course_category")->num_rows/5);

	if($page == 0)
	$button[] = [["•","*"],[$echopage ." / $count","*"],[">","nextusercat$page"]];
	else
	$button[] = [["<","prevusercat$page"],[$echopage ." / $count","*"],[">","nextusercat$page"]];
		
	$button[] = [["⬅️ ". $userlang['back'], "back"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursecategorychange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}

/* ----------------------------------------- */
if(mb_stripos($callback_data, "coursecategory") !== false){
	$category_id = str_replace("coursecategory", "", $callback_data);
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM courses WHERE `category_id` = '$category_id' ORDER BY id DESC LIMIT 5 OFFSET 0";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['coursename_' . $languser], "courseid".$row['id']."&".$category_id]];
	}

	$res = $mysqli->query("SELECT * FROM courses WHERE `category_id` = '$category_id'")->num_rows;
	$count = ceil($res/5);

	$page = 0;
	$echopage = 1;

	if(!($echopage == $count) and $res !== 0)
	$button[] = [["•","*"],["$echopage / $count","*"],[">","nextusercourse$page"]];

	$button[] = [["⬅️ ". $userlang['back'], "courses"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursechange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}

if(mb_stripos($callback_data, "nextusercourse")!== false and $chat_id == $admin){
	$page = str_replace("nextusercourse", "", $callback_data);
	$page += 1;
	$echopage = $page+1;
	$offset = $page * 5;
	$query = "SELECT * FROM courses ORDER BY id DESC LIMIT 5 OFFSET $offset";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['coursename_uz'], "courseid".$row['id']."&".$category_id]];
	}

	$count = ceil($mysqli->query("SELECT * FROM courses WHERE `category_id` = '$category_id'")->num_rows/5);

	if($echopage == $count)
	$button[] = [["<","prevusercourse$page"],[$echopage ." / $count","*"],["•","*"]];
	else
	$button[] = [["<","prevusercourse$page"],[$echopage ." / $count","*"],[">","nextusercourse$page"]];

	$button[] = [["⬅️ ". $userlang['back'], "courses"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursechange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}

if(mb_stripos($callback_data, "prevusercourse")!== false and $chat_id == $admin){
	$page = str_replace("prevusercourse", "", $callback_data);
	$page -= 1;
	$echopage = $page+1;
	$offset = $page * 5;
	$query = "SELECT * FROM courses ORDER BY id DESC LIMIT 5 OFFSET $offset";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['coursename_uz'], "courseid".$row['id']."&".$category_id]];
	}

	$count = ceil($mysqli->query("SELECT * FROM courses WHERE `category_id` = '$category_id'")->num_rows/5);

	if($page == 0)
	$button[] = [["•","*"],[$echopage ." / $count","*"],[">","nextusercourse$page"]];
	else
	$button[] = [["<","prevusercourse$page"],[$echopage ." / $count","*"],[">","nextusercourse$page"]];

	$button[] = [["⬅️ ". $userlang['back'], "courses"]];

	$bot->editMessage($chat_id, [
      'message_id'=>$message_id,
      'text'=>$userlang['coursechange'],
      'reply_markup'=>$bot->buildInlineKeyboard($button)
   	]);
}
/* ----------------------------------------- */

if(mb_stripos($callback_data, "courseid") !== false){
	$course_id = str_replace("courseid", "", $callback_data);
	$ex = explode("&", $course_id);
	$course_id = $ex[0];
	$category_id = $ex[1];

	$query = "SELECT * FROM courses WHERE `id` = '$course_id'";
	$result = $mysqli->query($query);
	
	$row = $result->fetch_assoc();

	$button = [];

	$text = str_replace(['{coursename}','{coursedescription}', '{amount}'],[$row['coursename_' . $languser], $row['coursedescription_' . $languser], $row['amount']],$userlang['courseview']);
	$array = explode(",", $info['mycourses']);
	if(in_array($course_id, $array)){
	$text .= "\n".$userlang['buyyed'];
	}else{
	$button[] = [[$userlang['buy'], "buy" . $course_id]];
	}
	$button[] = [["⬅️ ". $userlang['back'], "coursecategory" . $category_id]];

	

	$bot->editMessage($chat_id, [
	'message_id'=>$message_id,
	'text'=>$text,
	'reply_markup'=>$bot->buildInlineKeyboard($button)
	]);
}

if(mb_stripos($callback_data, "buy") !== false){
	$course_id = str_replace("buy", "", $callback_data);
	$query = "SELECT * FROM courses WHERE `id` = '$course_id'";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$amount = $row['amount'];
	$coursename = $row['coursename_' . $languser];

	$bot->deleteMessage($chat_id, $message_id);
	$bot->sendInvoice($chat_id,[
		'title'=>$coursename,
	    'description'=>str_replace(["{amount}", "{coursename}"], [$amount, $coursename], $userlang['buycoursewithamount']),
	    'payload'=>"coursepayload-" . $course_id,
	    'provider_token'=>CLICK_KEY,
	    'start_parameter'=>"pay",
	    'currency'=>"UZS",
	    'prices'=>json_encode([
	    [
	    'label'=>$userlang['buy'],
	    'amount'=>$amount * 100
	    ]
	    ])
	]);
}

if(isset($message['successful_payment'])){
	$course_id = str_replace("coursepayload-", "", $message['successful_payment']['invoice_payload']);
	$query = "SELECT * FROM courses WHERE `id` = '$course_id'";
	$mycourses = $course_id . "," . $info['mycourses'];
	$mysqli->query("UPDATE `users` SET `mycourses` = '$mycourses' WHERE `user_id` = '$chat_id'");
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$name = $row['coursename_' . $languser];
	$bot->sendMessage($chat_id, [
		'text'=>str_replace("{coursename}", $name, $userlang['coursesuccessfulpayment']),
		'reply_markup'=>$bot->buildInlineKeyboard([
			[["⬅️ ". $userlang['back'], "back"]]
		])
	]);
}

if($callback_data == 'q&a'){
	$mysqli->query("UPDATE `users` SET `step` = 'q&a' WHERE `user_id` = '$chat_id'");
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>$userlang['q&a_main'],
		'reply_markup'=>$bot->buildInlineKeyboard([
			[["⬅️ ". $userlang['back'], "back"]]
		])
	]);
}

if($step == 'q&a'){
	$mysqli->query("UPDATE `users` SET `step` = '' WHERE `user_id` = '$chat_id'");
	$query = "SELECT * FROM question_answer WHERE question_".$languser." LIKE '%".$text."%' ORDER BY id DESC LIMIT 10 OFFSET 0";
	$result = $mysqli->query($query);
	$button = [];
	while($row = $result->fetch_assoc()){
		$button[] = [[$row['question_'. $languser], 'qaview'.$row['id']]];
	}

	$button[] = [["⬅️ ". $userlang['back'], "back"]];


	$bot->sendMessage($chat_id, [
		'text'=>$userlang['q&a_isset'],
		'reply_markup'=>$bot->buildInlineKeyboard($button)
	]);
}

if(mb_stripos($callback_data,"qaview")!==false){
	$id = str_replace("qaview","",$callback_data);
	$result = $mysqli->query("SELECT * FROM question_answer WHERE `id` = '$id'")->fetch_assoc();
	$bot->editMessage($chat_id, [
		'message_id'=>$message_id,
		'text'=>str_replace([
			'{question}'
			,'{answer}'
		],[
		$result['question_'.$languser],
		$result['answer_'.$languser]
		],$userlang['qaview']),
		'parse_mode'=>"markdown",
		'reply_markup'=>$bot->buildInlineKeyboard([
			[["⬅️ ". $userlang['back'], "back"]]
		])
	]);
}
