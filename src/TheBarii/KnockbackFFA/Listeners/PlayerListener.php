<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\event\block\BlockPlaceEvent;
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
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\EntityShootBowEvent;


class PlayerListener implements Listener{

    public function __construct(Main $plugin){
        $this->plugin=$plugin;
    }


    /**
     * @priority HIGHEST
     */
    function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(CPlayer::class);
    }
    /**
     * @priority HIGHEST
     */
    public function onJoin(PlayerJoinEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setJoinMessage("§r§d+§r§a $n");
        $p->sendMessage("§4§l─────────────────────────────\n§r§k§5l§dl§r  §fWelcome to §bKnockback FFA!  §r§k§5l§dl§r\n§7Blocks reset every twenty seconds after you place them.\n§r§71 §r§4kill §7will give you one extra §r§6arrow§7, a §rcobweb§7, §r§7and an §r§bender pearl.\n\n§7Discord: https://astralclient.net/discord/\n§l§4─────────────────────────────\n§r§l§6Teaming is not allowed!");
        $this->setItems($p);


        $x = 244;
        $y = 88;
        $z = 182;

        $level = $this->plugin->getServer()->getLevelByName("kbstick1");
        $p->teleport(new Vector3($x, $y, $z, 0, 0, $level));
    }

    /**
     * @priority LOW
     */

    public function onChat(PlayerChatEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $msg = $e->getMessage();
        if(!$n == "TheBarii") {
            $e->setFormat("§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "TheBarii"){
            $e->setFormat("§4Owner §r§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "Argued168"){
            $e->setFormat("§4Administrator §r§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "Mo8rty22"){
            $e->setFormat("§4Owner §r§5[§r§6 $n §d] §7§r$msg");
        }else{
            $e->setFormat("§r§5[§r§6 $n §d] §7§r$msg");
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onCraft(CraftItemEvent $e){
        $e->setCancelled();
    }
    public function onQuit(PlayerQuitEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setQuitMessage("§r§c-§r§c $n");
    }
    /**
     * @priority HIGHEST
     */
    public function onDeath(PlayerDeathEvent $e){
    $p = $e->getPlayer();
    $pn = $p->getName();
    $this->setItems($p);
        if ($p instanceof Player) {
            $cause = $p->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    $this->setItems($p);
                    $finalhealth = round($damager->getHealth(), 1);
                    $dn = $damager->getName();
                    if ($dn == $pn) {
                        $dm = "§c$pn died to the void.";
                        $e->setDeathMessage($dm);

                    } else {
                        $damager->getInventory()->addItem(Item::get(368, 0, 1));
                        $damager->getInventory()->addItem(Item::get(262, 0, 1));
                        $messages = ["quickied", "railed", "clapped", "killed", "smashed", "OwOed", "UwUed", "sent to the heavens"];
                        $dm = "§e$pn §7was " . $messages[array_rand($messages)] . " by §c$dn §7[" . $finalhealth . " HP]";
                        $e->setDeathMessage($dm);
                    }
                }
            }
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onMove(PlayerMoveEvent $e){
        $p = $e->getPlayer();
        $y = $p->getFloorY();
        if($y < 45){
            $p->kill();
            if($p->hasTagged()){
                $whoTagged = $p->getTagged();
                $whoTagged->getInventory()->addItem(Item::get(368, 0, 1));
                $whoTagged->getInventory()->addItem(Item::get(262, 0, 1));
            }
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onArrowHit(ProjectileHitEvent $e){
        $p = $e->getEntity();
        $y = $p->getFloorY();
        if($y > 79) {
            $e->setCancelled();
        }
    }

    /**
     * @priority HIGHEST
     */
    public function onUse(PlayerInteractEvent $e){
        $p = $e->getPlayer();
        $y = $p->getFloorY();
        if($y > 79) {
            $e->setCancelled();
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onShoot(ProjectileLaunchEvent $e){
        $p = $e->getEntity();
        $y = $p->getFloorY();
        if($y > 79){
            $e->setCancelled();
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onShoot2(EntityShootBowEvent $e){
        $p = $e->getEntity();
        $y = $p->getFloorY();
        if($y > 79){
            $e->setCancelled();
        }
    }
    /**
     * @priority HIGH
     */
    public function onPlace(BlockPlaceEvent $e){
        $p = $e->getPlayer();
        $y = $p->getFloorY();
        if($y > 79){
            $e->setCancelled();
        }
    }

    /**
     * @priority LOW
     */
    public function onRespawn(PlayerRespawnEvent $e){
        $p = $e->getPlayer();
        $this->setItems($p);
    }
    /**
     * @priority HIGHEST
     */
    public function onHit(EntityDamageByEntityEvent $e){
        $p = $e->getEntity();
        if($e->getDamager() instanceof Player) {
            $player = $e->getDamager();
            $p->setTagged($player);
        }
        $y = $p->getFloorY();
        if($y > 79) {
            $e->setCancelled();
        }
    }
    /**
     * @priority LOWEST
     */
    public function onExhaust(PlayerExhaustEvent $event){
        $event->setCancelled();
    }
    /**
     * @priority LOWEST
     */
    public function onDamage(EntityDamageEvent $ev){
        if($ev->getCause() === EntityDamageEvent::CAUSE_FALL){
            $ev->setCancelled();
        }
    }
    public function setItems(Player $p){

        //get items
        $sword = Item::get(268, 0, 1);
        $stick = Item::get(280, 0, 1);
        $bow = Item::get(261, 0, 1);
        $stone = Item::get(24, 0, 64);
        $enderpearl = Item::get(368, 0, 1);
        $arrow = Item::get(262, 0, 1);
        $cob = Item::get(30, 0, 1);
        $helm = Item::get(298);
        $chest = Item::get(299);
        $pant = Item::get(300);
        $boot = Item::get(301);

        //enchants
        $sharpness = Enchantment::getEnchantment(9);

        $prot = Enchantment::getEnchantment(0);

        $kb = Enchantment::getEnchantment(12);

        $eff = Enchantment::getEnchantment(15);

        $punch = Enchantment::getEnchantment(20);

        //enchant items

        $stick->addEnchantment(new EnchantmentInstance($kb, 1));
        $sword->addEnchantment(new EnchantmentInstance($sharpness, 1));
        $bow->addEnchantment(new EnchantmentInstance($punch, 1));
        $helm->addEnchantment(new EnchantmentInstance($prot, 1));
        $chest->addEnchantment(new EnchantmentInstance($prot, 1));
        $boot->addEnchantment(new EnchantmentInstance($prot, 1));
        $pant->addEnchantment(new EnchantmentInstance($prot, 1));

        //set sum shit
        $p->extinguish();
        $p->setScale(1);
        $p->setGamemode(2);
        $p->getInventory()->setSize(36);
        $p->getInventory()->clearAll();
        $p->getArmorInventory()->clearAll();

        //set items
        $p->getInventory()->setItem(0, $sword);
        $p->getInventory()->setItem(1, $stick);
        $p->getInventory()->setItem(8, $bow);
        $p->getInventory()->setItem(2, $enderpearl);
        $p->getInventory()->setItem(5, $stone);
        $p->getInventory()->setItem(6, $arrow);
        $p->getInventory()->setItem(4, $cob);




        //set armor inv
        $p->getArmorInventory()->setHelmet($helm);
        $p->getArmorInventory()->setChestplate($chest);
        $p->getArmorInventory()->setLeggings($pant);
        $p->getArmorInventory()->setBoots($boot);

    }

}