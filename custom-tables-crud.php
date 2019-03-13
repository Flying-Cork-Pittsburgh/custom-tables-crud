<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Custom Tables CRUD Plugin
 * Plugin URI:        https://github.com/piotrku/custom-tables-crud
 * Description:       A boilerplate for WordPress plugins
 * Version:           0.0.1
 * Author:            Piotr Kubiak
 * Author URI:        https://2pi.pl
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * Text Domain:       custom-tables-crud
 * Domain Path:       languages
 * GitHub Plugin URI: piotrku/custom-tables-crud
 */

/*	Copyright 2018	  Piotr Kubiak (https://2pi.pl/)

		This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (!defined('ABSPATH')) die();

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require(__DIR__ . '/vendor/autoload.php');
}

// Initialize plugin
\PiotrKu\CustomTablesCrud\Plugin::instance();
