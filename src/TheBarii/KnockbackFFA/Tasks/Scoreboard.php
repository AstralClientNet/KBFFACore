<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Tasks;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\ArmorInventoryEventProcessor;
use pocketmine\item\Armor;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use TheBarii\KnockbackFFA\Main;
use TheBarii\KnockbackFFA\CPlayer;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\scheduler\Task;
use pocketmine\block\Sandstone;
use pocketmine\block\Block;
use pocketmine\block\Air;

class Scoreboard extends Task
{

    public function __construct(Main $plugin)
    {

        $this->plugin = $plugin;

    }


    public function onRun(int $tick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player)
            if (!$player == null) {
                $this->removeScoreboard($player);
        $ks = $this->plugin->getDatabaseHandler()->getKillstreak($player->getName());
        $k = $this->plugin->getDatabaseHandler()->getKills($player->getName());
        $bks = Main::getInstance()->getDatabaseHandler()->getBestKillstreak($player);
        $online = count($this->plugin->getServer()->getOnlinePlayers());

        $this->lineTitle($player, "  "."§r§d§lKNOCKBACK FFA§r ");

        $this->lineCreate($player, 1, ("§r§r§r§r§r§r§r§7-------------------"));

        $this->lineCreate($player, 2, "§dOnline: §f$online");
        $this->lineCreate($player, 3, "§r");
        $this->lineCreate($player, 4, "§dKillstreak: §f$ks");
        $this->lineCreate($player, 5, "§dBest Killstreak: §f$bks");
        $this->lineCreate($player, 6, "§dKills: §f$k ");
        $this->lineCreate($player, 7, "§r");
        $this->lineCreate($player, 8, "§o§dpvp.astralclient.net§r");

        $this->lineCreate($player, 9, "§r§r§r§r§r§r§r§r§7-------------------");

        $this->scoreboard[$player->getName()] = $player->getName();
        $this->main[$player->getName()] = $player->getName();

    }
}

    public function lineTitle($player, string $title)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
            $player = $p->getPlayer();
            $packet = new SetDisplayObjectivePacket();
            $packet->displaySlot = "sidebar";
            $packet->objectiveName = "objective";
            $packet->displayName = $title;
            $packet->criteriaName = "dummy";
            $packet->sortOrder = 0;
            $player->sendDataPacket($packet);
        }
    }
    public function removeScoreboard($player){
        $player=Main::getPlayer($player);
        $packet=new RemoveObjectivePacket();
        $packet->objectiveName="objective";
        $player->sendDataPacket($packet);
        unset($this->scoreboard[$player->getName()]);
        unset($this->main[$player->getName()]);
    }

    public function lineCreate($player, int $line, string $content)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
            $player = $p->getPlayer();
            $packetline = new ScorePacketEntry();
            $packetline->objectiveName = "objective";
            $packetline->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
            $packetline->customName = " " . $content . "   ";
            $packetline->score = $line;
            $packetline->scoreboardId = $line;
            $packet = new SetScorePacket();
            $packet->type = SetScorePacket::TYPE_CHANGE;
            $packet->entries[] = $packetline;
            $player->sendDataPacket($packet);
        }
    }

    public function lineRemove($player, int $line)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
            $player = $p->getPlayer();
            $entry = new ScorePacketEntry();
            $entry->objectiveName = "objective";
            $entry->score = $line;
            $entry->scoreboardId = $line;
            $packet = new SetScorePacket();
            $packet->type = SetScorePacket::TYPE_REMOVE;
            $packet->entries[] = $entry;
            $player->sendDataPacket($packet);
        }
    }

}