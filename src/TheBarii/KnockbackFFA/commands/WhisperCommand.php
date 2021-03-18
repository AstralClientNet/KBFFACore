<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\commands;

use pocketmine\Player;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use TheBarii\KnockbackFFA\Main;
use TheBarii\KnockbackFFA\CPlayer;

class WhisperCommand extends PluginCommand{
	
	private $plugin;
	
	public function __construct(Main $plugin){
		parent::__construct("whisper", $plugin);
		$this->plugin=$plugin;
		$this->setAliases(["w"]);
        $this->setAliases(["msg"]);
	}
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player instanceof Player){
			return;
		}
		if(!isset($args[0])){
			$player->sendMessage("§cYou must provide a player.");
			return;
		}
		if($this->plugin->getServer()->getPlayer($args[0])===null){
			$player->sendMessage("§cPlayer not found.");
			return;
		}
		if(count($args) < 2){
			$player->sendMessage("§cYou must provide a message.");
			return;
		}
		$target=$this->plugin->getServer()->getPlayer(array_shift($args));
		$message=implode(" ", $args);
		$sn=$player->getDisplayName();
		$tn=$target->getDisplayName();
		if($target instanceof Player){
			$player->sendMessage("§7(To §f".$tn."§7) ".$message);
			$target->sendMessage("§7(From §f".$sn."§7) ".$message);
			$target->setRe($player);

		}
	}
}