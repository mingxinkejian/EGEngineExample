<?php
namespace Application\Common;
/*
 * 上下文 包含当前用户uid， 内部通信local_ip local_port socket_id ，以及客户端client_ip client_port
 * 
 */
class UserContext
{
    /**
     * 内部通讯id
     * @var string
     */
    public static $local_ip;
    /**
     * 内部通讯端口
     * @var int
     */
    public static $local_port;
    /**
     * 客户端ip
     * @var string
     */
    public static $client_ip;
    /**
     * 客户端端口
     * @var int
     */
    public static $client_port;
    
    /**
     * socket连接文件描述符
     * @var unknown
     */
    public static $fd;
    /**
     * 用户id
     * @var int
     */
    public static $uid;
    
    /**
     * 编码session
     * @param mixed $session_data
     * @return string
     */
    public static function sessionEncode($session_data = '')
    {
        if($session_data !== '')
        {
            return json_encode($session_data);
        }
        return '';
    }
    
    /**
     * 解码session
     * @param string $session_buffer
     * @return mixed
     */
    public static function sessionDecode($session_buffer)
    {
        return json_decode($session_buffer, true);
    }
    
    /**
     * 清除上下文
     * @return void
     */
    public static function clear()
    {
        self::$local_ip = self::$local_port = self::$client_ip = self::$client_port = self::$fd = self::$uid  = null;
    }
}
