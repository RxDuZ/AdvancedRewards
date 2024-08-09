<?php

namespace rxduz\rewards\manager;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use rxduz\rewards\Main;
use rxduz\rewards\manager\types\Category;

class CategoriesManager
{

    use SingletonTrait;

    /** @var Category[] $categories */
    private array $categories;

    /** @var Config $data */
    private Config $data;

    public function load(): void
    {
        $this->categories = [];

        $this->data = new Config(Main::getInstance()->getDataFolder() . '/categories.yml', Config::YAML);

        foreach ($this->data->getAll() as $k => $v) {
            if (isset($this->categories[$k])) {
                Main::getInstance()->getServer()->getLogger()->info(Main::PREFIX . TextFormat::RED . 'The category ' . $k . ' already exists so it was ignored.');

                continue;
            }

            $this->categories[$k] = new Category($k, $v['customname'] ?? '', $v['description'] ?? '', $v['slot'] ?? 1, $v['representative-item'] ?? 'diamond');
        }

        Main::getInstance()->getServer()->getLogger()->info(Main::PREFIX . TextFormat::GREEN . 'Load ' . count($this->categories) . ' categorie(s).');
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return Category|null
     */
    public function getCategoryByName(string $name): Category|null
    {
        return $this->categories[$name] ?? null;
    }
}
