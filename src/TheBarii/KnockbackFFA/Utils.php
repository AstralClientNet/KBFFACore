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

    public static function updateStats($player, int $reason){
        switch($reason){
            case 0:
                $oplayer=self::getPlayer($player);
                $kills=Main::getInstance()->getDatabaseHandler()->getKills($player);
                $dailykills=Main::getInstance()->getDatabaseHandler()->getDailyKills($player);
                $killstreak=Main::getInstance()->getDatabaseHandler()->getKillstreak($player);
                Main::getInstance()->getDatabaseHandler()->setKills($player, $kills+ 1);
                Main::getInstance()->getDatabaseHandler()->setDailyKills($player, $dailykills + 1);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, $killstreak + 1);
                $bestkillstreak=Core::getInstance()->getDatabaseHandler()->getBestKillstreak($player);
                $newkillstreak=Core::getInstance()->getDatabaseHandler()->getKillstreak($player);
                if($newkillstreak >= $bestkillstreak){
                    Main::getInstance()->getDatabaseHandler()->setBestKillstreak($player, $newkillstreak);
                }
                if(!is_null($oplayer)) $oplayer->sendMessage("§aYou are on a killstreak of ".$newkillstreak."!");
                break;
            case 1:
                $oplayer=self::getPlayer($player);
                $deaths=Main::getInstance()->getDatabaseHandler()->getDeaths($player);
                $dailydeaths=Main::getInstance()->getDatabaseHandler()->getDailyDeaths($player);
                $killstreak=Main::getInstance()->getDatabaseHandler()->getKillstreak($player);
                Main::getInstance()->getDatabaseHandler()->setDeaths($player, $deaths + 1);
                Main::getInstance()->getDatabaseHandler()->setDailyDeaths($player, $dailydeaths + 1);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
                if(!is_null($oplayer) and $killstreak > 0) $oplayer->sendMessage("§cYou lost your killstreak of ".$killstreak."!");
                break;
            case 2:
                $deaths=Core::getInstance()->getDatabaseHandler()->getDeaths($player);
                $dailydeaths=Core::getInstance()->getDatabaseHandler()->getDailyDeaths($player);
                Main::getInstance()->getDatabaseHandler()->setDeaths($player, $deaths + 1);
                Main::getInstance()->getDatabaseHandler()->setDailyDeaths($player, $dailydeaths + 1);
                Main::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
                break;
            default:
                return;
        }
    }


}