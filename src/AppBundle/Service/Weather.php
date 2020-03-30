<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 22/02/19
 * Time: 09:21
 */

namespace AppBundle\Service;


use GuzzleHttp\ClientInterface;
use JMS\Serializer\Serializer;
use Symfony\Bridge\Monolog\Logger;

class Weather
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ClientInterface
     */
    private $weatherClient;

    /**
     * @var Logger
     */
    private $logger;

    private $apiKey;

    /**
     * Weather constructor.
     * @param Serializer $serializer
     * @param ClientInterface $weatherClient
     * @param Logger $logger
     * @param $apiKey
     */
    public function __construct(Serializer $serializer, ClientInterface $weatherClient, Logger $logger, $apiKey)
    {
        $this->serializer = $serializer;
        $this->weatherClient = $weatherClient;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
    }


    public function getCurrentWeatherData()
    {
        $uri = "/data/2.5/weather?q=Paris&APPID=".$this->apiKey;

        try {
            $response = $this->weatherClient->get($uri);
        } catch (\Exception $e) {
            $this->logger->error("The weather API returned an error: ".$e->getMessage());
            return ['error' => 'Le service des données météos en temps réel est indisponible'];
        }

        $data = $this->serializer->deserialize($response->getbody()->getcontents(), 'array', 'json');

         return [
             "city" => $data['name'],
             "description" => $data['weather'][0]["main"]
         ];
    }

}