<?php

namespace rxduz\rewards\utils;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rxduz\rewards\cooldown\Cooldown;
use rxduz\rewards\manager\CategoriesManager;
use rxduz\rewards\manager\RewardManager;
use rxduz\rewards\manager\types\Category;
use rxduz\rewards\manager\types\Reward;
use rxduz\rewards\translation\Translation;

class InvMenuUtils
{

    /**
     * @param Player $player
     */
    public static function sendCategoriesMenu(Player $player): void
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);

        $menu->setName(Translation::getInstance()->getMessage('CATEGORIES_MENU_NAME'));

        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
            $player = $transaction->getPlayer();
            $itemClicked = $transaction->getItemClicked();

            if ($itemClicked->getNamedTag()->getTag('category') !== null) {
                $categoryName = $itemClicked->getNamedTag()->getString('category');

                $category = CategoriesManager::getInstance()->getCategoryByName($categoryName);

                if ($category instanceof Category) {
                    self::sendRewardsMenu($player, $category);
                }
            }

            return $transaction->discard();
        });

        foreach (CategoriesManager::getInstance()->getCategories() as $category) {
            $item = $category->getRepresentativeItem();

            $item->setCustomName($category->getCustomName());

            $item->setLore([
                TextFormat::colorize($category->getDescription())
            ]);

            $item->getNamedTag()->setString('category', $category->getName());

            $menu->getInventory()->setItem($category->getSlot(), $item);
        }

        $menu->send($player);
    }

    /**
     * @param Player $player
     * @param Category $category
     */
    public static function sendRewardsMenu(Player $player, Category $category): void
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);

        $menu->setName(Translation::getInstance()->getMessage('REWARDS_MENU_NAME', ['{CATEGORY}' => $category->getCustomName()]));

        $menu->setListener(function (InvMenuTransaction $transaction) use ($category): InvMenuTransactionResult {
            $player = $transaction->getPlayer();
            $itemClicked = $transaction->getItemClicked();

            if ($itemClicked->getNamedTag()->getTag('reward') !== null) {
                $rewardName = $itemClicked->getNamedTag()->getString('reward');

                $reward = RewardManager::getInstance()->getRewardByName($rewardName);

                if ($reward instanceof Reward) {
                    $reward->tryToClaim($player);
                }
            }

            return $transaction->discard();
        });

        foreach (RewardManager::getInstance()->getRewardsByCategory($category) as $reward) {
            $item = $reward->getRepresentativeItem();

            $item->setCustomName($reward->getCustomName());

            $status = Translation::getInstance()->getMessage('STATUS_AVAILABLE');

            if (Cooldown::getInstance()->hasCooldow($player->getName(), 'reward-' . $reward->getName())) {
                $status = Translation::getInstance()->getMessage('STATUS_NOT_AVAILABLE', ['{COOLDOWN}' => Cooldown::getInstance()->intToTimeString(Cooldown::getInstance()->getCooldow($player->getName(), 'reward-' . $reward->getName()))]);
            }

            $description = str_replace('{STATUS}', $status, $reward->getDescription());

            $item->setLore([
                TextFormat::colorize($description)
            ]);

            $item->getNamedTag()->setString('reward', $reward->getName());

            $menu->getInventory()->setItem($reward->getSlot(), $item);
        }

        $menu->send($player);
    }
}
