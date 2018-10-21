<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 21:04
 */

namespace Tests\Afw\Component\Reflection;


class TestStub
{
    private $val1;
    private $val2;

    /**
     * TestStub constructor.
     * @param $val1
     * @param $val2
     */
    public function __construct($val1, $val2)
    {
        $this->val1 = $val1;
        $this->val2 = $val2;
    }

    /**
     * @return mixed
     */
    public function getVal1()
    {
        return $this->val1;
    }

    /**
     * @return mixed
     */
    public function getVal2()
    {
        return $this->val2;
    }
}