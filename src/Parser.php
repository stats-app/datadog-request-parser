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
        $metrics = array_merge( $this->parseMetrics( $requestArray['metrics'] ), $this->getMetricsFromSystemInfo( $requestArray ) );

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
     * @param $requestArray
     * @return Metric[]
     */
    private function getMetricsFromSystemInfo($requestArray)
    {
        $systemInfoRegexes = [
            'cpu.*',
            'mem.*',
        ];
        $regex = implode('|', $systemInfoRegexes );
        $timestamp = (int)$requestArray['collection_timestamp'];
        $metrics = [];

        foreach ( $requestArray as $key => $value ) {
           if ( preg_match("/$regex/", $key ) ) {
               $metrics[] = new Metric($key, $value, 'gauge', $timestamp );
           }
        }
        return $metrics;
    }
}