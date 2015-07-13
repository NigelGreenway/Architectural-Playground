<?php


namespace Demo\Domain\Customer;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerID
{
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function fromNative($value)
    {
        return new self($value);
    }

    public static function generate()
    {
        return new self(rand(1,1000));
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}