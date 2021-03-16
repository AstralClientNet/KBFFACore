<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\EventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use TheBarii\Listeners\PlayerListener;
use TheBarii\Listeners\BlockListener;


class Main extends PluginBase{
    private static $instance;

 public function onEnable():void{

     self::$instance=$this;

     $this->getServer()->loadLevel("kbffa");

 }

public function setListeners(){
    $map=$this->getServer()->getPluginManager();
    $map->registerEvents(new PlayerListener($this), $this);
    $map->registerEvents(new BlockListener($this), $this);
}


}