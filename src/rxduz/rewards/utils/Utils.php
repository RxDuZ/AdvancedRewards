<?php

namespace rxduz\rewards\utils;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class Utils
{

    /**
     * @param Player $player
     * @param string $soundName
     * @param float $volume
     * @param float $pitch
     */
    public static function playSound(Player $player, string $soundName, float $volume = 1.0, float $pitch = 1.0): void
    {
        $pk = PlaySoundPacket::create(
            $soundName,
            $player->getLocation()->getX(),
            $player->getLocation()->getY(),
            $player->getLocation()->getZ(),
            $volume,
            $pitch
        );

        $player->getNetworkSession()->sendDataPacket($pk);
    }
}
