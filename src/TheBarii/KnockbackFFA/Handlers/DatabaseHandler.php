<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Handlers;

use pocketmine\Player;
use pocketmine\Server;
use TheBarii\KnockbackFFA\Main;

class DatabaseHandler{

    private $plugin;

    public function __construct(){

        $this->plugin=Main::getInstance();
    }

    public function getBestKillstreak($player){
        $query=$this->plugin->main->query("SELECT bestkillstreak FROM essentialstats WHERE player='".Main::getPlayerName($player)."';");
        $result=$query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["bestkillstreak"];
    }

    public function getDeaths($player){
        $query=$this->plugin->main->query("SELECT deaths FROM essentialstats WHERE player='".Main::getPlayerName($player)."';");
        $result=$query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["deaths"];
    }

    public function essentialStatsAdd($player){
        $check=$this->plugin->main->query("SELECT player FROM essentialstats WHERE player='".Main::getPlayerName($player)."';");
        $result=$check->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
            $query=$this->plugin->main->prepare("INSERT OR REPLACE INTO essentialstats (player, kills, deaths, kdr, killstreak, bestkillstreak, coins, elo) VALUES (:player, :kills, :deaths, :kdr, :killstreak, :bestkillstreak, :coins, :elo);");
            $query->bindValue(":player", $player);
            $query->bindValue(":kills", 0);
            $query->bindValue(":deaths", 0);
            $query->bindValue(":kdr", 0);
            $query->bindValue(":killstreak", 0);
            $query->bindValue(":bestkillstreak", 0);
            $query->bindValue(":coins", 0);
            $query->bindValue(":elo", 0);
            $query->execute();
        }
    }

    public function getKillstreak($player){
        $query=$this->plugin->main->query("SELECT killstreak FROM essentialstats WHERE player='".Main::getPlayerName($player)."';");
        $result=$query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["killstreak"];
    }

    public function getKills($player){
        $query=$this->plugin->main->query("SELECT kills FROM essentialstats WHERE player='".Main::getPlayerName($player)."';");
        $result=$query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["kills"];
    }

    public function setKillstreak($player, $int){
        $this->plugin->main->exec("UPDATE essentialstats SET killstreak='$int' WHERE player='".Main::getPlayerName($player)."';");
    }
    public function setBestKillstreak($player, $int){
        $this->plugin->main->exec("UPDATE essentialstats SET bestkillstreak='$int' WHERE player='".Main::getPlayerName($player)."';");
    }

    public function topKillstreaks(string $viewer){
        $query=$this->plugin->main->query("SELECT * FROM essentialstats ORDER BY killstreak DESC LIMIT 10;");
        $message="";
        $i=0;
        while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
            $j=$i + 1;
            $player2=$resultArr['player'];
            $val=$this->getKillstreak($player2);

                if($j===1){
                    $message.="§c#1 §7".$player2." §7-§d §o".$val."\n";
                }
                if($j===2){
                    $message.="§e#2 §7".$player2." §7-§d §o".$val."\n";
                }
                if($j===3){
                    $message.="§a#3 §7".$player2." §7-§d §o".$val."\n";
                }
                if($j!==1 and $j!==2 and $j!==3){
                    $message.="§7#".$j." §7".$player2." §7-§d §o".$val."\n";
                }
                ++$i;
            }
        return$message;
    }

    public function setKills($player, $int){
        $this->plugin->main->exec("UPDATE essentialstats SET kills='$int' WHERE player='".Utils::getPlayerName($player)."';");
    }

    public function topKills(string $viewer){
        $query=$this->plugin->main->query("SELECT * FROM essentialstats ORDER BY kills DESC LIMIT 10;");
        $message="";
        $i=0;
        while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
            $j=$i + 1;
            $player=$resultArr['player'];
            $val=$this->getKills($player);
                if($j===1){
                    $message.="§c#1 §7".$player." §7-§d §o".$val."\n";
                }
                if($j===2){
                    $message.="§e#2 §7".$player." §7-§d §o".$val."\n";
                }
                if($j===3){
                    $message.="§a#3 §7".$player." §7-§d §o".$val."\n";
                }
                if($j!==1 and $j!==2 and $j!==3){
                    $message.="§7#".$j." §7".$player." §7-§d §o".$val."\n";
                }
                ++$i;
        }
        return$message;
    }


}
