<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Tasks;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\ArmorInventoryEventProcessor;
use pocketmine\item\Armor;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use TheBarii\KnockbackFFA\Main;
use TheBarii\KnockbackFFA\CPlayer;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\scheduler\Task;
use pocketmine\block\Sandstone;
use pocketmine\block\Block;
use pocketmine\block\Air;

class DropSword extends Task
{

    public function onRun(int $tick)
    {
        $sword = Item::get(268, 0, 1);
        $sword->setCustomName("DaBaby");

        $much1 = 1;
        $much2 = 2;
        $much3 = 3;
        $much4 = 4;
        $much5 = 5;
        $much6 = 6;
        $much7 = 7;
        $much8 = 8;
        $much9 = 9;
        $much10 = 10;
        $much11 = 11;
        $much12 = 12;
        $much13 = 13;
        $much14 = 14;
        $much15 = 15;

        $howmuch = [$much1, $much2, $much3, $much4, $much5, $much6, $much7, $much8, $much9, $much10, $much11, $much12, $much13, $much14, $much15];

        $sword = Item::get(268, 0, 1);
        $stone = Item::get(24, 0, $howmuch[array_rand($howmuch)]);
        $enderpearl = Item::get(368, 0, $howmuch[array_rand($howmuch)]);
        $arrow = Item::get(262, 0, $howmuch[array_rand($howmuch)]);
        $cob = Item::get(332, 0, $howmuch[array_rand($howmuch)]);

        $kb = Enchantment::getEnchantment(12);
        $sword->addEnchantment($kb, 100);
        $pos1 = [240, 79, 204];
        $pos2 = [224, 79, 188];
        $pos3 = [249, 79, 178];
        $pos4 = [229, 74, 155];
        $pos5 = [264, 74, 174];

        $positions = [$pos1, $pos2, $pos3, $pos4, $pos5];

        $level = Main::getInstance()->getServer()->getLevelByName("kbstick1");
        $pos = new Vector3($positions[array_rand($positions)]);
        $level->dropItem($sword, $pos);


    }
}