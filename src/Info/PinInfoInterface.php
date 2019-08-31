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

interface PinInfoInterface
{
    /** @var int Pin is available as GPIO */
    const MODE_GPIO         = 1<<0;

    /** @var int Pin is ground 0v */
    const MODE_GROUND       = 1<<1;

    /** @var int Power pin 3.3v */
    const MODE_33V          = 1<<2;

    /** @var int Power pin 5v */
    const MODE_5V           = 1<<3;

    /** @var int SPI Pin */
    const MODE_SPI          = 1<<4;

    /** @var int I2C Pin */
    const MODE_I2C          = 1<<5;

    /** @var int UART Pin */
    const MODE_UART         = 1<<6;

    /**
     * Returns the physical pin number on the rpi board
     *
     * @return int
     */
    public function getBoardPinNumber(): int;

    /**
     * Returns the Broadcom SOC pin number
     *
     * @return int
     */
    public function getBCMPinNumber(): int;

    /**
     * Returns the GPIO pin number
     *
     * @return int
     */
    public function getWiredPinNumber(): int;

    /**
     * Returns the GPIO instance the pin belongs to
     *
     * @return GPIOInterface
     */
    public function getGPIO(): GPIOInterface;

    /**
     * Returns if the pin is changeable or fixed (eg ground, 5v or 3.3v are not changeable)
     *
     * @return bool
     */
    public function isChangeable(): bool;

    /**
     * Get the rpi label for a pin
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the available modes a pin can accept
     * Modes are combined using bitwise or operator.
     *
     * @return int
     * @see PinInfoInterface::MODE_* constants
     */
    public function getModes(): int;
}