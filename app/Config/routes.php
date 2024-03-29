<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
Router::connect('/users/edit', array('controller' => 'users', 'action' => 'edit'));
Router::connect('/users/update', array('controller' => 'users', 'action' => 'update'), array('method' => 'POST'));
Router::connect('/userprofiles/new', array('controller' => 'userprofiles', 'action' => 'new'));
Router::connect('/userprofiles/show', array('controller' => 'userprofiles', 'action' => 'show'));
Router::connect('/userprofiles/edit', array('controller' => 'userprofiles', 'action' => 'edit'));
Router::connect('/userprofiles/update', array('controller' => 'userprofiles', 'action' => 'update'), array('method' => 'POST'));
Router::connect('/messages/list', array('controller' => 'messages', 'action' => 'list'));
Router::connect('/messages/new', array('controller' => 'messages', 'action' => 'new'));
Router::connect('/messages/send', array('controller' => 'messages', 'action' => 'send'), array('method' => 'POST'));
Router::connect('/messages/reply', array('controller' => 'messages', 'action' => 'reply'), array('method' => 'POST'));
Router::connect('/messages/detail', array('controller' => 'messages', 'action' => 'detail'), array('method' => 'POST'));
Router::connect('/messages/getUsers', array('controller' => 'messages', 'action' => 'getUsers'));
Router::connect('/messages/destroyMessage', array('controller' => 'messages', 'action' => 'destroyMessage'), array('method' => 'POST'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';