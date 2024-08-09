<?php

namespace rxduz\rewards\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rxduz\rewards\utils\InvMenuUtils;

class RewardCommand extends Command
{

    public function __construct(array $data)
    {
        parent::__construct($data['name'], $data['description'], null, $data['aliases']);

        $this->setPermission('reward.command.use');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'Hey, please use this command in-game');

            return;
        }

        if (!$this->testPermission($sender)) {
            return;
        }

        InvMenuUtils::sendCategoriesMenu($sender);
    }
}
