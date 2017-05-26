<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili_v2.php')));

$ak="yyQzdCUKE0lXmIJsvJ_yQJTeIsJYeK6liEdWAn9JuU";
$sk="HwIhvaYrUZ4pedSRTpKZsqunSty0uUpQdrFDGLGU";

$mac = new Qiniu\Pili\Mac($ak, $sk);
$client = new Qiniu\Pili\RoomClient($mac);

try {
   
    $resp = $client->createRoom("901", "testroom");
    print_r($resp);

    $resp = $client->getRoom("testroom");
    print_r($resp);

    $resp = $client->deleteRoom("testroom");
    print_r($resp);

    $resp=$client->getRoomUserNum("testroom");
    print_r($resp);
    $resp=$client->kickingPlayer("testroom","qiniu-f6e07b78-4dc8-45fb-a701-a9e158abb8e6");
    print_r($resp);
    //鉴权的有效时间: 1个小时.
    $resp = $client->roomToken("testroom", "123", 'admin', (time()+3600));

    print_r($resp);
     $resp=$client->kickingPlayer("testroom","qiniu-f6e07b78-4dc8-45fb-a701-a9e158abb8e6");
     print_r($resp);
    $resp3=$client->getRoomUserNum("testroom");
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}
