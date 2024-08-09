<?php

namespace rxduz\rewards\manager;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use rxduz\rewards\Main;
use rxduz\rewards\manager\types\Category;
use rxduz\rewards\manager\types\Reward;

class RewardManager
{

    use SingletonTrait;

    /** @var Reward[] $rewards */
    private array $rewards;

    /** @var Config $data */
    private Config $data;

    public function load(): void
    {
        $this->rewards = [];

        $this->data = new Config(Main::getInstance()->getDataFolder() . '/rewards.yml', Config::YAML);

        foreach ($this->data->getAll() as $k => $v) {
            if (isset($this->rewards[$k])) {
                Main::getInstance()->getServer()->getLogger()->info(Main::PREFIX . TextFormat::RED . 'The reward ' . $k . ' already exists so it was ignored.');

                continue;
            }

            $this->rewards[$k] = new Reward($k, $v['customname'] ?? '', $v['description'] ?? '',  $v['category'] ?? 'user', $v['slot'] ?? 1, $v['representative-item'] ?? 'diamond', $v['commands'] ?? [], $v['cooldown'] ?? 600, $v['permission'] ?? 'reward.user.claim');
        }

        Main::getInstance()->getServer()->getLogger()->info(Main::PREFIX . TextFormat::GREEN . 'Load ' . count($this->rewards) . ' reward(s).');
    }

    /**
     * @return Reward[]
     */
    public function getRewards(): array
    {
        return $this->rewards;
    }

    /**
     * @return Reward|null
     */
    public function getRewardByName(string $name): Reward|null
    {
        return $this->rewards[$name] ?? null;
    }

    /** 
     * @return Reward[]
     */
    public function getRewardsByCategory(Category $category): array
    {
        return array_filter($this->rewards, function (Reward $reward) use ($category): bool {
            return $reward->getCategory() === $category->getName();
        });
    }
}
