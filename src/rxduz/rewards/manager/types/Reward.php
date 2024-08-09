<?php

namespace rxduz\rewards\manager\types;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use rxduz\rewards\cooldown\Cooldown;
use rxduz\rewards\Main;
use rxduz\rewards\translation\Translation;
use rxduz\rewards\utils\Utils;

class Reward
{

    public function __construct(
        private string $name,
        private string $customname,
        private string $description,
        private string $category,
        private int $slot,
        private string $representativeItem,
        private array $commands,
        private int $cooldown,
        private string $permission
    ) {
        $this->createPermission($permission);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCustomName(): string
    {
        return TextFormat::colorize($this->customname);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getSlot(): int
    {
        return $this->slot;
    }

    /**
     * @return Item
     */
    public function getRepresentativeItem(): Item
    {
        $stringToItem = StringToItemParser::getInstance();

        $item = $stringToItem->parse($this->representativeItem);

        if ($item === null) $item = VanillaItems::DIAMOND();

        return $item;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return int
     */
    public function getCooldown(): int
    {
        return $this->cooldown;
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     */
    public function createPermission(string $permission): void
    {
        $permManager = PermissionManager::getInstance();

        $opRoot = $permManager->getPermission(DefaultPermissions::ROOT_OPERATOR);

        $permManager->addPermission(new Permission($permission));

        $opRoot->addChild($permission, true);
    }

    /**
     * @param Player $player
     */
    public function tryToClaim(Player $player): void
    {
        $player->removeCurrentWindow(); // Remove chest inventory

        if (!$player->hasPermission($this->getPermission())) {
            $player->sendMessage(Translation::getInstance()->getMessage('REWARD_NO_PERMISSION', ['{PREFIX}' => Main::PREFIX]));

            Utils::playSound($player, Main::getInstance()->getConfig()->get('reward-failed-sound'));

            return;
        }

        if (Cooldown::getInstance()->hasCooldow($player->getName(), 'reward-' . $this->name)) {
            $player->sendMessage(Translation::getInstance()->getMessage('REWARD_IN_COOLDOWN', ['{PREFIX}' => Main::PREFIX, '{COOLDOWN}' => Cooldown::getInstance()->intToTimeString(Cooldown::getInstance()->getCooldow($player->getName(), 'reward-' . $this->name))]));

            Utils::playSound($player, Main::getInstance()->getConfig()->get('reward-failed-sound'));

            return;
        }

        foreach ($this->commands as $command) {
            $console = new ConsoleCommandSender(Main::getInstance()->getServer(), Main::getInstance()->getServer()->getLanguage());

            Server::getInstance()->dispatchCommand($console, str_replace("{PLAYER}", '"' . $player->getName() . '"', $command));
        }

        Cooldown::getInstance()->addCooldow($player->getName(), 'reward-' . $this->name, $this->cooldown);

        $player->sendMessage(Translation::getInstance()->getMessage('REWARD_CLAIM_SUCCESSFULLY', ['{PREFIX}' => Main::PREFIX, '{REWARD}' => $this->name]));

        Utils::playSound($player, Main::getInstance()->getConfig()->get('reward-claim-sound'));

        if (Main::getInstance()->getConfig()->get('broadcast-claim-rewards')) Main::getInstance()->getServer()->broadcastMessage(Translation::getInstance()->getMessage('BROADCAST_REWARD_CLAIM_SUCCESSFULLY', ['{PREFIX}' => Main::PREFIX, '{PLAYER}' => $player->getName(), '{REWARD}' => $this->name]));
    }
}
