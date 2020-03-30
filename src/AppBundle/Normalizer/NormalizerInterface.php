<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 26/02/19
 * Time: 21:48
 */

namespace AppBundle\Normalizer;


interface NormalizerInterface
{
    public function normalize(\Exception $exception);

    public function supports(\Exception $exception);
}