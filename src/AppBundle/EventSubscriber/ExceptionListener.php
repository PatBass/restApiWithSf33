<?php

namespace AppBundle\EventSubscriber;


use AppBundle\Normalizer\NormalizerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener extends EventSubscriberInterface
{
    /**
     * @var array
     */
    private $normalizers;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ExceptionListener constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    public static function getSubscribedEvents()
    {
        return [
            kernelEvents::EXCEPTION => [["processException", 255]]
        ];
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $result = null;

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($event->getException())) {
                $result = $normalizer->normalize($event->getException());
            }
        }

        if (null == $result) {

            $result['code'] = Response::HTTP_BAD_REQUEST;

            $result['body'] = [
                'code' => Response::HTTP_BAD_REQUEST,
                'body' => $event->getException()->getMessage()
            ];
        }

        $body = $this->serializer->serialize($result['body'], 'json');

        $event->setResponse(new Response($body, $result['code']));
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
}