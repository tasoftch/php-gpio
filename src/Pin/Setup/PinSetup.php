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


class PinSetup implements MultiplePinSetupInterface
{
    private $pins = [];
    /** @var int */
    protected $setup = self::SETUP_PIN_INPUT;
    /** @var array  */
    protected $options = [];

    /**
     * @inheritDoc
     */
    public function getPinNumbers(): array
    {
        return array_keys($this->pins);
    }

    /**
     * @inheritDoc
     */
    public function getPinNumber(): int
    {
        return reset($this->pins);
    }

    /**
     * Adds a pin number to the setup
     *
     * @param int $pin
     */
    public function addPinNumber(int $pin) {
        $this->pins[$pin] = 1;
    }

    /**
     * Removes a pin number from setup
     *
     * @param int $pin
     */
    public function removePinNumber(int $pin) {
        unset($this->pins[$pin]);
    }

    /**
     * @inheritDoc
     */
    public function getSetup(): int
    {
        return $this->setup;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Define an option
     *
     * @param string $name
     * @param $value
     */
    public function setOption(string $name, $value) {
        $this->options[$name] = $value;
    }

    /**
     * Fetches an option
     *
     * @param string $name
     * @return mixed|null
     */
    public function getOption(string $name) {
        return $this->options[$name] ?? NULL;
    }
}