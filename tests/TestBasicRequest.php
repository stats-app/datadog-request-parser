<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 1:58 PM
 */
namespace TomVerran\DataDog;

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
    public function testRequestHasNineMetrics()
    {
        $this->assertCount( 9, $this->request->getMetrics() );
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
            1425131744,
            1425131744
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
} 