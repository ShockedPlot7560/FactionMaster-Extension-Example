<?php

/*
 *
 *      ______           __  _                __  ___           __
 *     / ____/___ ______/ /_(_)___  ____     /  |/  /___ ______/ /____  _____
 *    / /_  / __ `/ ___/ __/ / __ \/ __ \   / /|_/ / __ `/ ___/ __/ _ \/ ___/
 *   / __/ / /_/ / /__/ /_/ / /_/ / / / /  / /  / / /_/ (__  ) /_/  __/ /  
 *  /_/    \__,_/\___/\__/_/\____/_/ /_/  /_/  /_/\__,_/____/\__/\___/_/ 
 *
 * FactionMaster - A Faction plugin for PocketMine-MP
 * This file is part of FactionMaster and is an extension
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @author ShockedPlot7560 
 * @link https://github.com/ShockedPlot7560
 * 
 *
*/

namespace ShockedPlot7560\FactionMasterExemple;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use ShockedPlot7560\FactionMaster\Button\Button;
use ShockedPlot7560\FactionMaster\Button\ButtonFactory;
use ShockedPlot7560\FactionMaster\Button\Collection\MainCollectionFac;
use ShockedPlot7560\FactionMaster\Extension\Extension;
use ShockedPlot7560\FactionMaster\Main as FactionMasterMain;
use ShockedPlot7560\FactionMaster\Permission\Permission;
use ShockedPlot7560\FactionMaster\Router\RouterFactory;
use ShockedPlot7560\FactionMaster\Utils\Utils;

/**
 * The main class must implement the Extension interface.
 */
class Main extends PluginBase implements Extension{

    const PERMISSION_EXEMPLE = "PERMISSION_EXEMPLE";
    const PERMISSION_EXEMPLE_ID = 10;

    private $LangConfig = [];

    public function onLoad()
    {
        FactionMasterMain::getInstance()->getExtensionManager()->registerExtension($this);

        @mkdir($this->getDataFolder());
        $this->saveResource('fr_FR.yml');
        $this->LangConfig = [
            "fr_FR" => new Config($this->getDataFolder() . "fr_FR.yml", Config::YAML)
        ];
    }

    /**
     * Function that will be called when loading the extension
     */
    public function execute(): void
    {
        /**
         * Use the RouterFactory to register new menus
         */
        RouterFactory::registerRoute(new ExemplePanel());
        /**
         * Use the ButtonFactory to register new button collections
         */
        ButtonFactory::register(new ExempleCollection());
        /**
         * Use the PermissionManager to register new permissions 
         * that will be proposed to the players in the appropriate menu
         */
        $PermissionManager = FactionMasterMain::getInstance()->getPermissionManager();
        $PermissionManager->registerPermission(new Permission(
            self::PERMISSION_EXEMPLE,
            function(string $playerName) { 
                return "Using the Exemple button"; 
            },
            self::PERMISSION_EXEMPLE_ID
        ), true);

        /**
         * To modify an existing menu, get its instance and add a function 
         * that will be called when creating the menu for the player.
         */
        $MainCollectionFac = ButtonFactory::get(MainCollectionFac::SLUG);
        $MainCollectionFac->registerCallable("FactionMasterBank", function() use ($MainCollectionFac) {
            $MainCollectionFac->register(new Button(
                "exempleButton", //the button slug
                function () {
                    return "Exemple Button"; //Function called to create the content of the button
                },
                function (Player $Player) { //Function called when the player clicks on the button
                    Utils::processMenu(RouterFactory::get(ExemplePanel::SLUG), $Player, ["Its a message"]);
                }
            ), 0);
        });
    }

    public function getLangConfig(): array
    {
        return $this->LangConfig;
    }

    public function getExtensionName() : string {
        return 'factionExample';
    }
}