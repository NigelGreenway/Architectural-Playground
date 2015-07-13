<?php

namespace Demo\Domain\Core;

/**
 * Class description
 *
 * @package Demo\Domain\Core
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class EmailAddress
{
    private $value;

    private function __construct($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new \Exception(sprintf('[%s] is not a valid ema il address'));
        }
        $this->value = $value;
    }

    public static function fromNative($value)
    {
        return new self($value);
    }

    public function sameAs(EmailAddress $object)
    {
        return (string) $this == (string) $object;
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}