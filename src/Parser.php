<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 1:54 PM
 */
namespace TomVerran\DataDog;
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
            list( $name, $value,, $metaData ) = $metric;
            $outMetrics[] = new Metric( $name, $value, $metaData['type'] );
        }
        return $outMetrics;
    }
}