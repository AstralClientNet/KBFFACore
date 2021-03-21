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

    


}