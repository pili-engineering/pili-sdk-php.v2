<?php

namespace Qiniu\Pili;

use \Qiniu\Pili\Utils;

class RoomClient
{
    private $_transport;
    private $_mac;

    public function __construct($mac)
    {
        $this->_mac = $mac;
        $this->_transport = new Transport($mac);
    }

    /*
     * ownerId: 要创建房间的所有者
     * roomName: 房间名称
     * Version:连麦的版本号
     */
    public function createRoom($ownerId, $roomName = NULL,$Version="v2")
    {
        $url = Config::getInstance()->RTCAPI_HOST . sprintf("/%s/rooms",$Version);
        $params['owner_id'] = $ownerId;
        if (!empty($roomName)) {
            $params['room_name'] = $roomName;
        }
        $body = json_encode($params);
        try {
            $ret = $this->_transport->send(HttpRequest::POST, $url, $body);
        } catch (\Exception $e) {
            return $e;
        }

        return $ret;
    }

    /*
     * roomName: 房间名称
     * Version:连麦的版本号
     */
    public function getRoom($roomName,,$Version="v2")
    {
        $url = sprintf("%s/%s/rooms/%s", Config::getInstance()->RTCAPI_HOST, $Version,$roomName);
        try {
            $ret = $this->_transport->send(HttpRequest::GET, $url);
        } catch (\Exception $e) {
            return $e;
        }
        return $ret;
    }

    /*
     * roomName: 房间名称
     * Version:连麦的版本号
     */
    public function deleteRoom($roomName,$Version="v2")
    {
        $url = sprintf("%s/%s/rooms/%s", Config::getInstance()->RTCAPI_HOST,$Version ,$roomName);
        try {
            $ret = $this->_transport->send(HttpRequest::DELETE, $url);
        } catch (\Exception $e) {
            return $e;
        }
        return $ret;
    }
        /*
     * 获取房间的人数
      * roomName: 房间名称
      * Version:连麦的版本号
    */
    public function getRoomUserNum($roomName,$Version="v2"){
        $url = sprintf("%s/%s/rooms/%s/users", Config::getInstance()->RTCAPI_HOST,$Version, $roomName);
        try {
            $ret = $this->_transport->send(HttpRequest::GET, $url);
        } catch (\Exception $e) {
            return $e;
        }
        return $ret;

    }
    /*
     * 踢出玩家
    * roomName: 房间名称
    * userId: 请求加入房间的用户ID
    * Version:连麦的版本号
  */
public function kickingPlayer($roomName,$UserId,$Version="v2"){
    $url = sprintf("%s/%s/rooms/%s/users/%s", Config::getInstance()->RTCAPI_HOST,$Version, $roomName,$UserId);
    try {
        $ret = $this->_transport->send(HttpRequest::DELETE, $url);
    } catch (\Exception $e) {
        return $e;
    }
    return $ret;

}
    /*
     * roomName: 房间名称
     * userId: 请求加入房间的用户ID
     * perm: 该用户的房间管理权限，"admin"或"user"，房间主播为"admin"，拥有将其他用户移除出房间等特权。
     * expireAt: int64类型，鉴权的有效时间，传入秒为单位的64位Unix时间，token将在该时间后失效。
     */
    public function roomToken($roomName, $userId, $perm, $expireAt)
    {
        $params['room_name'] = $roomName;
        $params['user_id'] = $userId;
        $params['perm'] = $perm;
        $params['expire_at'] = $expireAt;

        $roomAccessString = json_encode($params);

        $encodedRoomAccess = Utils::base64UrlEncode($roomAccessString);
        $sign = hash_hmac('sha1', $encodedRoomAccess, $this->_mac->_secretKey, true);
        $encodedSign = Utils::base64UrlEncode($sign);
        return $this->_mac->_accessKey . ":" . $encodedSign . ":" . $encodedRoomAccess;
    }
}