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


use TASoft\GPIO\Exception\GPIOInitException;

class GPIO extends AbstractGPIO
{
    /**
     * @inheritDoc
     */
    protected function loadRevision(string &$hardware, string &$serialNumber): string
    {
        $info = file_get_contents('/proc/cpuinfo');
        $rev = "";
        if(preg_match_all("/^revision\s*:\s*(\S+)\s*$/im", $info, $ms)) {
            $rev = $ms[1];
        }
        if(preg_match_all("/^hardware\s*:\s*(\S+)\s*$/im", $info, $ms)) {
            $hardware = $ms[1];
        }
        if(preg_match_all("/^serial\s*:\s*(\S+)\s*$/im", $info, $ms)) {
            $serialNumber = $ms[1];
        }
        return $rev;
    }

    /**
     * @inheritDoc
     */
    protected function setupRevision($revision, string &$model, array &$pinout)
    {
        $revs = require __DIR__ . "/../lib/revisions.php";
        $model = $revs['revisions'][ $revision ] ?? NULL;

        $po = $revs['pinout']($revision);
        if(!is_file(__DIR__ . "/../lib/pinout-$po.php")) {
            throw new GPIOInitException("Revision $po is not defined", -4);
        } else {
            $pinout = require __DIR__ . "/../lib/pinout-$pinout.php";
        }
    }

    /**
     * Reads the CPU temperature of the pi
     *
     * @return float
     */
    public function getCpuTemperature(): float
    {
        return floatval(file_get_contents('/sys/class/thermal/thermal_zone0/temp'))/1000;
    }

    /**
     * Reads the current frequency of the CPU
     *
     * @return int
     */
    public function getCpuFrequency(): int
    {
        return floatval(file_get_contents('/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq'))/1000;
    }
}