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

    public function topKillstreaks(string $viewer){
        $query=$this->plugin->main->query("SELECT * FROM essentialstats ORDER BY bestkillstreak DESC LIMIT 10;");
        $message="";
        $i=0;
        while($resultArr=$query->fetchArray(SQLITE3_ASSOC)){
            $j=$i + 1;
            $player2=$resultArr['player'];
            $val=$this->getBestKillstreak($player2);

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


}