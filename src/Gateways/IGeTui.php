<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\MessageInterface as Message;
use Wzhanjun\Push\Support\Config;

class IGeTui implements GatewayInterface
{

    protected $config;

    protected $url;

    protected $appKey;

    protected $appId;

    protected $masterSecret;

    protected $android;

    protected $ios;

    /**
     * Send device type.
     *
     * @var
     */
    protected $deviceType;


    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->android = $this->config->get('android');

        $this->ios = $this->config->get('ios');
    }

    /**
     * device type
     *
     * @param $deviceType
     * @return $this
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }


    public function send(Message $message)
    {
        return 'igetui send';
    }

    public function setSendTo($method, $value = true)
    {
        switch ($method)
        {
            case 'device';
            break;
        }
    }


    public function __call($method, $arguments)
    {
        $methods = ['setDevice', 'setAccount', 'setAlias', 'setTag', 'setAll'];

        if (in_array($method, $methods))
        {
            $this->setSendTo(lcfirst(substr($method, 3)), $arguments);
        }

        return $this;
    }


}