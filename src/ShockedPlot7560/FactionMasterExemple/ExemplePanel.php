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
 * This file is part of FactionMaster
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

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use ShockedPlot7560\FactionMaster\Button\ButtonCollection;
use ShockedPlot7560\FactionMaster\Button\ButtonFactory;
use ShockedPlot7560\FactionMaster\Database\Entity\UserEntity;
use ShockedPlot7560\FactionMaster\Route\Route;

class ExemplePanel implements Route {

    const SLUG = "exemplePanel";

    /** @var UserEntity */
    private $UserEntity;
    /** @var ButtonCollection */
    private $Collection;

    public function getSlug(): string
    {
        return self::SLUG;
    }
    
    /**
     * Function called to send the menu
     */
    public function __invoke(Player $Player, UserEntity $User, array $UserPermissions, ?array $params = null){
        $this->UserEntity = $User;
        // Create a new instance of the button collection : ExampleCollection
        $this->Collection = ButtonFactory::get(ExempleCollection::SLUG)->init($Player, $User);
        $menu = $this->exempleMenu($params[0]);
        $Player->sendForm($menu);
    }

    public function call() : callable{
        $Collection = $this->Collection;
        return function (Player $Player, $data) use ($Collection) {
            if ($data === null) return;
            $Collection->process($data, $Player);
        };
    }

    private function exempleMenu(string $message) : SimpleForm {
        $menu = new SimpleForm($this->call());
        // To create our buttons, we call the built-in function of the button 
        // collection which will generate the buttons according to the player's permissions
        $menu = $this->Collection->generateButtons($menu, $this->UserEntity->name);
        $menu->setTitle("Exemple Panel");
        $menu->setContent($message);
        return $menu;
    }

}