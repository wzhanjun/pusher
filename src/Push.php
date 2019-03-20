<?php

namespace Wzhanjun\Push;

use Wzhanjun\Push\Support\Config;
use Wzhanjun\Push\Contracts\GatewayInterface;
use Wzhanjun\Push\Exceptions\GatewayErrorException;

class Push
{

    protected $config;

    protected $defaultGateway;

    protected $gateways = [];

    public function __construct(array $config)
    {
        $this->config  = new Config($config);

        if (!empty($this->config->get('default')))
        {
            $this->setDefaultGateway($this->config->get('default'));
        }
    }


    /**
     * create gateway
     *
     * @param null $name
     * @return GatewayInterface
     * @throws GatewayErrorException
     */
    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();

        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }


    /**
     *  create gateway
     *
     * @param $name
     * @return mixed
     * @throws GatewayErrorException
     */
    public function createGateway($name)
    {
        $className = $this->formatGatewayClassName($name);
        $gateway   = $this->makeGateway($className, $this->config->get("pusher.{$name}", []));

        if (!($gateway instanceof GatewayInterface))
        {
            throw new GatewayErrorException(sprintf('Gateway "%s" not inherited from %s.', $name, GatewayInterface::class));
        }

        return $gateway;
    }


    /**
     * make gateway
     *
     * @param $gateway
     * @param $config
     * @return mixed
     * @throws GatewayErrorException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway))
        {
            throw new GatewayErrorException(sprintf('Gateway "%s" not exists.', $gateway));
        }

        return new $gateway($config);
    }


    /**
     * get default gateway
     *
     * @return mixed
     * @throws GatewayErrorException
     */
    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway))
        {
            throw new GatewayErrorException("No default gateway configured.");
        }

        return $this->defaultGateway;
    }


    /**
     * set default gateway
     *
     * @param $name
     * @return $this
     */
    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;

        return $this;
    }


    protected function formatGatewayClassName($name)
    {
        if (class_exists($name))
        {
            return $name;
        }

        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__."\\Gateways\\{$name}";
    }
}