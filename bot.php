<?php

include "src/dictionary.php";
$content = file_get_contents("php://input");
$update = json_decode($content, true);
if (!$update) {
    exit;
}else{
    $message = $update['message']['text'];
    $chat_id = $update['message']['chat']['id'];
    $message_id = $update['message']['message_id'];
    $user_id = $update['message']['from']['id'];
    $first_name = $update['message']['from']['first_name'];
    $username = $update['message']['from']['username'];
    $r_user_id = $update['message']['reply_to_message']['from']['id'];
    $r_first_name = $update['message']['reply_to_message']['from']['first_name'];
    $r_username = $update['message']['reply_to_message']['from']['username'];
    $r_message_id = $update['message']['reply_to_message']['message_id'];
    $WELCOME_MSG = $_ENV['WELCOME_MSG'];

    if (!$r_user_id){
        $r_user_id = $user_id;
        $r_first_name = $first_name;
        $r_username = $username;
        $r_message_id = $message_id;
    }

    switch ($message) {
        case '/start':
            sendMessage($chat_id,$r_message_id, "Welcome To The Group. \nUse /cmds to know the command.\n $WELCOME_MSG");
            break;
        case '/cmds':
            sendMessage($chat_id,$r_message_id, "<b>Here is the list of commands :</b>\n<code>/info </code>(To Know the info of the user)\n<code>/status </code>(To check bot is alive or not)");
            break;
        case '/info':
            sendMessage($chat_id,$r_message_id,"<b>ID : </b>$r_user_id\n<b>First Name: </b>$r_first_name\n<b>Username : </b>@$r_username\n<b>Permanent Link : </b><a href='tg://user?id=$r_user_id'>$r_first_name</a>");
            break;
        case '/status':
            sendMessage($chat_id,$r_message_id,"ACTIVE");
            break;
        case '/leave':
            leaveChat($chat_id);
            break;
        case '/dice':
            sendDice($chat_id);
            break;
        case '/pin':
            pinChatMessage($chat_id,$r_message_id);
            break;          
    }
    if (strpos($message, "/dictionary") === 0) {
        $dictio = substr($message, 12);
            if(strlen($message)<13){
                sendMessage($chat_id,$r_message_id,"No words inputted");
            }
            else{
                $words = dictionary($dictio);
                sendMessage($chat_id,$r_message_id,$words);
            }
    }
}
function sendMessage($chat_id,$r_message_id, $message){
    $botToken = $_ENV['TOKEN'];
    $text = urlencode($message); 
    file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chat_id&reply_to_message_id=$message_id&text=$text&parse_mode=HTML");
}
function pinChatMessage($chat_id,$r_message_id){
    $botToken = $_ENV['TOKEN'];
    file_get_contents("https://api.telegram.org/bot$botToken/pinChatMessage?chat_id=$chat_id&message_id=$r_message_id&disable_notification=true");

}
function sendDice($chat_id){
    $botToken = $_ENV['TOKEN'];
    file_get_contents("https://api.telegram.org/bot$botToken/sendDice?chat_id=$chat_id"); 
}
function leaveChat($chat_id){
    $botToken = $_ENV['TOKEN'];
    file_get_contents("https://api.telegram.org/bot$botToken/leaveChat?chat_id=$chat_id"); 
}


?>
