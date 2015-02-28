<?php
/**
 * Created by PhpStorm.
 * User: tom
 * Date: 2/28/15
 * Time: 1:54 PM
 */
namespace TomVerran\DataDog;
class Metric
{
    private $name;
    private $value;
    private $type;

    public function __construct( $name, $value, $type )
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }
}