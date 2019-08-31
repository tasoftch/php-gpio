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

namespace TASoft\GPIO;


use TASoft\GPIO\Info\PinInfoInterface;
use TASoft\GPIO\Pin\InputPinInterface;
use TASoft\GPIO\Pin\Setup\MultiplePinSetupInterface;
use TASoft\GPIO\Pin\Setup\PinSetupInterface;
use TASoft\RPi\Exception\InvalidPinNumberException;
use TASoft\GPIO\Pin\PinInterface;

interface GPIOInterface
{
    /** @var int The physical pin numbers on board */
    const GPIO_NS_BOARD      = 0;

    /** @var int The Broadcom SOC bord numbers */
    const GPIO_NS_BCM        = 1;

    /** @var int The GPIO board numbers */
    const GPIO_NS_WIRED      = 2;


    const GPIO_EDGE_FALLING = 1;
    const GPIO_EDGE_RISING = 2;
    const GPIO_EDGE_BOTH = 3;



    /**
     * Converts a pin number from a number system into another
     *
     * @param int $pinNumber
     * @param int $from
     * @param int $to
     * @return int
     * @throws InvalidPinNumberException
     */
    public function convertPinNumber(int $pinNumber, int $from = NULL, int $to = self::GPIO_NS_BOARD): int;

    /**
     * Gets information about a pin number on your raspberry pi
     *
     * @param int $pinNumber
     * @param int|NULL $ns
     * @return PinInfoInterface
     * @throws InvalidPinNumberException
     */
    public function getPinInformation(int $pinNumber, int $ns = NULL): PinInfoInterface;

    /**
     * Makes the pin in setup available under the given settings.
     *
     * @param PinSetupInterface $pinSetup
     * @return PinInterface
     */
    public function activatePin(PinSetupInterface $pinSetup): PinInterface;

    /**
     * Same as setupPin but allows to setup multiple pins at once.
     *
     * @param MultiplePinSetupInterface $pinSetup
     * @return PinInterface[]
     */
    public function activatePins(MultiplePinSetupInterface $pinSetup): array;

    /**
     * Resets the pin to default and removes it from PHP scope.
     * Note: Please do not use the PinInterface object anymore after that action!
     *
     * @param PinInterface $pin
     * @return bool
     */
    public function resetPin(PinInterface $pin): bool;

    /**
     * A Pi instance should know which pins are exported for PHP.
     * So a call of this method should reset all pins and invalidate existing PinInterface objects!
     *
     * @return bool
     */
    public function resetAll(): bool;


    /**
     * Adds a callback for detecting edges on an input.
     * NOTE: The edge detection works only if the php extension PCNTL is installed and enabled!
     * NOTE: This call will fork the current process! That means the callback will not be able to obtains variables you change from now!
     *       You need to pass everything the callback needs into it before registration!
     *
     * @param int $edge
     * @param InputPinInterface $pin
     * @param callable $callback
     * @return bool
     */
    public function registerEdge(int $edge, InputPinInterface $pin, callable $callback): bool;

    /**
     * @param InputPinInterface $pin
     * @return bool
     */
    public function unregisterEdge(InputPinInterface $pin): bool;

    /**
     * If you are using edge detection you MUST either call this method or unregister each registered pin!
     * This method kills all forked processes listening on edges
     * If you don't do that, the edge detection will continue even if the main process exits!
     *
     * @return bool
     */
    public function unregisterAllEdges(): bool;

    /**
     * Get the model name
     *
     * @return string
     */
    public function getModel(): string;

    /**
     * Gets the revision
     *
     * @return string
     */
    public function getRevision(): string;

    /**
     * Gets the hardware version
     * @return string
     */
    public function getHardware(): string;

    /**
     * Gets the serial of this model
     * @return string
     */
    public function getSerial(): string;
}