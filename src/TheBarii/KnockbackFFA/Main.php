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
use TheBarii\KnockbackFFA\Tasks\Scoreboard;
use TheBarii\KnockbackFFA\Tasks\DropParty;
use TheBarii\KnockbackFFA\Tasks\Generator;


class Main extends PluginBase
{


    private static $instance;
    private static $scoreboardHandler;
    private static $databaseHandler;
    public $text;
    public $text2;
    public $text3;
    public $text4;


    public function onEnable(): void
    {

        self::$instance = $this;

        $this->getServer()->loadLevel("kbffa");
        $this->disableCommands();
        $this->setListeners();
        $this->setCommands();
        $this->setHandlers();
        $this->loadUpdatingFloatingTexts();
        $this->loadUpdatingFloatingTexts2();
        $this->loadUpdatingFloatingTexts3();
        $this->loadUpdatingFloatingTexts4();
        $this->setTasks();
        $this->db = @mkdir($this->getDataFolder() . "kb.db");
        $this->main = new\SQLite3($this->getDataFolder() . "kb.db");
        $this->text = new FloatingTextParticle(new Vector3(242, 90, 182), "", "");
        $this->text2 = new FloatingTextParticle(new Vector3(244, 90, 184), "", "");
        $this->text3 = new FloatingTextParticle(new Vector3(240, 71, 204), "", "");
        $this->text4 = new FloatingTextParticle(new Vector3(240, 70, 204), "", "");
        $this->main = new\SQLite3($this->getDataFolder() . "kb.db");

        $this->main->exec("CREATE TABLE IF NOT EXISTS rank (player TEXT PRIMARY KEY, rank TEXT);");

        $this->main->exec("CREATE TABLE IF NOT EXISTS essentialstats (player TEXT PRIMARY KEY, kills INT, deaths INT, kdr REAL, killstreak INT, bestkillstreak INT, coins INT, elo INT);");
        $this->main->exec("CREATE TABLE IF NOT EXISTS matchstats (player TEXT PRIMARY KEY, elo INT, wins INT, losses INT, elogained INT, elolost INT);");
        $this->main->exec("CREATE TABLE IF NOT EXISTS temporary (player TEXT PRIMARY KEY, dailykills INT, dailydeaths INT);");
        $this->main->exec("CREATE TABLE IF NOT EXISTS temporaryranks (player TEXT PRIMARY KEY, temprank TEXT, duration INT, oldrank TEXT);");
        $this->main->exec("CREATE TABLE IF NOT EXISTS voteaccess (player TEXT PRIMARY KEY, bool TEXT, duration INT);");
        $this->main->exec("CREATE TABLE IF NOT EXISTS levels (player TEXT PRIMARY KEY, level INT, neededxp INT, currentxp INT, totalxp INT);");

    }


    public function loadUpdatingFloatingTexts(): void
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

    public function loadUpdatingFloatingTexts2(): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $title = "§5§lTop Kills §c§lLeaderboard";
            $k = $this->getDatabaseHandler()->topKills($player->getName());

            $this->text->setTitle($title);
            $this->text->setText($k);
            $level = $this->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text);
            $this->text->sendToAll();
        }
    }

    public function loadUpdatingFloatingTexts3(): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $title3 = "§5§lGenerator";
            $this->text3->setTitle($title3);
            $level = $this->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text3);
            $this->text3->sendToAll();
        }
    }

    public function loadUpdatingFloatingTexts4(): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $title4 = "§5§lGenerator";
            $this->text4->setTitle($title4);
            $level = $this->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text4);
            $this->text4->sendToAll();
        }
    }



    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public static function getDatabaseHandler(): DatabaseHandler
    {
        return self::$databaseHandler;
    }


    public static function getScoreboardHandler(): ScoreboardHandler
    {
        return self::$scoreboardHandler;
    }


    public function replaceProcess(Player $player, string $string): string
    {
        $string = str_replace("{topkillstreaks}", $this->getDatabaseHandler()->topKillstreaks($player->getName()), $string);
        return $string;
    }

    public static function isPlayer($player): bool
    {
        return !is_null(self::getPlayer($player));
    }

    public static function getPlayer($info)
    {
        $result = null;
        $player = self::getPlayerName($info);
        if ($player === null) {
            return $result;
            return;
        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player) {
            $result = $player;
        }
        return $result;
    }


    public static function getPlayerName($player)
    {
        $result = null;
        if (isset($player) and !is_null($player)) {
            if ($player instanceof Player) {
                $result = $player->getName();
            } elseif (is_string($player)) {
                $result = $player;
            }
        }
        return $result;
    }


    public function disableCommands()
    {
        $map = $this->getServer()->getCommandMap();
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

    public function setCommands()
    {


        //TODO: Staff utils & commands
        $map = $this->getServer()->getCommandMap();
        $map->register("gm", new GamemodeCommand($this));
        $map->register("ping", new PingCommand($this));
        $map->register("tpall", new TpallCommand($this));
        $map->register("reply", new ReplyCommand($this));
        $map->register("whisper", new WhisperCommand($this));
        $this->getLogger()->info("--- Loaded Commands ---");
    }

    public function setListeners()
    {

        $map = $this->getServer()->getPluginManager();
        $map->registerEvents(new PlayerListener($this), $this);
        $map->registerEvents(new BlockListener($this), $this);
        $this->getLogger()->info("--- Loaded Listeners ---");
    }

    public function setHandlers()
    {
        self::$databaseHandler = new DatabaseHandler();
        self::$scoreboardHandler = new ScoreboardHandler($this);
    }

    public function setTasks()
    {
        $this->getScheduler()->scheduleRepeatingTask(new Scoreboard($this), 20);
        $this->getScheduler()->scheduleRepeatingTask(new DropParty($this), mt_rand(1200, 3600));
        $this->getScheduler()->scheduleRepeatingTask(new Generator($this), mt_rand(100 , 200));
    }
}
