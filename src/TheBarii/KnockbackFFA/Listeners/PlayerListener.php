<?php

declare(strict_types=1);

namespace TheBarii\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class PlayerListener extends Listener{

    public function __construct(KnockbackFFA $plugin){
        $this->plugin=$plugin;
    }

    public function onJoin(PlayerJoinEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setJoinMessage("§r§d+§r§a $n");
        $p->setItems($p);

    }

    public function setItems(Player $p){

        $p->extinguish();
        $p->setScale(1);
        $p->setGamemode(2);
        $p->getInventory()->setSize(36);
        $p->getInventory()->clearAll();
        $p->getArmorInventory()->clearAll();
        $p->getInventory()->setItems(Item::get(1, 0, 1), 0);
    }

}