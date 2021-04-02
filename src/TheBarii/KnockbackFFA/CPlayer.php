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
use TheBarii\KnockbackFFA\Main;

class CPlayer extends Player{

    public $re=null;
    public $tag=null;
    private $plugin;

    public function __construct(SourceInterface $interface, $ip, $port)
    {
        parent::__construct($interface, $ip, $port);
        $plugin = $this->getServer()->getPluginManager()->getPlugin("KnockbackFFA");
        if ($plugin instanceof Main) {
            $this->setPlugin($plugin);
        }
    }

    public function setPlugin($plugin){
        $this->plugin=$plugin;
    }

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
        if(!$player == null) {
            $tag = $player;
            $this->tag = ($tag != null ? $tag->getName() : "");
        }else{
            $this->tag = null;
        }
    }

    public function hasTagged():bool{
        if($this->tag===null) return false;
        $tag=$this->getTagged();
        if($tag===null) return false;
        $player=$this->getTagged();
        return $player!==null;

    }

    public function initializeLogin()
    {
        Main::getInstance()->getDatabaseHandler()->essentialStatsAdd(Main::getPlayerName($this));
    }

    public function getTagged(){

        return Server::getInstance()->getPlayerExact($this->tag);
    }


}