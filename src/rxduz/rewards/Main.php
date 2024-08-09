<?php

namespace rxduz\rewards;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use rxduz\rewards\command\RewardCommand;
use rxduz\rewards\cooldown\Cooldown;
use rxduz\rewards\manager\CategoriesManager;
use rxduz\rewards\manager\RewardManager;
use rxduz\rewards\translation\Translation;
use rxduz\rewards\utils\ConfigUpdater;

class Main extends PluginBase
{

    /** @var string */
    public const PREFIX = TextFormat::BOLD . TextFormat::DARK_GRAY . '(' . TextFormat::MINECOIN_GOLD . 'AdvancedRewards' . TextFormat::DARK_GRAY . ')' . TextFormat::RESET . ' ';

    /** @var int */
    public const CONFIG_VERSION = 1;

    use SingletonTrait;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();

        $this->saveResource('/messages.yml');

        $this->saveResource('categories.yml');

        $this->saveResource('/rewards.yml');

        if (!class_exists(InvMenu::class)) {
            $this->getServer()->getPluginManager()->disablePlugin($this);

            $this->getLogger()->warning(TextFormat::RED . 'InvMenu not found, please download it and try again!');
            return;
        }

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        ConfigUpdater::checkUpdate($this->getConfig(), self::CONFIG_VERSION);
        Translation::getInstance()->init();
        Cooldown::getInstance()->load();
        CategoriesManager::getInstance()->load();
        RewardManager::getInstance()->load();

        $this->getServer()->getCommandMap()->register('AdvancedRewards', new RewardCommand($this->getConfig()->get('command')));

        $this->getLogger()->info(self::PREFIX . 'Enabled successfully made by iRxDuZ :3');
    }

    protected function onDisable(): void
    {
        Cooldown::getInstance()->save();
    }
}
