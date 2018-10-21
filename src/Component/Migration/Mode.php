<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:51
 */

namespace Afw\Component\Migration;


final class Mode
{
    public const NEXT_MODE = 'next';
    public const PREV_MODE = 'prev';

    private $mode = self::NEXT_MODE;

    private $availableModes = [self::NEXT_MODE, self::PREV_MODE];

    public function __construct(string $mode)
    {
        if (!in_array($mode, $this->availableModes)) {
            throw new \RuntimeException('Invalid mode');
        }

        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }
}