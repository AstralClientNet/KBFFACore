<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Server;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
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
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use TheBarii\KnockbackFFA\Utils;
use pocketmine\item\Durable;


class PlayerListener implements Listener{


    public function __construct(Main $plugin){
        $this->plugin=$plugin;
        $this->text = new FloatingTextParticle(new Vector3(242, 90, 182), "", "");
        $this->text2 = new FloatingTextParticle(new Vector3(244, 90, 184), "", "");
        $this->text3 = new FloatingTextParticle(new Vector3(224, 69, 189), "", "");
        $this->text4 = new FloatingTextParticle(new Vector3(240, 70, 204), "", "");
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
        $p->sendMessage("§4§l─────────────────────────────\n§r§k§5l§dl§r  §fWelcome to §bKnockback FFA!  §r§k§5l§dl§r\n§7Blocks reset every 5 seconds after you place them.\n§r§cYou cannot do anything at spawn. Go down!\n§r§71 §r§4kill §7will give you one extra §r§6arrow§7, 3 §rsnowballs§7, §r§7and an §r§bender pearl.\n§7There are §crandom §7item drops at middle.\n\n§7Discord: https://astralclient.net/discord/\n§l§4─────────────────────────────\n§r§l§6Teaming is not allowed!");
        $this->setItems($p);


        $x = 244;
        $y = 88;
        $z = 182;

        $level = $this->plugin->getServer()->getLevelByName("kbstick1");
        $p->teleport(new Vector3($x, $y, $z, 0, 0, $level));

            $this->plugin->getScoreboardHandler()->scoreboard($this);
            $this->loadUpdatingFloatingTexts($p);
            $this->loadUpdatingFloatingTexts2($p);
            $this->loadUpdatingFloatingTexts3($p);
            $this->loadUpdatingFloatingTexts4($p);
    }

    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        if ($player instanceof CPlayer) {
            $player->initializeLogin();
        }else{
            $this->plugin->getDatabaseHandler()->essentialStatsAdd(Main::getPlayerName($player));
        }
    }

    public function loadUpdatingFloatingTexts(Player $player)
    {
            $title = "§5§lTop Killstreaks §c§lLeaderboard";
            $ks = $this->plugin->getDatabaseHandler()->topKillstreaks($player->getName());

            $this->text->setTitle($title);
            $this->text->setText($ks);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text);
    }//

    public function loadUpdatingFloatingTexts2(Player $player): void
    {

            $title = "§5§lTop Kills §c§lLeaderboard";
            $k = $this->plugin->getDatabaseHandler()->topKills($player->getName());

            $this->text2->setTitle($title);
            $this->text2->setText($k);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text2);
    }

    public function loadUpdatingFloatingTexts3(Player $player): void
    {
            $title3 = "§5§lGenerator";
            $this->text3->setTitle($title3);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text3);
    }

    public function loadUpdatingFloatingTexts4(Player $player): void
    {

            $title4 = "§5§lGenerator";
            $this->text4->setTitle($title4);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text4);
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
        }elseif($n == "Mo8rty"){
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
    $e->setDrops(array());

        if ($p instanceof Player) {
            $cause = $p->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();

                if ($damager instanceof Player) {
                    $finalhealth = round($damager->getHealth(), 1);
                    if($damager->isAlive()) {
                        $damager->setHealth($damager->getMaxHealth());
                    }
                    $dn = $damager->getName();
                    if ($dn == $pn) {
                        $dm = "§c$pn died to the void.";
                        $e->setDeathMessage($dm);

                    }else{
                        if($damager->isAlive()) {
                            $damager->getInventory()->addItem(Item::get(368, 0, 1));
                            $damager->getInventory()->addItem(Item::get(262, 0, 1));
                            $damager->getInventory()->addItem(Item::get(332, 0, 3));
                        }
                        if($damager instanceof CPlayer) Utils::updateStats($damager, 0);
                        if($p instanceof CPlayer) Utils::updateStats($p, 1);
                        $messages = ["quickied", "railed", "clapped", "killed", "smashed", "OwOed", "UwUed", "sent to the heavens"];
                        $dm = "§e$pn §7was " . $messages[array_rand($messages)] . " by §c$dn §7[" . $finalhealth . " HP]";
                        if($damager->isAlive()) {
                            $damager->setHealth($damager->getMaxHealth());
                        }
                        $this->levelupSound($damager);
                        $e->setDeathMessage($dm);
                    }
                    if(Main::getInstance()->getDatabaseHandler()->getKillstreak($damager) >= 5){
                        $this->plugin->getServer()->broadcastMessage("§c".$damager->getName()." §7just got a killstreak of §6".Main::getInstance()->getDatabaseHandler()->getKillstreak($damager)."!");
                    }

                }
            }
        }

        $title = "§5§lTop Killstreaks §c§lLeaderboard";
        $ks = $this->plugin->getDatabaseHandler()->topKillstreaks($p->getName());

        $this->text->setTitle($title);
        $this->text->setText($ks);
        $level = $this->plugin->getServer()->getLevelByName("kbstick1");
        $level->addParticle($this->text);


                $title2 = "§5§lTop Kills §c§lLeaderboard";
                $k = $this->plugin->getDatabaseHandler()->topKills($p->getName());

                $this->text2->setTitle($title2);
                $this->text2->setText($k);
                $level = $this->plugin->getServer()->getLevelByName("kbstick1");
                $level->addParticle($this->text2);

        $this->plugin->getScoreboardHandler()->scoreboard($this);
        }

    public static function levelupSound(Player $player)
    {
        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "random.levelup";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;

        $player->dataPacket($sound1);

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
     * @priority HIGHEST
     */
    public function onMove(PlayerMoveEvent $e){
        $p = $e->getPlayer();
        $y = $p->getFloorY();
        if($y < 45){

            $x = 244;
            $y = 88;
            $z = 182;

            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $p->teleport(new Vector3($x, $y, $z, 0, 0, $level));
            $p->kill();


            if($p instanceof CPlayer) Utils::updateStats($p, 1);
            $title = "§5§lTop Killstreaks §c§lLeaderboard";
            $ks = $this->plugin->getDatabaseHandler()->topKillstreaks($p->getName());

            $this->text->setTitle($title);
            $this->text->setText($ks);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text);


            $title = "§5§lTop Kills §c§lLeaderboard";
            $k = $this->plugin->getDatabaseHandler()->topKills($p->getName());

            $this->text2->setTitle($title);
            $this->text2->setText($k);
            $level = $this->plugin->getServer()->getLevelByName("kbstick1");
            $level->addParticle($this->text2);


            if($p->hasTagged()){
                $whoTagged = $p->getTagged();
                if($whoTagged->isAlive()) {
                    $whoTagged->getInventory()->addItem(Item::get(368, 0, 1));
                    $whoTagged->getInventory()->addItem(Item::get(262, 0, 1));
                }
                if($whoTagged instanceof CPlayer) Utils::updateStats($whoTagged, 0);
                if($p instanceof CPlayer) Utils::updateStats($p, 1);
            }
        }
    }
    /**
     * @priority HIGHEST
     */
    public function onDrop(PlayerDropItemEvent $e){
        $p = $e->getPlayer();
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
        if($p->getGamemode() == 0) {
            if ($y > 79) {
                $e->setCancelled();
            }
        }
    }

    /**
     * @priority LOW
     */
    public function onRespawn(PlayerRespawnEvent $e){
        $p = $e->getPlayer();
        $this->setItems($p);
        $p->setTagged(null);
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
    public function setItems(Player $p)
    {

        //get items
        $sword = Item::get(268, 0, 1);
        $stick = Item::get(280, 0, 1);
        $bow = Item::get(261, 0, 1);
        $stone = Item::get(24, 0, 64);
        $enderpearl = Item::get(368, 0, 1);
        $arrow = Item::get(262, 0, 1);
        $cob = Item::get(332, 0, 10);
        $helm = Item::get(298);
        $chest = Item::get(299);
        $pant = Item::get(300);
        $boot = Item::get(301);
        $pick = Item::get(270);

        $allitems = array($sword, $pick, $stick, $bow, $stone, $enderpearl, $arrow, $cob, $helm, $chest, $pant, $boot);

        foreach ($allitems as $itemz) {
            if ($itemz instanceof Durable) {
                $itemz->setUnbreakable();
            }
        }

        //enchants
        $sharpness = Enchantment::getEnchantment(9);
        $prot = Enchantment::getEnchantment(0);
        $kb = Enchantment::getEnchantment(12);
        $eff = Enchantment::getEnchantment(15);
        $punch = Enchantment::getEnchantment(20);

        //enchant items
        $stick->addEnchantment(new EnchantmentInstance($kb, 1));
        $bow->addEnchantment(new EnchantmentInstance($punch, 2));
        $helm->addEnchantment(new EnchantmentInstance($prot, 4));
        $chest->addEnchantment(new EnchantmentInstance($prot, 4));
        $boot->addEnchantment(new EnchantmentInstance($prot, 4));
        $pant->addEnchantment(new EnchantmentInstance($prot, 4));
        $pick->addEnchantment(new EnchantmentInstance($eff, 5));
        $pick->addEnchantment(new EnchantmentInstance($sharpness, 2));

        //set sum shit
        $p->extinguish();
        $p->setScale(1);
        $p->setGamemode(0);
        $p->setMaxHealth(20);
        $p->getInventory()->setSize(30);
        $p->getInventory()->clearAll();
        $p->getArmorInventory()->clearAll();

        //set items
        $p->getInventory()->setItem(0, $stick);
        $p->getInventory()->setItem(8, $bow);
        $p->getInventory()->setItem(2, $enderpearl);
        $p->getInventory()->setItem(5, $stone);
        $p->getInventory()->setItem(6, $arrow);
        $p->getInventory()->setItem(4, $cob);
        $p->getInventory()->setItem(1, $pick);


        //set armor inv
        $p->getArmorInventory()->setHelmet($helm);
        $p->getArmorInventory()->setChestplate($chest);
        $p->getArmorInventory()->setLeggings($pant);
        $p->getArmorInventory()->setBoots($boot);

    }

}