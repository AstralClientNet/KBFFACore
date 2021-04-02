<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use TheBarii\KnockbackFFA\Listeners\PlayerListener;
use TheBarii\KnockbackFFA\Main;
use pocketmine\math\Vector3;
use TheBarii\KnockbackFFA\Sounds\Sounds;

class Countdown extends Task{

    public $num;
    public $player;

    public function __construct($num, Player $player){

        $this->num = $num;
        $this->player = $player;

    }

    public function onRun(int $tick)
    {
        if (!$this->num == 0){
            if(!$this->player == null) {
                $this->player->addTitle("§l§cYOU DIED!", "§aRespawning In §b$this->num §aseconds.", 20, 60, 40);
                $this->player->getInventory()->clearAll();
                Sounds::tickSound($this->player);
            }
      }else {
            if(!$this->player == null) {
                $this->player->setGamemode(0);
                PlayerListener::setItems($this->player);
                $this->player->teleport(new Vector3(244, 88, 182, 0, 0, Main::getInstance()->getServer()->getLevelByName("kbstick1")));
                $this->player->setTagged(null);
                Sounds::spawnSound($this->player);
            }
        }
    }
}