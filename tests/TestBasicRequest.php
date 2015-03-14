<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 1:58 PM
 */
namespace TomVerran\DataDog;
use TomVerran\Stats\Metric;

class TestBasicRequest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Set up test fixtures
     */
    public function setUp()
    {
        $parser = new Parser;
        $this->request = $parser->parse( file_get_contents( 'requests/basic.json' ) );
    }

    /**
     * We should have a Request object
     */
    public function testGetsRequestObject()
    {
        $this->assertInstanceOf( Request::class, $this->request );
    }

    /**
     * We should have picked up nine metrics
     */
    public function testRequestHasMoreThanNineMetrics()
    {
        $this->assertGreaterThan( 9, $this->request->getMetrics() );
    }

    /**
     * Make sure the metrics have the right names, types and values
     */
    public function testMetricsHaveCorrectNamesTypesAndValues()
    {
        $expectedNames = [
            "system.net.packets_out.error",
            "system.net.bytes_sent"
        ];

        $expectedValues = [
            0.0,
            318.0
        ];

        /** @var Metric[] $metrics */
        $metrics = array_slice( $this->request->getMetrics(), 0, 2 );

        foreach ( $metrics as $metric ) {
            $expectedName = array_shift( $expectedNames );
            $expectedValue = array_shift( $expectedValues );
            $this->assertEquals( $expectedName, $metric->getName() );
            $this->assertEquals( $expectedValue, $metric->getValue() );
            $this->assertEquals( 'gauge', $metric->getType() );
        }
    }

    public function testMemoryStatsRecorded()
    {
        $metricArray = $this->getIndexedMetrics();

        $this->assertEquals( 'gauge', $metricArray['memPhysUsed']->getType() );
        $this->assertEquals( 6373, $metricArray['memPhysUsed']->getValue() );

        $this->assertEquals( 'gauge', $metricArray['memPhysFree']->getType() );
        $this->assertEquals( 5766, $metricArray['memPhysFree']->getValue() );


        $this->assertEquals( 'gauge', $metricArray['cpuIdle']->getType() );
        $this->assertEquals( 96.68, $metricArray['cpuIdle']->getValue() );
    }

    public function testAllMetricsHaveSameTimestamp()
    {
        $metrics = $this->getIndexedMetrics();
        $expectectedTimestamp = reset( $metrics )->getTimestamp();

        foreach ( $metrics as $metric ) {
            $this->assertEquals( $expectectedTimestamp, $metric->getTimestamp() );
        }
    }

    /**
     * @return \TomVerran\Stats\Metric[]
     */
    public function getIndexedMetrics()
    {
        $metrics = $this->request->getMetrics();
        /** @var Metric[] $metricArray */
        $metricArray = [];

        foreach ($metrics as $metric) {
            $metricArray[$metric->getName()] = $metric;
        }
        return $metricArray;
    }

} 