<?php

date_default_timezone_set("Asia/Kuwait");


$token = "6572158611:AAFYvPN66dWhWhS7HOUI-ys53y0s57mFEwE"; // bot token here
define("API_KEY", $token);

require_once("abod.php");

function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $datas = http_build_query($datas);
    $res = file_get_contents($url . "?" . $datas);
    return json_decode($res);
}


@$update = json_decode(file_get_contents("php://input"));
@$message = $update->message;
@$id = $message->from->id;
@$chat_id = $message->chat->id;
@$text = $message->text;
@$user1 = $message->from->first_name;
@$user = $message->from->username;
@$forward_from_chat = $message->forward_from_chat;
@$forward_sender_name = $message->forward_sender_name;
@$forward_origin = $message->forward_origin;
if (isset($update->callback_query)) {
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data;
    $user = $update->callback_query->from->username;
    $user2 = $update->callback_query->from->first_name;
}



function editmsg($chat_id, $message_id, $text, $reply_markup = [])
{
    $abod = bot('editmessagetext', [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $text,
        'reply_markup' => json_encode([
            'inline_keyboard' => $reply_markup
        ])
    ]);
    return $abod;
}

function sendmsg($chat_id, $text, $reply_markup = [])
{
    $abod = bot('sendmessage', [
        'chat_id' => $chat_id,
        'text' => $text,
        'reply_markup' => json_encode([
            'inline_keyboard' => $reply_markup
        ])
    ]);
    return $abod;
}
// مصفوفة رموز العملات
$currencies = [
    "USD" => "💵",
    "EUR" => "💶",
    //"TRY" => "🇹🇷",
    //"EGP" => "🇪🇬",
    "SAR" => "🇸🇦",
    //"JOD" => "🇯🇴",
    //"AED" => "🇦🇪",
    //"QAR" => "🇶🇦",
    //"BHD" => "🇧🇭",
    //"LYD" => "🇱🇾",
    "KWD" => "🇰🇼",
    //"OMR" => "🇴🇲",
    //"GBP" => "💷",
    //"SEK" => "🇸🇪",
    //"CAD" => "🇨🇦",
    //"NOK" => "🇳🇴",
    //"DKK" => "🇩🇰",
];


$branches = [
    'damascus' => 6,
    'aleppo' => 7,
    'idlib' => 8,
    'alhasakah' => 9
];

$branch = "damascus";
$curr = new Sp_Currency(governorate: $branch);
$message = "📍 أسعار العملات في دمشق | " . ucfirst(string: $branch) . "\n";
$message .= "--------------------------------------\n";

foreach ($currencies as $currency => $emoji) {
    $data = $curr->get_Price(currency: $currency);

    $message .= "{$emoji} {$data['name']} ({$data['Ar']})\n";
    $message .= "🔹 شراء: {$data['Buy']}\n";
    $message .= "🔹 بيع: {$data['Sell']}\n";
    $message .= "📈 التغيير: {$data['Change']} ({$data['Percentage']})\n";
    $message .= "--------------------------------------\n\n";
}

$message .= "📅 تاريخ آخر تحديث :". date(format: "d/m/Y | H:i:s") . "\n";

sendmsg(chat_id: -1002059408599, text: $message, reply_markup: [
    [['text' => 'قناتنا ✅', 'url' => 'https://t.me/aboodalajrad8']]
]);

?>