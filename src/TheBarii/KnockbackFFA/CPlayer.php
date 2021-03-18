<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\network\SourceInterface;
use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;

class CPlayer extends Player{

    public $re=null;
    public $tag=null;

    public function setRe($player){
        $re=$player;
        $this->re=($re!=null ? $re->getName():"");
    }
    public function hasRe():bool{
        if($this->re===null) return false;
        $re=$this->getRe();
        if($re===null) return false;
        $player=$this->getRe();
        return $player!==null;
    }
    public function getRe(){
        return Server::getInstance()->getPlayerExact($this->re);
    }

    public function setTagged($player){

        $tag=$player;
        $this->tag=($tag!=null ? $tag->getName():"");

    }

    public function hasTagged():bool{
        if($this->re===null) return false;
        $tag=$this->getTagged();
        if($tag===null) return false;
        $player=$this->getTagged();
        return $player!==null;

    }

    public function getTagged(){

        return Server::getInstance()->getPlayerExact($this->tag);
    }


}