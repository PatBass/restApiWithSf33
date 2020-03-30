<?php

namespace AppBundle\Normalizer;


abstract class AbstractNormalizer implements NormalizerInterface
{
    private $exceptionType;

    /**
     * AbstractNormalizer constructor.
     * @param $exceptionType
     */
    public function __construct($exceptionType)
    {
        $this->exceptionType = $exceptionType;
    }


    public function normalize(\Exception $exception)
    {
        // TODO: Implement normalize() method.
    }

    public function supports(\Exception $exception)
    {
        return in_array(get_class($exception), $this->exceptionType);
    }
}