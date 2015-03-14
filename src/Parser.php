<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 1:54 PM
 */
namespace TomVerran\DataDog;
use TomVerran\Stats\Metric;

class Parser
{
    /**
     * Parse a request from a datadog agent
     * @param string $request The JSON request data
     * @return Request
     */
    public function parse( $request )
    {
        $requestArray = json_decode( $request, true );
        $metrics = $this->parseMetrics( $requestArray['metrics'] );
        $firstMetric = reset( $metrics );

        $time = $firstMetric->getTimestamp();
        $metrics = array_merge( $metrics, $this->getMetricsFromSystemInfo( $requestArray, $time ) );

        $requestObject = new Request;
        $requestObject->setMetrics( $metrics );
        return $requestObject;
    }

    /**
     * Given an array of metrics, get Metric objects
     * @param array $metrics
     * @return Metric[]
     */
    protected function parseMetrics( $metrics )
    {
        $outMetrics = [];
        foreach( $metrics as $metric ) {
            list( $name, $time, $value, $metaData ) = $metric;
            $outMetrics[] = new Metric( $name, $value, $metaData['type'], $time );
        }
        return $outMetrics;
    }

    /**
     * Get metrics from system info
     * @param array $requestArray
     * @param int $timestamp
     * @return Metric[]
     */
    private function getMetricsFromSystemInfo( $requestArray, $timestamp )
    {
        $systemInfoRegexes = [
            'cpu.*',
            'mem.*',
        ];
        $regex = implode('|', $systemInfoRegexes );
        $metrics = [];

        foreach ( $requestArray as $key => $value ) {
           if ( preg_match("/$regex/", $key ) ) {
               $metrics[] = new Metric($key, $value, 'gauge', $timestamp );
           }
        }
        return $metrics;
    }
}