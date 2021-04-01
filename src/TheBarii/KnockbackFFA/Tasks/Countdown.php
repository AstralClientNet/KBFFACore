<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;

class Countdown extends Task{

    public $num;
    public $player;

    public function __construct($num, Player $player){

        $this->num = $num;
        $this->player = $player;

    }

    public function onRun(int $tick){
            $this->player->setTitle("");
            $this->player->addTitle("§l§cYOU DIED!", "§aRespawning In §b$this->num §aseconds.", 20, 60, 40);
    }

}