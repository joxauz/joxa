 <?php
//    ___________________________________///
//      ÷÷÷÷÷÷÷ FAST CODER ÷÷÷÷÷÷÷÷  ///
//      ÷÷÷÷÷÷÷÷ PHP TEAM ÷÷÷÷÷÷÷÷  ///
//      ÷÷÷÷÷÷÷ @Fast_Coder ÷÷÷÷÷÷÷  ///
//     ÷÷÷ @Rustam_Hikmatullayev ÷÷÷ ///
//    __________________________________///





ob_start();
define('API_KEY','1431188534:AAEnDZOjVoyGAqgJE7Pmn6pyhg-lc0T2SPo');
$admin = "809931622";
function up($ch){ 
return bot('sendChatAction', [
'chat_id' => $ch,
'action' => 'upload_photo',
]);
}
function ty($ch){ 
return bot('sendChatAction', [
'chat_id' => $ch,
'action' => 'typing',
]);
}
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
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
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$mid = $message->message_id;
$cid = $message->chat->id;
$tx = $message->text;
$photo_id=$message->photo[1]->file_id;

$joy = file_get_contents("pechat/$cid.joy");
$step = file_get_contents("pechat/$cid.step");

$button = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"↖️"],['text'=>"⬆️"],['text'=>"↗️"],],
[['text'=>"↙️"],['text'=>"⬇️"],['text'=>"↘️"],],
]
]);

if($tx == "/start" or $tx == "/help"){ 
ty($cid);
mkdir("pechat");
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"😊**Salom**, `men sizga suratlarga o'z pechatingizni qo'yishizga yordam beraman. Buning uchun oldin sozlab oling...`\nIltimos o'z pechat logotipizni **fayl** ko'rinishida yuboring, rasm emas **fayl** ko'rinishida!",
'parse_mode'=>'markdown',
]);
file_put_contents("pechat/$cid.step", "logo");
$baza = file_get_contents("pechat.dat");
if(mb_stripos($baza, $cid) !== false){
}else{
file_put_contents("pechat.dat", "$baza\n$cid");
}
}

$doc=$message->document;
$doc_id=$doc->file_id;
if(isset($message->document) and $step == "logo"){
ty($cid);
$url = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY.'/getFile?file_id='.$doc_id),true);
$path=$url['result']['file_path'];
$file = 'https://api.telegram.org/file/bot'.API_KEY.'/'.$path;
$type = strtolower(strrchr($file,'.')); 
$type=str_replace('.','',$type);
$okey = file_put_contents("pechat/$cid.ph.$type",file_get_contents($file));
if($okey){
file_put_contents("pechat/$cid.step", "joy");
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"**Fayl** yuklab olindi. Endi logotip joylashuvini tanlang.",
'parse_mode'=>'markdown',
'reply_markup'=>$button,
]);
}else{
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"Xatolik #1. Iltimos shu xabarni @UzbekGuy'ga yuboring!",
]);
}
}

if($step == "joy"){
if($tx == "Ortga"){
}else{
ty($cid);
$tx = str_replace("↖️","1",$tx);
$tx = str_replace("⬆️","2",$tx);
$tx = str_replace("↗️","3",$tx);
$tx = str_replace("↙️","4",$tx);
$tx = str_replace("⬇️","5",$tx);
$tx = str_replace("↘️","6",$tx);
file_put_contents("pechat/$cid.joy", $tx);
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"👍Endi bemalol rasmlarni yuborishni boshlashingiz mumkin!",
'parse_mode'=>'markdown',
]);
unlink("pechat/$cid.step");
}
}

if(isset($message->photo) and $joy){
$url = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY.'/getFile?file_id='.$photo_id),true);
$path=$url['result']['file_path'];
$file = 'https://api.telegram.org/file/bot'.API_KEY.'/'.$path;
$okey = file_put_contents("pechat/$cid.pic.png",file_get_contents($file));
$type = strtolower(strrchr($file,'.')); 
$type=str_replace('.','',$type);
if($okey){
if($type == "jpg") $image = imagecreatefromjpeg("pechat/$cid.pic.png");
else if($type == "png") $image = imagecreatefrompng("pechat/$cid.pic.png");
$image_width = imagesx($image);
$image_height = imagesy($image);
$get = glob("poster/$cid.ph.*");
$tyu= strtolower(strrchr($get[0],'.')); 
$tyu=str_replace('.','',$tyu);
if($tyu == "jpg") $logo = imagecreatefromjpeg("pechat/$cid.ph.jpg");
else if($tyu == "png") $logo = imagecreatefrompng("pechat/$cid.ph.png");
$logo_width = imagesx($logo);
$logo_height = imagesy($logo);
$p = file_get_contents("pechat/$cid.joy");
if($p == "1"){
// tep o'rta
$image_y = $image_height / 100 - 10;
$image_x = $image_width / 2 - 50; 
}
else if($p == "2"){
// pas o'rta
$image_x = $image_width / 2 - 50; 
$image_y = $image_height - $logo_height;
}
else if($p == "3"){
// chap pas
$image_y = $image_height - $logo_height;
$image_x = 0;
}
else if($p == "4"){
// chap tepa
$image_y = 0;
$image_x = $image_width /100;
}
else if($p == "5"){
// o'ng tepa
$image_y = 0;
$image_x = $image_width - $logo_width;
}
else if($p == "6"){
// o'ng pas
$image_y = $image_height - $logo_height;
$image_x =  $image_width - $logo_width;
}
$logo_boyi = $logo_width*2/3+10;
$logo_uzun = $logo_height*2/3+10;
imagecopy($image, $logo, $image_x, $image_y, 0, 0, $logo_boyi, $logo_uzun);
imagedestroy($logo);
imagejpeg($image, "pechat/$cid.pic.png");
imagedestroy($image);
up($cid);
bot('sendPhoto',[
'chat_id'=>$cid,
'photo'=>new CURLFile("pechat/$cid.pic.png"),
'caption'=>"$type 😊Bizni do'stlaringizga taklif qiling. Ze'ro ularga ham bu botni foydasi tegsin!",
]);
unlink("pechat/$cid.pic.png");
unlink("pechat/$cid.step");
}else{
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"Xatolik #2. Iltimos shu xabarni @UzbekGuy'ga yuboring!",
]);
}
}

if($text1 == "/stat"){
$baza = file_get_contents("pechat.dat");
$obsh = substr_count($baza,"\n");
$gruppa = substr_count($baza,"-");
$lichka = $obsh - $gruppa;

     bot('sendMessage',[
     'chat_id'=>$cid,
     'text'=>"📈Bot foydalanuvchilari:\n👤 $lichka odamlar,\n👥 $gruppa guruxlar.\n🕵️Hammasi bo'lib: $obsh\n\n".date("Y-m-d H:i:s", strtotime('5 hour'))."\n\n©2017-".date("Y"),
     'parse_mode'=>'markdown',
     ]);
}
?>