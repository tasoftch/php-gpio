<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace TASoft\GPIO\Info;


use TASoft\GPIO\GPIOInterface;

class PinInfo implements PinInfoInterface
{
    /** @var int */
    private $boardPinNumber;
    /** @var int */
    private $BCMPinNumber;
    /** @var int */
    private $wiredPinNumber;
    /** @var GPIOInterface */
    private $GPIO;
    /** @var string */
    private $name;
    /** @var int */
    private $modes;

    /**
     * PinInfo constructor.
     * @param int $boardPinNumber
     * @param int $BCMPinNumber
     * @param int $wiredPinNumber
     * @param GPIOInterface $GPIO
     * @param string $name
     * @param int $modes
     */
    public function __construct(int $boardPinNumber, int $BCMPinNumber, int $wiredPinNumber, GPIOInterface $GPIO, string $name, int $modes)
    {
        $this->boardPinNumber = $boardPinNumber;
        $this->BCMPinNumber = $BCMPinNumber;
        $this->wiredPinNumber = $wiredPinNumber;
        $this->GPIO = $GPIO;
        $this->name = $name;
        $this->modes = $modes;
    }


    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getModes(): int
    {
        return $this->modes;
    }

    /**
     * @inheritDoc
     */
    public function isChangeable(): bool
    {
        return $this->getModes() & self::MODE_GPIO ? true : false;
    }


    /**
     * @inheritDoc
     */
    public function getBoardPinNumber(): int
    {
        return $this->boardPinNumber;
    }

    /**
     * @inheritDoc
     */
    public function getBCMPinNumber(): int
    {
        return $this->BCMPinNumber;
    }

    /**
     * @inheritDoc
     */
    public function getWiredPinNumber(): int
    {
        return $this->wiredPinNumber;
    }

    /**
     * @inheritDoc
     */
    public function getGPIO(): GPIOInterface
    {
        return $this->GPIO;
    }

    /**
     * Debug output
     *
     * @return array
     */
    public function __debugInfo()
    {
        $info = [
            'BOARD' => $this->boardPinNumber,
            'BCM' => $this->BCMPinNumber,
            "WPI" => $this->wiredPinNumber,
            'Name' => $this->name
        ];

        $modes = [];
        foreach([
                    self::MODE_GPIO => 'GPIO',
                    self::MODE_GROUND => 'GROUND',
                    self::MODE_5V => '5v',
                    self::MODE_33V => '3.3v',
                    self::MODE_I2C => 'I2C',
                    self::MODE_UART => 'UART',
                    self::MODE_SPI => 'SPI'
                ] as $mode => $name) {
            if($this->modes & $mode)
                $modes[] = $name;
        }
        $info["Modes"] = implode(" ", $modes);

        return $info;
    }
}