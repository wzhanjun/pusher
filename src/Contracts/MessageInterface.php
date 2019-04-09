<?php


namespace Wzhanjun\Push\Contracts;

/**
 *
 * @method setMessageType()
 * @method getMessageType()
 *
 *
 * @method setTitle($string)
 * @method string getTitle()
 *
 * @method setBody($string)
 * @method string getBody()
 *
 * @method setIsOffline($bool)
 * @method bool getIsOffline()
 *
 * @method setOfflineExpireTime()
 * @method getOfflineExpireTime()
 *
 * @method isBell()
 * @method setIsBell()
 *
 * @method setIsVibration()
 * @method isVibration()
 *
 * @method getPayload()
 * @method setPayload()
 *
 * @method isClearAble()
 * @method setIsClearAble()
 *
 * @method getExtra($key)
 * @method setExtra($key, $val)
 *
 * @method isVoIp()
 * @method setIsVoIP()
 *
 * @method getVoIPPayload()
 * @method setVoIPPayload()
 *
 * Interface MessageInterface
 * @package Wzhanjun\Push\Contracts
 */
interface MessageInterface
{
    /**
     * pushType
     */
    const MESSAGE_TYPE_NOTICE          = 1;
    const MESSAGE_TYPE_NOTY_POP_LOAD   = 2;
    const MESSAGE_TYPE_LINK            = 3;
    const MESSAGE_TYPE_TRANSMISSION    = 4;

}