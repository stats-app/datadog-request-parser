<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 2:04 PM
 */

namespace TomVerran\DataDog;
use TomVerran\Stats\Metric;

class Request
{
    private $metrics = [];

    /**
     * @param Metric[] $metrics
     */
    public function setMetrics( $metrics )
    {
        $this->metrics = $metrics;
    }

    /**
     * @return Metric[]
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}