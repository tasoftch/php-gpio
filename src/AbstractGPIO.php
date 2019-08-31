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
use TASoft\GPIO\Exception\InvalidPinSetupException;
use TASoft\GPIO\Exception\StaticPinException;
use TASoft\GPIO\Info\PinInfo;
use TASoft\GPIO\Info\PinInfoInterface;
use TASoft\GPIO\Pin\InputPin;
use TASoft\GPIO\Pin\InputPinInterface;
use TASoft\GPIO\Pin\OutputPin;
use TASoft\GPIO\Pin\PinInterface;
use TASoft\GPIO\Pin\PWMPin;
use TASoft\GPIO\Pin\Setup\InputPinSetup;
use TASoft\GPIO\Pin\Setup\MultiplePinSetupInterface;
use TASoft\GPIO\Pin\Setup\PinSetupInterface;
use TASoft\RPi\Exception\GPIOException;

abstract class AbstractGPIO implements GPIOInterface
{
    /** @var string */
    private $model;
    /** @var string */
    private $revision;
    /** @var string */
    private $hardware;
    /** @var string */
    private $serial;

    private $pinout = [];

    private $numberSystem = self::GPIO_NS_WIRED;

    private $activePins = [];
    private $activeObservers = [];

    /**
     * GPIO instance constructor.
     */
    public function __construct()
    {
        if($this->revision = $this->loadRevision($this->hardware, $this->serial)) {
            $this->setupRevision( $this->revision, $this->model, $this->pinout);
            if(NULL === $this->model)
                trigger_error("Pi could not map $this->revision to a model name", E_USER_NOTICE);
        } else {
            throw new GPIOInitException("GPIO %s was not able to load model revision", -1, NULL, get_class($this));
        }

        LiveGPIOWrapper::register();
    }

    /**
     * Loads the device revision, hardware and serial number
     *
     * @param string $hardware
     * @param string $serialNumber
     * @return string
     */
    abstract protected function loadRevision(string &$hardware, string &$serialNumber): string;


    /**
     * Load model and the pinout
     *
     * @param $revision
     * @param string $model
     * @param array $pinout
     */
    abstract protected function setupRevision($revision, string &$model, array &$pinout);

    /**
     * @return int
     */
    public function getNumberSystem(): int
    {
        return $this->numberSystem;
    }

    /**
     * @param int $numberSystem
     */
    public function setNumberSystem(int $numberSystem): void
    {
        $this->numberSystem = $numberSystem;
    }


    /**
     * @inheritDoc
     */
    public function convertPinNumber(int $pinNumber, int $from = NULL, int $to = self::GPIO_NS_BOARD): int
    {
        if(is_null($from))
            $from = $this->getNumberSystem();

        switch ($from) {
            case self::GPIO_NS_BCM:
                $src = $this->pinout['bcm2brd'];
                break;
            case self::GPIO_NS_WIRED:
                $src = $this->pinout['wpi2brd'];
                break;
            default:
        }

        switch ($to) {
            case self::GPIO_NS_BCM:
                $dst = $this->pinout['bcm2brd'];
                break;
            case self::GPIO_NS_WIRED:
                $dst = $this->pinout['wpi2brd'];
                break;
            default:
        }

        if(isset($src))
            $pinNumber = $src[$pinNumber] ?? -1;

        if(isset($dst)) {
            if($idx = array_search($pinNumber, $dst))
                return $idx;
            return -1;
        }

        if(isset($this->pinout["name"][$pinNumber]))
            return $pinNumber;
        else
            return -1;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getRevision(): string
    {
        return $this->revision;
    }

    /**
     * @return string
     */
    public function getHardware(): string
    {
        return $this->hardware;
    }

    /**
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * Reads from revision what modes (functions) a given pin has
     *
     * @param int $pinNumber
     * @param int|NULL $ns
     * @return int
     */
    public function getModesForPin(int $pinNumber, int $ns = NULL): int {
        $pin = $this->convertPinNumber($pinNumber, $ns);
        $modes = 0;
        foreach($this->pinout["funcs"] as $mode => $pins) {
            if(in_array($pin, $pins))
                $modes|=$mode;
        }
        return $modes;
    }

    /**
     * @inheritDoc
     */
    public function getPinInformation(int $pinNumber, int $ns = NULL): PinInfoInterface
    {
        $bpin = $this->convertPinNumber($pinNumber, $ns, self::GPIO_NS_BOARD);
        return new PinInfo(
            $bpin,
            $this->convertPinNumber($bpin, self::GPIO_NS_BOARD, self::GPIO_NS_BCM),
            $this->convertPinNumber($bpin, self::GPIO_NS_BOARD, self::GPIO_NS_WIRED),
            $this,
            $this->pinout["name"][$bpin] ?? '??',
            $this->getModesForPin($pinNumber, $ns)
            );
    }

    protected function noteWarning($message, $pin) {
        trigger_error(sprintf($message, $pin), E_USER_WARNING);
    }

    /**
     * @param int $bcmPin
     * @param PinSetupInterface $pinSetup
     * @return PinInterface
     * @throws StaticPinException
     * @throws InvalidPinSetupException
     * @internal
     */
    private function _activatePin(int $bcmPin, PinSetupInterface $pinSetup): PinInterface {
        if($this->getModesForPin($bcmPin) & PinInfoInterface::MODE_GPIO) {
            $checkExistence = function(PinInterface $pin) {
                if(file_exists(sprintf("gpio://gpio%d", $pin->getPinNumber()))) {
                    $this->noteWarning("Pin %d is already in use", $pin->getPinNumber());
                    $this->resetPin($pin);
                }
            };

            switch($pinSetup->getSetup()) {
                case PinSetupInterface::SETUP_PIN_INPUT:
                    $pin = new InputPin($this, $bcmPin);
                    $checkExistence($pin);

                    file_put_contents("gpio:///export", $bcmPin);
                    file_put_contents("gpio:///gpio$bcmPin/direction", 'in');
                    if($res = $pinSetup->getOptions()["RESISTOR"] ?? InputPinSetup::RESISTOR_NONE) {
                        $rr = [0=>'tri', -1=>'down', 1=>'up'];
                        if(isset($rr[$res]))
                            $this->callGPIO("gpio -g mode $bcmPin {$rr[$res]}");
                    }
                    break;
                case PinSetupInterface::SETUP_PIN_OUTPUT:
                    $pin = new OutputPin($this, $bcmPin);
                    $checkExistence($pin);
                    file_put_contents("gpio:///export", $bcmPin);
                    file_put_contents("gpio:///gpio$bcmPin/direction", 'out');
                    break;
                case PinSetupInterface::SETUP_PIN_PWM:
                    $pin = new PWMPin($this, $bcmPin);
                    $checkExistence($pin);
                    file_put_contents("gpio:///gpio$bcmPin/direction", 'pwm');
                    break;
                default:
                    $e = new InvalidPinSetupException("Setup mode %d for pin #%d is not supported", -14, NULL, $pinSetup->getSetup(), $bcmPin);
                    $e->setPinNumber($bcmPin);
                    $e->setNumberSystem(self::GPIO_NS_BCM);
                    $e->setSetupMode($pinSetup->getSetup());
                    throw $e;
            }

            $this->activePins[$bcmPin] = $pin;
            return $pin;
        } else {
            $e = new StaticPinException("Can not activate pin #%d because it is static (%s)", -13, NULL, $bcmPin, $this->pinout["name"][ $this->convertPinNumber($bcmPin, self::GPIO_NS_BCM, self::GPIO_NS_BOARD) ] ?? "??");
            $e->setNumberSystem(self::GPIO_NS_BCM);
            $e->setPinNumber($bcmPin);
            throw $e;
        }
    }

    /**
     * @inheritDoc
     */
    public function activatePin(PinSetupInterface $pinSetup): PinInterface
    {
        return $this->_activatePin($pinSetup->getPinNumber(), $pinSetup);
    }

    /**
     * @inheritDoc
     */
    public function activatePins(MultiplePinSetupInterface $pinSetup): array
    {
        $list = [];
        foreach ($pinSetup->getPinNumbers() as $pinNumber) {
            $list[] = $this->_activatePin($pinNumber, $pinSetup);
        }
        return $list;
    }

    public function resetPin(PinInterface $pin): bool
    {
        if(file_exists(sprintf("gpio://gpio%d", $pin->getPinNumber()))) {
            file_put_contents("gpio://unexport", $pin->getPinNumber());
            unset($this->activePins[$pin->getPinNumber()]);
        }
        return true;
    }

    public function resetAll(): bool
    {
        foreach(array_keys($this->activePins) as $pin) {
            if(!$this->resetPin($pin))
                return false;
        }
        return true;
    }


    public function callGPIO(string $cmd) {
        return exec("$cmd");
    }


    public function registerEdge(int $edge, InputPinInterface $pin, callable $callback): bool
    {
        if(!function_exists("posix_kill"))
            return false;

        if(function_exists("pcntl_fork")) {
            $bcm = $pin->getPinNumber();

            if(isset($this->activeObservers[$bcm]))
                $this->unregisterEdge($pin);

            switch ($edge) {
                case self::GPIO_EDGE_FALLING:
                    $edge = 'falling';
                    break;
                case self::GPIO_EDGE_RISING:
                    $edge = 'rising';
                    break;
                default:
                    $edge = 'both';
            }

            $pid = pcntl_fork();
            if($pid == -1)
                throw new GPIOException("Can not fork current process", -99);
            if($pid) {
                // Main process
                $this->activeObservers[$bcm] = $pid;
            } else {
                // Child process
                while(1) {
                    $this->callGPIO("gpio -g wfi $bcm $edge");
                    call_user_func($callback, $bcm);
                }
                exit();
            }
            return true;
        }
        return false;
    }

    public function unregisterEdge(InputPinInterface $pin): bool
    {
        if(!function_exists("posix_kill"))
            return false;

        if(isset($this->activeObservers[ $pin->getPinNumber() ])) {
            $pid = $this->activeObservers[ $pin->getPinNumber() ];
            posix_kill($pid, SIGINT);
            unset($this->activeObservers[$pin->getPinNumber()]);
        }
        return true;
    }

    public function unregisterAllEdges(): bool
    {
        if(!function_exists("posix_kill"))
            return false;

        foreach(array_values($this->activeObservers) as $pid) {
            posix_kill($pid, SIGINT);
        }
        $this->activeObservers = [];

        return true;
    }


}