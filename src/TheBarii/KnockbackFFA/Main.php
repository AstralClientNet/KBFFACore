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
use TheBarii\KnockbackFFA\Handlers\DatabaseHandler;
use TheBarii\KnockbackFFA\Handlers\ScoreboardHandler;


class Main extends PluginBase{


    private static $instance;
    private static $scoreboardHandler;
    private static $databaseHandler;
    public $text;


 public function onEnable():void{

     self::$instance=$this;

     $this->getServer()->loadLevel("kbffa");
     $this->disableCommands();
     $this->setListeners();
     $this->setCommands();
     $this->setHandlers();
     $this->loadUpdatingFloatingTexts();
     $this->db = @mkdir($this->getDataFolder()."kb.db");
     $this->main=new\SQLite3($this->getDataFolder()."kb.db");
     $this->text = new FloatingTextParticle(new Vector3(77, 48, -892), "", "");
     $this->main->exec("CREATE TABLE IF NOT EXISTS essentialstats (player TEXT PRIMARY KEY, kills INT, deaths INT, kdr REAL, killstreak INT, bestkillstreak INT, coins INT, elo INT);");
 }

        public function getUpdatingFloatingTexts(){



         }
    public function loadUpdatingFloatingTexts():void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $title = "§5§lTop Killstreaks §c§lLeaderboard";
            $ks = $this->getDatabaseHandler()->topKillstreaks($player->getName());

            $this->text->setTitle($title);
            $this->text->setText($ks);
            $level = $this->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text);
            $this->text->sendToAll();
        }
    }

    public static function getInstance():Main{
        return self::$instance;
    }

    public static function getDatabaseHandler():DatabaseHandler{
        return self::$databaseHandler;
    }


    public static function getScoreboardHandler():ScoreboardHandler{
    return self::$scoreboardHandler;
    }


    public function replaceProcess(Player $player, string $string):string{
        $string=str_replace("{topkillstreaks}", $this->getDatabaseHandler()->topKillstreaks($player->getName()), $string);
        return $string;
    }

    public static function isPlayer($player):bool{
        return !is_null(self::getPlayer($player));
    }
    public static function getPlayer($info){
        $result=null;
        $player=self::getPlayerName($info);
        if($player===null){
            return $result;
            return;
        }
        $player=Server::getInstance()->getPlayer($player);
        if($player instanceof Player){
            $result=$player;
        }
        return $result;
    }


public static function getPlayerName($player){
    $result=null;
    if(isset($player) and !is_null($player)){
        if($player instanceof Player){
            $result=$player->getName();
        }elseif(is_string($player)){
            $result=$player;
        }
    }
    return $result;
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

    public function setHandlers()
    {
        self::$databaseHandler=new DatabaseHandler();
        self::$scoreboardHandler=new ScoreboardHandler($this);
    }


}