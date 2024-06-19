<?php

namespace KumaDev\HideSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use KumaDev\HideSystem\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use KumaDev\HideSystem\Event\HideManager;

class Main extends PluginBase implements Listener {

    private Config $config;
    private HideManager $hideManager;

    public function onEnable(): void {
        $this->saveResource('config.yml');
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $this->hideManager = new HideManager();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return true;
        }

        switch ($command->getName()) {
            case "hidesystem":
            case "hsystem":
            case "hs":
                $this->openMainForm($sender);
                return true;
            default:
                return false;
        }
    }

    public function openMainForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    $this->openHideNameTagForm($player);
                    break;
                case 1:
                    $this->openHidePlayerForm($player);
                    break;
            }
        });

        $form->setTitle($this->config->get("forms")["main_form"]["title"]);
        $form->setContent($this->config->get("forms")["main_form"]["content"]);
        $form->addButton($this->config->get("forms")["main_form"]["buttons"]["hide_nametag"], 0, "textures/items/name_tag");
        $form->addButton($this->config->get("forms")["main_form"]["buttons"]["hide_player"], 0, "textures/ui/icon_multiplayer");
        $player->sendForm($form);
    }

    public function openHideNameTagForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    if ($this->hideManager->isNameTagHidden($player)) {
                        $player->sendMessage($this->config->get("messages")["already_hidden_nametag"]);
                    } else {
                        $this->hideManager->hideNameTags($player);
                        $player->sendMessage($this->config->get("messages")["hide_nametag_success"]);
                    }
                    break;
                case 1:
                    if (!$this->hideManager->isNameTagHidden($player)) {
                        $player->sendMessage($this->config->get("messages")["already_visible_nametag"]);
                    } else {
                        $this->hideManager->showNameTags($player);
                        $player->sendMessage($this->config->get("messages")["remove_nametag_success"]);
                    }
                    break;
            }
        });

        $form->setTitle($this->config->get("forms")["hidename_tag_form"]["title"]);
        $form->setContent($this->config->get("forms")["hidename_tag_form"]["content"]);
        $form->addButton($this->config->get("forms")["hidename_tag_form"]["buttons"]["hide"], 0, "textures/ui/invisibility_effect");
        $form->addButton($this->config->get("forms")["hidename_tag_form"]["buttons"]["remove"], 0, "textures/ui/trash");
        $player->sendForm($form);
    }

    public function openHidePlayerForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    if ($this->hideManager->isPlayerHidden($player)) {
                        $player->sendMessage($this->config->get("messages")["already_hidden_player"]);
                    } else {
                        $this->hideManager->hidePlayers($player);
                        $player->sendMessage($this->config->get("messages")["hide_player_success"]);
                    }
                    break;
                case 1:
                    if (!$this->hideManager->isPlayerHidden($player)) {
                        $player->sendMessage($this->config->get("messages")["already_visible_player"]);
                    } else {
                        $this->hideManager->showPlayers($player);
                        $player->sendMessage($this->config->get("messages")["remove_player_success"]);
                    }
                    break;
            }
        });

        $form->setTitle($this->config->get("forms")["hide_player_form"]["title"]);
        $form->setContent($this->config->get("forms")["hide_player_form"]["content"]);
        $form->addButton($this->config->get("forms")["hide_player_form"]["buttons"]["hide"], 0, "textures/ui/invisibility_effect");
        $form->addButton($this->config->get("forms")["hide_player_form"]["buttons"]["remove"], 0, "textures/ui/trash");
        $player->sendForm($form);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->hideManager->onPlayerJoin($player);
    }
}
