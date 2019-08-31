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

namespace TASoft\GPIO\Pin;


use TASoft\GPIO\AbstractGPIO;

class PWMPin extends Pin implements PWMPinInterface
{
    private $value;

    /**
     * @inheritDoc
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function setValue(float $value = 0.0): void
    {
        $gpio = $this->getGPIO();
        if($gpio instanceof AbstractGPIO) {
            $this->value = min(1.0, max(0.0, $value));
            $gpio->callGPIO((sprintf("gpio -g pwm %d %d", $this->getPinNumber(), round($this->value * 1023))));
        }
    }
}