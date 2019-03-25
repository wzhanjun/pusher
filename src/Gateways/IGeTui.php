<?php

namespace Wzhanjun\Push\Gateways;

use Wzhanjun\Push\Support\Config;
use Wzhanjun\Push\Exceptions\Exception;
use Wzhanjun\Push\Gateways\IGeTui\Client;
use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Contracts\TargetInterface as Target;
use Wzhanjun\Push\Contracts\MessageInterface as Message;

class IGeTui implements GatewayInterface
{

    protected $config;

    /**
     * Send device type.
     *
     * @var
     */
    protected $deviceType;


    /**
     * clientConfig
     *
     * @var
     */
    protected $clientConfig;


    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->toClient();
    }

    /**
     * @param Message $message
     * @param Target $target
     * @return mixed|null
     * @throws \Exception
     */
    public function send(Message $message, Target $target)
    {
        $client = new Client($this->clientConfig);

        switch ($target->getPushType())
        {
            case Target::PUSH_TYPE_DEVICE_LIST:
            case Target::PUSH_TYPE_ALIAS_LIST:
                $result = $client->pushMessageToList($message, $target);
                break;
            case Target::PUSH_TYPE_TAGS:
            case Target::PUSH_TYPE_ALL:
                $result = $client->pushMessageToApp($message, $target);
                break;
            case Target::PUSH_TYPE_DEVICE:
            case Target::PUSH_TYPE_ALIAS:
                $result = $client->pushMessageToSingle($message, $target);
                break;
            default:
                throw new Exception(sprintf('PUSH_TYPE %s not support', $target->getPushType()));
        }

        return $result;
    }


    /**
     * 绑定别名
     *
     * @param $alias
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function bindAlias($alias, $deviceId)
    {
        $client = new Client($this->clientConfig);

        return $client->bindAlias($alias, $deviceId);
    }


    /**
     * 查询别名
     *
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function queryAlias($deviceId)
    {
        $client = new Client($this->clientConfig);

        return $client->queryAlias($deviceId);
    }


    /**
     * 获取设备状态
     *
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getClientIdStatus($deviceId)
    {
        $client = new Client($this->clientConfig);

        return $client->getClientIdStatus($deviceId);
    }


    /**
     * 绑定标签
     *
     * @param array $tagList
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function setDeviceTags(array $tagList, $deviceId)
    {
        $client = new Client($this->clientConfig);

        return $client->setDeviceTags($tagList, $deviceId);
    }


    /**
     * @param $deviceId
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getDeviceTags($deviceId)
    {
        $client = new Client($this->clientConfig);

        return $client->getDeviceTags($deviceId);
    }

    /**
     * 获取任务结果
     *
     * @param array $taskList
     * @return mixed|null
     * @throws \Wzhanjun\Igetui\Sdk\RequestException
     */
    public function getPushResultByTaskList(array $taskList)
    {
        $client = new Client($this->clientConfig);

        return $client->getPushResultByTaskList($taskList);
    }


    /**
     * to client
     *
     * @param null $client
     * @return $this
     * @throws Exception
     */
    public function toClient($client = null)
    {
        if (!$client)
        {
            $client = $this->config->get('default_client');
        }

        $config = $this->config->get("clients.{$client}");

        if (empty($config))
        {
            throw new Exception(sprintf("client %s config not exist", $client));
        }

        $this->clientConfig = new Config($config);

        return $this;
    }

}