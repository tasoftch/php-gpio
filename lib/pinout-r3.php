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

use TASoft\GPIO\Info\PinInfoInterface;

return [
    'max' => 40,
    'name' => [
        1 => '3.3v',
        2 => '5v',
        3 => 'SDA.1',
        4 => '5v',
        5 => 'SCL.1',
        6 => '0v',
        7 => 'GPIO. 7',
        8 => 'TxD',
        9 => '0v',
        10 => 'RxD',
        11 => 'GPIO. 0',
        12 => 'GPIO. 1',
        13 => 'GPIO. 2',
        14 => '0v',
        15 => 'GPIO. 3',
        16 => 'GPIO. 4',
        17 => '3.3v',
        18 => 'GPIO. 5',
        19 => 'MOSI',
        20 => '0v',
        21 => 'MISO',
        22 => 'GPIO. 6',
        23 => 'SCLK',
        24 => 'CE0',
        25 => '0v',
        26 => 'CE1',
        27 => 'SDA.0',
        28 => 'SCL.0',
        29 => 'GPIO.21',
        30 => '0v',
        31 => 'GPIO.22',
        32 => 'GPIO.26',
        33 => 'GPIO.23',
        34 => '0v',
        35 => 'GPIO.24',
        36 => 'GPIO.27',
        37 => 'GPIO.25',
        38 => 'GPIO.28',
        39 => '0v',
        40 => 'GPIO.29',
    ],
    'bcm2brd' => [
        2 => 3,
        3 => 5,
        4 => 7,
        14 => 8,
        15 => 10,
        17 => 11,
        18 => 12,
        27 => 13,
        22 => 15,
        23 => 16,
        24 => 18,
        10 => 19,
        9 => 21,
        25 => 22,
        11 => 23,
        8 => 24,
        7 => 26,
        0 => 27,
        1 => 28,
        5 => 29,
        6 => 31,
        12 => 32,
        13 => 33,
        19 => 35,
        16 => 36,
        26 => 37,
        20 => 38,
        21 => 40,
    ],
    'wpi2brd' => [
        0 => 11,
        1 => 12,
        2 => 13,
        3 => 15,
        4 => 16,
        5 => 18,
        6 => 22,
        7 => 7,
        8 => 3,
        9 => 5,
        10 => 24,
        11 => 26,
        12 => 19,
        13 => 21,
        14 => 23,
        15 => 8,
        16 => 10,
        21 => 29,
        22 => 31,
        23 => 33,
        24 => 35,
        25 => 37,
        26 => 32,
        27 => 36,
        28 => 38,
        29 => 40,
        30 => 27,
        31 => 28,
    ],
    'funcs' => [
        PinInfoInterface::MODE_33V => [
            1, 17
        ],
        PinInfoInterface::MODE_5V => [
            2, 4
        ],
        PinInfoInterface::MODE_GROUND => [
            6, 9, 14, 20, 25, 30, 34, 39
        ],
        PinInfoInterface::MODE_GPIO => [
            3, 5, 7, 8, 10, 11, 12, 13, 15, 16, 18, 19, 21, 22, 23, 24, 26, 27, 28, 29, 31, 32, 33, 35, 36, 37, 38, 40
        ],
        PinInfoInterface::MODE_SPI => [
            11, 12, 19, 21, 23, 24, 26, 35, 36, 38, 40
        ],
        PinInfoInterface::MODE_I2C => [
            3, 5, 27, 28
        ],
        PinInfoInterface::MODE_UART => [
            8, 10
        ],
    ]
];
