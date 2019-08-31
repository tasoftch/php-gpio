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


interface PinSetupInterface
{
    /** @var int pin setup as input */
    const SETUP_PIN_INPUT = 0;

    /** @var int pin setup as output */
    const SETUP_PIN_OUTPUT = 1;

    /** @var int pin setup as pulse with modulation */
    const SETUP_PIN_PWM = 2;

    /**
     * Returns the affected BCM pin number
     *
     * @return int
     */
    public function getPinNumber(): int;

    /**
     * Returns one of the SETUP_PIN_* constants
     *
     * @return int
     */
    public function getSetup(): int;

    /**
     * Returns options for setup
     *
     * @return array|null
     */
    public function getOptions(): ?array;
}