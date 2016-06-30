<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));


/**
 * Global User-Defined Functions
 */
function html($str, $strip_tags = true){
  return htmlentities($strip_tags? strip_tags($str) : $str, ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
}

function is_pos_int($val){
  if(is_int($val))
    return ($val >= 0);
  else if(!is_string($val))
    return false;

  return ctype_digit($val);
}


/**
 * Returns the number as a string with two decimals
 * @param string $val
 */
function money($money){
  if(!is_numeric($money))
    return "0.00";

  return sprintf("%01.2f", $money);
}

function get_html($url){
  ini_set('max_execution_time', 300);
  $c = curl_init($url);
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_AUTOREFERER, true);
  curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
  $html = curl_exec($c);
  if (curl_error($c))
    die(curl_error($c));
  curl_close($c);
  return $html;
}

// Find first regex match in html source code
function get_first_match($html, $regex){
  if(preg_match($regex, $html, $regs)){
    if(isset($regs[2]))
      return $regs; // multiple capture groups
    else
      return $regs[1];
  }
  return false;
}

// Used to find ALL occurrences, not just the first one
function get_all_matches($html, $regex){
  preg_match_all($regex, $html, $result, PREG_PATTERN_ORDER);
  $matches = array();
  
  for ($i = 0; $i < count($result[0]); $i++) {
    $match = array();
    for ($j = 1; $j < count($result); $j++) {
      $match[$j] = $result[$j][$i];
    } 
    $matches[] = $match;
  }
  
  return $matches;
}

if(defined('FULL_BASE_URL') && substr(FULL_BASE_URL,0,5) == 'https') // load balancer hack
  $_SERVER['HTTPS'] = 'on';

$is_ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])? true : false;

/**
 * Application CONSTANTS
 */

Configure::write('debug', 2);        // 0,1,2(max)  => amount of debug/warning messages
Configure::write('App.test_mode', true); // use sandbox systems?

Configure::write('App.suppress_email', true);  // emails written to app/logs/email.log

// ---- Email ----
Configure::write('App.smtp_host', 'ssl://smtp.sendgrid.net');
Configure::write('App.smtp_port', 465);
Configure::write('App.smtp_user', '');
Configure::write('App.smtp_password', '');
// ---------------





// Production Server Override
$o = APP . 'Config' . DS . 'production.config.php';
if(file_exists($o))
  require($o);

// CLI - Command Line Interface
if(!defined('FULL_BASE_URL'))
  define('FULL_BASE_URL', Configure::read('App.test_mode')? 'http://flyways.localhost.com' : 'http://reservations.flyways.com');


