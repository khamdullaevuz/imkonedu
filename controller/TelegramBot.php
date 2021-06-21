<?php

namespace app;

class TelegramBot
{
	public $api_key;

	function __construct($api_key)
	{
		$this->api_token = $api_key;
	}

	function go($method,$datas=[]){
        $url = "https://api.telegram.org/bot".$this->api_token."/".$method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }else{
            return json_decode($res);
        }
    }

    function getData($data){
        return json_decode(file_get_contents($data),true);
    }

    function buildKeyboard($datas=[]){
        $new_keyboard = [];
        $i = 0;
        foreach($datas as $row){
            foreach($row as $button) {
                $new_keyboard[$i][] = ['text' => $button];
            }
            $i++;
        }
        return json_encode([
            'resize_keyboard'=>true,
            'keyboard'=> $new_keyboard
        ]);
    }

    function buildInlineKeyboard($datas=[]){
        $new_keyboard = [];
        $i = 0;
        foreach($datas as $row){
            foreach($row as $button) {
                $new_keyboard[$i][] = ['text' => $button[0], 'callback_data'=>$button[1]];
            }
            $i++;
        }
        return json_encode([
            'inline_keyboard'=>$new_keyboard
        ]);
    }

    function buildInlineKeyboardCustom($datas=[]){
        return json_encode([
            'inline_keyboard'=>$datas
        ]);
    }

    function sendMessage($chat_id, $datas=[]){
    	return $this->go('sendMessage',
        array_merge([
        'chat_id'=>$chat_id
        ], $datas)
        );
    }

    function sendPhoto($chat_id, $datas=[]){
        return $this->go('sendPhoto',
        array_merge([
        'chat_id'=>$chat_id
        ], $datas)
        );
    }    

    function sendInvoice($chat_id, $datas=[]){
        return $this->go('sendInvoice',
        array_merge([
        'chat_id'=>$chat_id
        ], $datas)
        );
    }   

    function answerPreCheckoutQuery($pre_check_id, $bool = true){
        return $this->go('answerPreCheckoutQuery', [
        'pre_checkout_query_id'=>$pre_check_id,
        'ok'=>$bool
        ]);
    }   

    function answerInline($chat_id, $datas=[]){
        return $this->go('answerInlineQuery',
        array_merge([
        'chat_id'=>$chat_id
        ], $datas)
        );
    }  

    function answerCallback($call_id, $text, $show_alert = false){
        return $this->go('answerCallbackQuery',[
        'callback_query_id'=>$call_id,
        'text'=>$text,
        'show_alert'=>$show_alert
        ]);
    }     

    function editMessage($chat_id, $datas=[]){
        return $this->go('editMessageText',
        array_merge([
        'chat_id'=>$chat_id
        ], $datas)
        );
    } 

    function deleteMessage($chat_id, $message_id){
        return $this->go('deleteMessage',[
        'chat_id'=>$chat_id,
        'message_id'=>$message_id
        ]);
    } 

    function sendAction($chat_id, $action){
        return $this->go('sendChatAction',[
        'chat_id'=>$chat_id,
	    'action'=>$action
        ]);
    }   

    function getChatMember($chat_id, $user_id){
        return $this->go('getChatMember',[
        'chat_id'=>$chat_id,
        'user_id'=>$user_id
        ]);
    } 
}
