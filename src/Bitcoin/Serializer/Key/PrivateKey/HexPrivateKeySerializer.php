<?php

namespace Afk11\Bitcoin\Serializer\Key\PrivateKey;

use Afk11\Bitcoin\Buffer;
use Afk11\Bitcoin\Parser;
use Afk11\Bitcoin\Key\PrivateKey;
use Afk11\Bitcoin\Key\PrivateKeyInterface;
use Afk11\Bitcoin\Math\Math;
use Mdanter\Ecc\GeneratorPoint;

class HexPrivateKeySerializer
{
    /**
     * @var Math
     */
    private $math;

    /**
     * @var GeneratorPoint
     */
    private $generator;

    /**
     * @param Math $math
     * @param GeneratorPoint $G
     */
    public function __construct(Math $math, GeneratorPoint $G)
    {
        $this->math = $math;
        $this->generator = $G;
    }

    /**
     * @param PrivateKeyInterface $privateKey
     * @return Buffer
     */
    public function serialize(PrivateKeyInterface $privateKey)
    {
        $multiplier = $privateKey->getSecretMultiplier();
        $hex = str_pad($this->math->decHex($multiplier), 64, '0', STR_PAD_LEFT);
        $out = Buffer::hex($hex);
        return $out;
    }

    /**
     * @param Parser $parser
     * @return PrivateKey
     */
    public function fromParser(Parser & $parser)
    {
        $bytes = $parser->readBytes(32);
        return $this->parse($bytes->serialize('hex'));
    }
    /**
     * @param $string
     * @return PrivateKey
     */
    public function parse($string)
    {
        $multiplier = $this->math->hexDec($string);
        $privateKey = new PrivateKey($this->math, $this->generator, $multiplier, false);
        return $privateKey;
    }
}