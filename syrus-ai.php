<?php
/**
 * Plugin Name:       Syrus AI
 * Plugin URI:        https://syrusindustry.com
 * Description:       Syrus Plugin for AI Support on your website
 * Version:           0.4.3
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Syrus Industry, Daniele Di Rollo, Paolo Mondillo, Cristiano De Luca, Andrea Carlizza, Marco Lorica
 * Author URI:        https://syrusindustry.com/
 * Text Domain:       syrus-ai
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
    Syrus AI is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    Syrus AI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Syrus AI. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

if(!defined('ABSPATH'))
    exit;

if(!function_exists('initSyrusAIPlugin')) {
    function initSyrusAIPlugin() {
        global $syrusAIPlugin;

        if(!isset($syrusAIPlugin)) {
            $syrusAIPlugin = new SyrusAI_Plugin();
            $syrusAIPlugin->initialize();
        }

        return $syrusAIPlugin;
    }
}

require_once "load.php";