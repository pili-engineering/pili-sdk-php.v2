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

    public function createRoom($ownerId, $roomName = NULL)
    {
        $url = Config::getInstance()->RTCAPI_HOST . "/v1/rooms";
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

    public function getRoom($roomName)
    {
        $url = sprintf("%s/v1/rooms/%s", Config::getInstance()->RTCAPI_HOST, $roomName);
        try {
            $ret = $this->_transport->send(HttpRequest::GET, $url);
        } catch (\Exception $e) {
            return $e;
        }
        return $ret;
    }

    public function deleteRoom($roomName)
    {
        $url = sprintf("%s/v1/rooms/%s", Config::getInstance()->RTCAPI_HOST, $roomName);
        try {
            $ret = $this->_transport->send(HttpRequest::DELETE, $url);
        } catch (\Exception $e) {
            return $e;
        }
        return $ret;
    }

    public function roomToken($roomName, $userId, $perm, $expireAt)
    {
        $params['room_name'] = $roomName;
        $params['user_id'] = $userId;
        $params['perm'] = $perm;
        $params['expire_at'] = $expireAt * 1000000;

        $roomAccessString = json_encode($params);
        print_r($roomAccessString . "\n");

        $encodedRoomAccess = Utils::base64UrlEncode($roomAccessString);
        $sign = hash_hmac('sha1', $encodedRoomAccess, $this->_mac->_secretKey, true);
        $encodedSign = Utils::base64UrlEncode($sign);
        return $this->_mac->_accessKey . ":" . $encodedSign . ":" . $encodedRoomAccess;
    }
}