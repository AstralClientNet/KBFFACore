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
use TheBarii\KnockbackFFA\Listeners\PlayerListener;
use TheBarii\KnockbackFFA\Listeners\BlockListener;
use TheBarii\KnockbackFFA\commands\GamemodeCommand;
use TheBarii\KnockbackFFA\commands\PingCommand;
use TheBarii\KnockbackFFA\commands\ReplyCommand;
use TheBarii\KnockbackFFA\commands\TpallCommand;
use TheBarii\KnockbackFFA\commands\WhisperCommand;


class Main extends PluginBase{


    private static $instance;

 public function onEnable():void{

     self::$instance=$this;

     $this->getServer()->loadLevel("kbffa");
     $this->disableCommands();
     $this->setListeners();
     $this->setCommands();


 }

    public function disableCommands(){
        $map=$this->getServer()->getCommandMap();
        $map->unregister($map->getCommand("kill"));
        $map->unregister($map->getCommand("me"));
        $map->unregister($map->getCommand("op"));
        $map->unregister($map->getCommand("deop"));
        $map->unregister($map->getCommand("enchant"));
        $map->unregister($map->getCommand("effect"));
        $map->unregister($map->getCommand("defaultgamemode"));
        $map->unregister($map->getCommand("difficulty"));
        $map->unregister($map->getCommand("spawnpoint"));
        $map->unregister($map->getCommand("setworldspawn"));
        $map->unregister($map->getCommand("title"));
        $map->unregister($map->getCommand("seed"));
        $map->unregister($map->getCommand("particle"));
        $map->unregister($map->getCommand("gamemode"));
        $map->unregister($map->getCommand("tell"));
        $map->unregister($map->getCommand("say"));
        $map->unregister($map->getCommand("reload"));

    }

 public function setCommands(){


     //TODO: Staff utils & commands
     $map=$this->getServer()->getCommandMap();
     $map->register("gm", new GamemodeCommand($this));
     $map->register("ping", new PingCommand($this));
     $map->register("tpall", new TpallCommand($this));
     $map->register("reply", new ReplyCommand($this));
     $map->register("whisper", new WhisperCommand($this));
     $this->getLogger()->info("--- Loaded Commands ---");
 }

public function setListeners(){

    $map=$this->getServer()->getPluginManager();
    $map->registerEvents(new PlayerListener($this), $this);
    $map->registerEvents(new BlockListener($this), $this);
    $this->getLogger()->info("--- Loaded Listeners ---");
  }

}