<?php

namespace rxduz\rewards\manager\types;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat;

class Category
{

    public function __construct(
        private string $name,
        private string $customname,
        private string $description,
        private int $slot,
        private string $representativeItem
    ) {}

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
}
