<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili_v2.php')));

$ak="vI2xPIjOoh7udcRw4GdYNvf3o_gKsCx9wdZaC9";
$sk="ja76Ne-wCvo-YSc88D3TwKM5O3JtBym5isn-YqjN";

$mac = new Qiniu\Pili\Mac($ak, $sk);
$client = new Qiniu\Pili\RoomClient($mac);

try {
   //创建连麦房间
    $resp = $client->createRoom("901", "testroom","v1");
    print_r($resp);
   //获取连麦房间状态
    $resp = $client->getRoom("testroom","v1");
    print_r($resp);
    //获取房间连麦的成员
    $resp=$client->getRoomUserNum("testroom","v1");
    print_r($resp);
    //剔除房间的连麦成员
    $resp=$client->kickingPlayer("testroom","qiniu-f6e07b78-4dc8-45fb-a701-a9e158abb8e6","v1");
    print_r($resp);
    //鉴权的有效时间: 1个小时.
    $resp = $client->roomToken("testroom", "123", 'admin', (time()+3600,"v1"));
     print_r($resp);
     //删除房间
    $resp = $client->deleteRoom("testroom","v1");
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}
