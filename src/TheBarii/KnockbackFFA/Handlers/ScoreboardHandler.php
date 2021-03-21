<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Handlers;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use TheBarii\KnockbackFFA\Main;

class ScoreboardHandler
{

    private $plugin;
    private $main=[];

    public function __construct(Main $plugin){

        $this->plugin = $plugin;

    }

    public function scoreboard($player, string $title ="Knockback FFA"): void
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
            $player = $p->getPlayer();
            $ks =  $this->plugin->getDatabaseHandler()->topKillstreaks($player->getName());
            $this->lineTitle($player, "  "."§bKnockback FFA ");
            $online = count($this->plugin->getServer()->getOnlinePlayers());
            $this->lineCreate($player, 0, ("§r§r§r§r§r§r§r§r--------------------"));
            $this->lineCreate($player, 1, "$ks");
            $this->lineCreate($player, 2, "§r§r§r§r§r§r§r§r--------------------");
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