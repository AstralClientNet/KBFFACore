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

class DropParty extends Task
{

    public function __construct(Main $plugin){

        $this->plugin = $plugin;

    }

    public function onRun(int $tick)
    {

        //compile how much

        $thefinalMuch = rand(1, 15);


        $theFinalItem = rand(1, 5);

        //set the !!!!! ITEM!!!!!
        if ($theFinalItem == 1) {

            $theitem = Item::get(368, 0, (int)$thefinalMuch);

        } elseif ($theFinalItem == 2) {

            $theitem = Item::get(262, 0, (int)$thefinalMuch);

        } elseif ($theFinalItem == 3) {

            $theitem = Item::get(332, 0, (int)$thefinalMuch);

        } elseif ($theFinalItem == 5) {

            $theitem = Item::get(268, 0, 1);
            $kb = Enchantment::getEnchantment(9);
            $theitem->addEnchantment(new EnchantmentInstance($kb, 32000));
            $theitem->setDamage(59);
            $theitem->setCustomName("DaBaby");

        } elseif ($theFinalItem == 4) {

            $theitem = Item::get(24, 0, (int)$thefinalMuch);

        }


        //THE DROP!!!!!!!!!!
        $level = Main::getInstance()->getServer()->getLevelByName("kbstick1");
        $pos = new Vector3(251, 76, 176);
        $level->dropItem($pos, $theitem);

        //yusz
            Main::getInstance()->getServer()->broadcastMessage("§kll§r  §7Item(s) with the amount of §c" . (int)$thefinalMuch . " §7has been spawned at middle!  §r§kll");
    }
}