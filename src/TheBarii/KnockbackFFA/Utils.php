<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\entity\EntityIds;
use pocketmine\math\AxisAlignedBB;
use pocketmine\level\Position;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\SplashPotion as DefaultSplashPotion;
use pocketmine\entity\projectile\SplashPotion as ProjectileSplashPotion;
use pocketmine\item\MushroomStew;
use pocketmine\item\EnderPearl;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\utils\Color;
use TheBarii\KnockbackFFA\Main;


class Utils{

    private static $instance;


    public function __construct(Main $plugin){
        $this->plugin=$plugin;

    }

    public static function updateStats($player, int $reason){
        switch($reason){
            case 0:
                $oplayer=Main::getPlayer($player);
                $kills=Main::getInstance()->getDatabaseHandler()->getKills($player);
                $killstreak=Main::getInstance()->getDatabaseHandler()->getKillstreak($player);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, $killstreak + 1);
                Main::getInstance()->getDatabaseHandler()->setKills($player, $kills+ 1);
                $bestkillstreak=Main::getInstance()->getDatabaseHandler()->getBestKillstreak($player);
                $newkillstreak=Main::getInstance()->getDatabaseHandler()->getKillstreak($player);
                if($newkillstreak >= $bestkillstreak){
                    Main::getInstance()->getDatabaseHandler()->setBestKillstreak($player, $newkillstreak);
                }
                if(!is_null($oplayer)) $oplayer->sendMessage("§aYou are on a killstreak of ".$newkillstreak."!");
                break;
            case 1:
                $oplayer=Main::getPlayer($player);
                $deaths=Main::getInstance()->getDatabaseHandler()->getDeaths($player);
                $killstreak=Main::getInstance()->getDatabaseHandler()->getKillstreak($player);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
                if(!is_null($oplayer) and $killstreak > 0) $oplayer->sendMessage("§cYou lost your killstreak of ".$killstreak."!");
                break;
            case 2:
                $deaths=Main::getInstance()->getDatabaseHandler()->getDeaths($player);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
                break;
            default:
                return;
        }
    }


    public static function spawnUpdatingTextsToPlayer($player){
        $player=Main::getPlayer($player);
        if(is_null($player)) return;
            $title = "§5§lTop Killstreaks §c§lLeaderboard";
            $plugin = Main::getInstance();
            $ft = $plugin->loadUpdatingFloatingTexts();
            $ks = $plugin->getDatabaseHandler()->topKillstreaks($player->getName());
            $level=Main::getInstance()->getServer()->getLevelByName("kbstick1");
            $ft->setTitle($title);
            $ft->setText($ks);
            $level->addParticle($ft, [$player]);
        }

}