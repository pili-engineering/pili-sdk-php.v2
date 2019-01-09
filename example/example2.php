<?php

require join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili_v2.php'));

$ak="QzdCUKE0lXmIJsvJ_yQJTeIsJYeK6liEdWAn9JuU";
$sk="ja76Ne-wCvo-YSc88D3TwKM5O3JtBym5isn-YqjN";

$mac = new Qiniu\Pili\Mac($ak, $sk);
$client = new Qiniu\Pili\RoomClient($mac);

try {
    //创建连麦房间
    $resp = $client->createRoom("901", "testroom");
    print_r($resp);
    //获取连麦房间状态
    $resp = $client->getRoom("testroom");
    print_r($resp);
    //获取房间连麦的成员
    $resp=$client->getRoomUserNum("testroom");
    print_r($resp);
    //剔除房间的连麦成员
    $resp=$client->kickingPlayer("testroom", "qiniu-f6e07b78-4dc8-45fb-a701-a9e158abb8e6");
    print_r($resp);
    //鉴权的有效时间: 1个小时.
    $resp = $client->roomToken("testroom", "123", 'admin', (time()+3600));
     print_r($resp);
     //删除房间
    $resp = $client->deleteRoom("testroom");
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
