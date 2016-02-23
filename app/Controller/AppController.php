<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
  
  public $components = array(
      'Session',
      'Auth' => array(
          'loginAction'    => array('controller' => 'login', 'action' => 'index', 'plugin' => null),
          'logoutRedirect' => array('controller' => 'login', 'action' => 'index'),
          'loginRedirect' => array('controller'=>'account', 'action'=>'orders'),
          'authError' => "You can't access that page",
          'authorize' => array('Controller'),
          'authenticate' => array(
              'Form' => array(
                  'fields' => array('username' => 'email')
              )
          )
      ),
      'Cookie' => array('name'=> 'user')
  );
  
  // Called right before EVERY action for every controller
  public function beforeFilter(){
    $this->Auth->allow(); // Pages by default do NOT require login
  
    Configure::write("client_ip", $this->request->clientIp(false));
  
    if($this->request->is('ajax') || $this->request->query('json')){
      $this->json();
    }
  }
  
  // This is called right after EVERY action for every controller
  public function afterFilter(){
    AppError::save_sql_log();
  }
  
  public function beforeRedirect($url, $status = null, $exit = true){
    AppError::save_sql_log();
  
    // Prevent redirect if an error has occurred that we might miss!
    if(Configure::read('App.error_occurred') && Configure::read('debug')){
      die('Redirect prevented due to error!');
    }
  
    return array(compact('url', 'status', 'exit'));
  }
  
  public function beforeRender(){
    if(count($this->viewVars) == 1 && isset($this->viewVars['json']))
      $this->json();
  
    // Set some global variables available to all Views
    $logged_in = $this->Auth->loggedIn();
  
    $this->set('is_logged_in', $logged_in);
    $this->set('current_user', $logged_in? $this->Auth->user() : null);
  
    $this->set('base_path',  FULL_BASE_URL . '/');
    $this->set('base_path_secure',    str_ireplace('http:', 'https:', FULL_BASE_URL) . '/');
    $this->set('base_path_insecure',  str_ireplace('https:', 'http:', FULL_BASE_URL) . '/');
  
    $this->set('currency', Configure::read('App.currency'));
    $this->set('is_ssl', stripos(FULL_BASE_URL, 'http://') === false);
  
    $this->set('cookies', $this->Cookie->read());
  }
  
  // Determine if LOGGED IN user has permission to access a specific page
  public function isAuthorized($user){
    return true; // Logged-in users can access any page (you can override in subclass)
  }
  
  protected function user_id(){
    return $this->Auth->user('id');
  }
  
  protected function logged_in(){
    return $this->Auth->loggedIn();
  }
  
  protected function login_redirect_url($url = null){
    return strlen($url)? $this->Auth->redirectUrl($url): $this->Auth->redirectUrl();  // should never be used statically
  }
  
  protected function requireSSL(){
    if(stripos(FULL_BASE_URL, 'https://') === false)
      $this->redirect(str_ireplace('http:', 'https:', FULL_BASE_URL) . $_SERVER[ 'REQUEST_URI' ]);
  }
  
  // alert  -- setFlash() wrapper
  protected function alert($msg, $config = null){
    $type = 'success';
    if(!$config){}
    elseif($config == 'err' || $config == 'error' || $config == 'e' || $config == 'bad')
    $type = 'danger';
    elseif($config == 'info' || $config == 'i')
    $type = 'info';
  
  
    if(strlen($msg))
      $this->Session->setFlash(__($msg), 'alert_msg', array('class' => 'alert alert-' . $type));
    else
      $this->Session->delete('Message.flash');
  }
  
  // Output JSON instead of normal page with headers and footers
  protected function json(){
    $this->layout = 'ajax';
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    $this->view = '/Elements/json';
    $this->response->type('json');
  }
  
  // Output an error message as JSON
  protected function json_error($str){
    $this->json();
  
    $this->set('json', array('error_msg' => $str));
    return false;
  }
  
  // Retrieves the currently logged in user info from the DB
  // returns array or FALSE if not logged in
  protected function user($refresh_from_db = true){
    $u = $this->Auth->user();
    if(!$u || !isset($u['id']))
      return false;
  
    if(!$refresh_from_db){
      $this->User->id = $u['id'];
      return $u;
    }
  
    // User is logged in. Refresh from DB
    $u = $this->User->read(null, $u['id']);
  
    if(!$u || !isset($u[$this->User->alias])){
      // User's account has been deleted from the DB...
  
      // We really should log the user out
      $this->alert('An authentication error occurred. Please log back in. (e1140)', 'error');
      $this->redirect($this->Auth->logout());
    }
  
    $this->Session->write('Auth.User', $u[$this->User->alias]); // update the cached user info  (this hacks into Cake internals)
  
    return $u[$this->User->alias];
  }
  
  // Auto Loader for Models
  public function &__get($name) {
    if(!isset($this->modelsList))
      $this->modelsList = array_flip(App::objects('model'));
  
    if(isset($this->modelsList[$name])){
      try {
        $this->loadModel($name);
        return $this->$name;
      } catch(Exception $e) {
      }
    }
  
    // Model not found. Let's see if our parent knows what we're trying to access
    $ret = parent::__get($name);
    return $ret; // must return a variable containing false/null, not false itself
  }
  
  
}
