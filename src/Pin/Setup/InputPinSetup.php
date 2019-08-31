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

namespace TASoft\GPIO\Pin\Setup;

/**
 * If you use an interrupted kind to trigger inputs, there is one problem:
 * For example a switch is used to trigger an input means, while the switch is not pressed, the input pin is "floating",
 * it does not have a specified value.
 * With the resistors you may solve this problem and specify a "default" value.
 *
 * @package TASoft\GPIO\Pin\Setup
 */
class InputPinSetup extends PinSetup
{
    /** @var int sets a 10k resistor to 3.3v */
    const RESISTOR_PULL_UP = 1;
    /** @var int sets a 10k resistor to ground */
    const RESISTOR_PULL_DOWN = -1;
    /** @var int removes any resistor */
    const RESISTOR_NONE = 0;

    /**
     * Sets a resistor to the input(s)
     * @param int $resistor
     */
    public function setResistor(int $resistor = self::RESISTOR_NONE) {
        $this->options["RESISTOR"] = $resistor;
    }

    /**
     * @return int
     */
    public function getResistor(): int {
        return $this->options["RESISTOR"] ?? self::RESISTOR_NONE;
    }
}