<?php
require join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili_v2.php'));
$ak = "Ge_kRfuV_4JW0hOCOnRq5_kD1sX53bKVht8FNdd3";
$sk = "0fU92CSrvgNJTVCXqbuRVqkntPFJLFERGa4akpko";
$hubName = "PiliSDKTest";
//创建hub
echo "================Create hub\n";
$mac = new Qiniu\Pili\Mac($ak, $sk);
$client = new Qiniu\Pili\Client($mac);
$hub = $client->hub($hubName);
print_r($hub);
//获取stream
echo "================Get stream\n";
$streamKey = "php-sdk-test" . time();
$stream = $hub->stream($streamKey);
print_r($stream);
try {
    //创建stream
    echo "================Create stream\n";
    $resp = $hub->create($streamKey);
    print_r($resp);
    //获取stream info
    echo "================Get stream info\n";
    $resp = $stream->info();
    print_r($resp);
    //列出所有流
    echo "================List streams\n";
    $resp = $hub->listStreams("php-sdk-test", 1, "");
    print_r($resp);
    //列出正在直播的流
    echo "================List live streams\n";
    $resp = $hub->listLiveStreams("php-sdk-test", 1, "");
    print_r($resp);
    echo "================Batch live streams\n";
    $resp = $hub->batchLiveStatus(array($streamKey, "foo", "bar"));
    print($resp["items"]);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    echo "================Get liveStatus:\n";
    $status = $stream->liveStatus();
    print_r($status);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //禁用流
    echo "================Disable stream:\n";
    $stream->disable(time() + 120);
    $status = $stream->liveStatus();
    echo "liveStatus:\n";
    print_r($status);
    $info = $stream->info();
    echo "info:\n";
    print_r($info);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //启用流
    echo "================Enable stream:\n";
    $stream->enable();
    $status = $stream->liveStatus();
    echo "liveStatus:\n";
    print_r($status);
    $info = $stream->info();
    echo "info:\n";
    print_r($info);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //保存直播数据
    echo "================Save stream:\n";
    $fname = $stream->save(0, time());
    print_r($fname);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //保存直播数据
    echo "================Save stream:\n";
    $resp = $stream->saveas(array("format" => "mp4"));
    print_r($resp);
    $resp = $stream->saveas();
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //查询推流历史
    echo "================Get stream history record:\n";
    $records = $stream->historyActivity(0, 0);
    print_r($records["items"]);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //保存直播截图
    echo "================Save snapshot:\n";
    $resp = $stream->snapshot(array("format" => "jpg"));
    print_r($resp);
    $resp = $stream->snapshot();
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
try {
    //更改流的实时转码规格
    echo "================Update converts:\n";
    $info = $stream->info();
    echo "before update converts. info:\n";
    print_r($info);
    $stream->updateConverts(array("480p", "720p"));
    $info = $stream->info();
    echo "after update converts. info:\n";
    print_r($info);
} catch (\Exception $e) {
    echo "Error:", $e->getMessage(), "\n";
}
//RTMP 推流地址
$url = Qiniu\Pili\RTMPPublishURL("publish-rtmp.test.com", $hubName, $streamKey, 3600, $ak, $sk);
echo $url, "\n";
//RTMP 直播放址
$url = Qiniu\Pili\RTMPPlayURL("live-rtmp.test.com", $hubName, $streamKey);
echo $url, "\n";
//HLS 直播地址
$url = Qiniu\Pili\HLSPlayURL("live-hls.test.com", $hubName, $streamKey);
echo $url, "\n";
//HDL 直播地址
$url = Qiniu\Pili\HDLPlayURL("live-hdl.test.com", $hubName, $streamKey);
echo $url, "\n";
//截图直播地址
$url = Qiniu\Pili\SnapshotPlayURL("live-snapshot.test.com", $hubName, $streamKey);
echo $url, "\n";