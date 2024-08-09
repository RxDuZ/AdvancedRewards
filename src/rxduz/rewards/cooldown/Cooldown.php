<?php

/**
 * This API was created by me 
 * for multiple cooldown uses 
 * and is free to use :)
 */

namespace rxduz\rewards\cooldown;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use rxduz\rewards\Main;

class Cooldown
{

    use SingletonTrait;

    /** @var array $cooldowns */
    private array $cooldowns = [];

    /** @var Config $data */
    private Config $data;

    /**
     * Load data when powering on the server
     */
    public function load(): void
    {
        $this->data = new Config(Main::getInstance()->getDataFolder() . '/cooldown.yml', Config::YAML);

        foreach ($this->data->getAll() as $name => $value) {
            foreach ($value as $type => $time) {
                $this->cooldowns[$name] = $value;

                if (!$this->hasCooldow($name, $type)) {
                    $this->removeCooldow($name, $type);
                }
            }
        }
    }

    /**
     * @param string $name
     * @param string $type
     * @param int $time
     */
    public function addCooldow(string $name, string $type, int $time)
    {
        $this->cooldowns[$name][$type] = time() + $time;
    }

    /**
     * @param string $name
     * @param string $type
     */
    public function removeCooldow(string $name, string $type)
    {
        unset($this->cooldowns[$name][$type]);
    }

    /**
     * @param string $name
     * @param string $type
     */
    public function hasCooldow(string $name, string $type): bool
    {
        if (isset($this->cooldowns[$name][$type])) {
            if (time() < $this->cooldowns[$name][$type]) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $type
     * @return int
     */
    public function getCooldow(string $name, string $type): int
    {
        if (!$this->hasCooldow($name, $type)) return 0;

        return $this->cooldowns[$name][$type] - time();
    }

    /**
     * @param int $seconds
     * @return string
     */
    public function intToTimeString(int $seconds): string
    {
        if ($seconds === 0) {
            return "0 seconds";
        }

        $timeString = "";

        $timeArray = [];

        if ($seconds >= 86400) {
            $unit = floor($seconds / 86400);

            $seconds -= $unit * 86400;

            $timeArray[] = $unit . " days";
        }

        if ($seconds >= 3600) {
            $unit = floor($seconds / 3600);

            $seconds -= $unit * 3600;

            $timeArray[] = $unit . " hours";
        }

        if ($seconds >= 60) {
            $unit = floor($seconds / 60);

            $seconds -= $unit * 60;

            $timeArray[] = $unit . " minutes";
        }

        if ($seconds >= 1) {
            $timeArray[] = $seconds . " seconds";
        }

        foreach ($timeArray as $key => $value) {
            if ($key === 0) {
                $timeString .= $value;
            } elseif ($key === count($timeArray) - 1) {
                $timeString .= " and " . $value;
            } else {
                $timeString .= ", " . $value;
            }
        }

        return $timeString;
    }

    /**
     * Save data when shutting down the server
     */
    public function save()
    {
        $this->data->setAll($this->cooldowns);
        $this->data->save();
    }
}
