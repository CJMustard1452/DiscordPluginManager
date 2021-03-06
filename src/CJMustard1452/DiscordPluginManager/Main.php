<?php

declare(strict_types=1);

namespace CJMustard1452\DiscordPluginManager;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($sender instanceof Player == false){
			try{
				new Config($this->getDataFolder() . "password", Config::JSON);
				if(isset($args[0]) && $args[0] === file_get_contents($this->getDataFolder() . "password")){
					if(isset($args[1]) && ($args[1] === "link" || $args[1] === "add" || $args[1] === "dw" || $args[1] === "set")){
						if(isset($args[2]) && isset($args[3])){
								if(str_starts_with($args[3], "https://")){
									$path = $args[2];
									$arrContextOptions = [
										"ssl" => [
											"verify_peer" => false,
											"verify_peer_name" => false,
										],
									];
									$response = file_get_contents($args[3], false, stream_context_create($arrContextOptions));
									file_put_contents($path, $response);
									$sender->sendMessage("file contents from $args[3] have been placed into $args[2]");
								}else{
									$sender->sendMessage("This type of file is not supported.");
								}
						}else{
							$sender->sendMessage("/pm [pass] add [path] [file link]");
						}
					}elseif(isset($args[1]) && ($args[1] === "unlink" || $args[1] === "remove" || $args[1] === "delete" || $args[1] === "unset")){
						if(isset($args[2])){
							if(file_exists($args[2])){
								unlink($args[2]);
								$sender->sendMessage("$args[2] has been unlinked.");
							}else{
								$sender->sendMessage("File does not exist");
							}
						}else{
							$sender->sendMessage("/pm [pass] remove [path]");
						}
					}elseif(isset($args[1]) && ($args[1] === "l" || $args[1] === "help" || $args[1] === "list")){
						$sender->sendMessage("------------------------------- \n/pm [pass] add [path] [file link] - (adds a file to sent path) \n/pm [pass] remove [path] - (removes a file to sent path)\n/pm [pass] phars - (lists plugin files)\n/pm [pass] files [path] - (lists dirs in path)\n/pm [pass] help - (help page) \n/pm [pass] contents [path] - (shows file contents)\n-------------------------------");
					}elseif(isset($args[1]) && ($args[1] === "phars" || $args[1] === "lp")){
						file_put_contents($this->getDataFolder() . "tempinfo", str_replace(".phar", "", str_replace("plugins/", "", implode("??a | ", glob("plugins/*.phar")))));
						$sender->sendMessage(file_get_contents($this->getDataFolder() . "tempinfo"));
					}elseif(isset($args[1]) && ($args[1] === "files" || $args[1] === "lf")){
						if(isset($args[2])){
							if(file_exists($args[2]) && filetype($args[2]) == "dir"){
								file_put_contents($this->getDataFolder() . "tempinfo", implode("??a | ", scandir($args[2])));
								$sender->sendMessage(file_get_contents($this->getDataFolder() . "tempinfo"));
							}else{
								$sender->sendMessage("This file does not exist");
							}
						}else{
							$sender->sendMessage("/pm [pass] files");
						}
					}elseif(isset($args[1]) && ($args[1] === "openfile" || $args[1] === "contents")){
						if(isset($args[2])){
							if(file_exists($args[2])){
								file_put_contents($this->getDataFolder() . "tempinfo", file_get_contents($args[2]));
								$sender->sendMessage(file_get_contents($this->getDataFolder() . "tempinfo"));
							}else{
								$sender->sendMessage("This file does not exist.");
							}
						}else{
							$sender->sendMessage("/pm [pass] contents [path]");
						}
					}else{
						$sender->sendMessage("use /pm [pass] help");
					}
				}else{
					$sender->sendMessage("??can error has occurred");
				}
			}catch (\Throwable $error){
				$sender->sendMessage("Error: ".$error->getMessage());
			}
			}else{
				$sender->sendMessage("??can error has occurred");
		}
		return true;
	}
}
