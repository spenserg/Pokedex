<?php
/**
 * 
 * @author Adam Albright
 * 
 * Hooks into CakePHP error handler to prevent redirection when an error is detected
 *
 */


class AppError {

  public static function handleError($code, $description, $file = null, $line = null, $context = null){
    
    // When function errors are manually suppressed by doing $file = @file_get_contents(...)
    // the error_reporting() setting is temporarily changed to 0
    // http://php.net/manual/en/language.operators.errorcontrol.php
    if(error_reporting() === 0)
      return false;
    
    Configure::write('App.error_occurred', true);
    
    //------
    list($error, $level) = ErrorHandler::mapErrorCode($code);
    if($level === LOG_ERR || substr($description,0,strlen('assert(')) == 'assert(') {
      // FATAL ERROR
      // Convert code to WARNING so Cake won't show the special page (execution will still cease)
      $code = E_WARNING;
      
      self::report_error($description, compact('file','line','code','context'));
    }
    //------
    
    if(Configure::read('debug') != 0){
      // Cake normally doesn't write to logfile if debug != 0
      $message = $error . ' (' . $code . '): ' . $description . (strlen($file)? ' in [' . $file . ', line ' . $line . ']' : '');
      CakeLog::write($level, $message);
    }
    
    ErrorHandler::handleError($code, $description, $file, $line, $context);
  }

  public static function handleException(Exception $e, $show_error_page = true){
    $file = $e->getFile();
    $line = $e->getLine();
    
    $error_level = E_ERROR;
    
    $trace = $e->getTrace();
    
    if(is_array($trace)){  // strip out the framework level functions
      foreach($trace as $i => $t){
        if(isset($t['class']) && !in_array(strtolower($t['class']), array('pdostatement','dbosource','mysql'))){
          $trace[0] = $trace[$i];
          break;
        }
      }
    }
    
    $type = get_class($e);
    
    if(is_array($trace) && isset($trace[0])) {
      if(!strlen($file) && isset($trace[0]['file']))
        $file = $trace[0]['file'];
      elseif(!strlen($file) && isset($trace[0]['class']) && isset($trace[0]['function']))
        $file = $trace[0]['class'] . '->' . $trace[0]['function'];
      else if(isset($trace[0]['function']) && strlen($trace[0]['function']))
        $file .= ' -> ' . $trace[0]['function'] . '()';
      
      if(!strlen($line))
        $line = isset($trace[0]['line']) ? $trace[0]['line']:null;
    }
    
    $report_error = true; // Send an admin email report?
    
    if($type == 'PDOException')
      $msg = 'Database error: ' . $e->getMessage() . " \n\n " . $e->queryString;
    else if($type == 'MissingControllerException' || $type == 'MissingActionException' || $type == 'NotFoundException'){
      $msg = 'Not found: ' . $_SERVER['REQUEST_URI'] . ' -- ' . (isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : 'No UA');
      if(strlen(env('HTTP_REFERER')))
        $msg .= ' -- ' . env('HTTP_REFERER');
      
      $file = $line = '';
      
      CakeLog::write("404s", env('HTTP_X_FORWARDED_FOR') . ' ' .$msg);
      $report_error = false; // Crappy bots and fuzzers
    }
    else
      $msg = "$type: " . $e->getMessage();
    
    try {
      if($report_error){
        self::handleError($error_level, $msg, $file, $line, $e->getTrace());
        self::save_sql_log();
      }
    } catch(Exception $e){}
    
    
    if(!$show_error_page)
      return true;
    
    try {
      $config = Configure::read('Exception');
      $renderer = $config['renderer'];
      if($renderer !== 'ExceptionRenderer') {
        list ($plugin, $renderer) = pluginSplit($renderer, true);
        App::uses($renderer, $plugin . 'Error');
      }
      
      $error = new ExceptionRenderer($e);
      $error->render();
    } catch(Exception $e) {
      echo "An internal error has occurred. Please report this (Incident ID: 98ZQB)";
    }
    
    return;
  }
  
  static function silent_exception(Exception $e){
    if(get_class($e) == 'ExpectedException')
      return;
    
    $debug_level = Configure::read('debug');
    Configure::write('debug',0);
    AppError::handleException($e, false);
    Configure::write('debug', $debug_level);
  }
  
  static function get_sql_log(){
    App::uses("ConnectionManager", "Model");
    
    if(!count(ConnectionManager::sourceList()))
      return;
    
    $log = ConnectionManager::getDataSource('default')->getLog(false, false);
    if(!is_numeric($log['time']) || !count($log['log']))
      return;
    
    $out = number_format($log['time'],0) . "ms";
    $out .= ' -- ' . (isset($_SERVER[ 'REQUEST_URI' ])? $_SERVER[ 'REQUEST_URI' ] : 'CLI');
    foreach($log['log'] as &$l)
      $out .= "\n  [" . $l['took'] . 'ms'. str_repeat('*',  floor($l['took']/150)) .'] ' . $l['query'];
    
    return $out;
  }
  
  static function save_sql_log(){
    $log = self::get_sql_log();
    if(strlen($log) && Configure::read('sql_logging') !== false)
      CakeLog::write('sql', $log);
  }
  
  
  // Sends an email to dev team about an error
  static function report_error($msg, $additional_variables = null){
    $debug_info = array();
    $incidence_id = date('YmdH') . substr(md5(rand()+rand()), 0, 5);
    
    $body = null;
    try {
      if(is_object($msg) && method_exists($msg, 'getMessage'))
        $msg = $msg->getMessage();
      else if(!is_string($msg))
        $msg = json_encode($msg);
        
      $body = "ERROR REPORT\n------------\n" . $msg . "\n\n";
      $body .= "URL: " . (PHP_SAPI === 'cli'? 'CLI BATCH SCRIPT' : $_SERVER['REQUEST_URI']) . "\n\n";
      
      $body .= 'IP: ' . (isset($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['HTTP_X_FORWARDED_FOR']:'') . "\n";
      $body .= 'Time: ' . date('Y-m-d H:i:s') . "\n\n";
      
      $debug_info = (func_num_args() > 0)? func_get_args() : array();
      
      foreach($debug_info as &$d)
        if(is_object($d) && method_exists($d, 'getTrace'))
          $d = 'Exception: ' . $d->getMessage() . "\n--Exception Trace--\n" . self::dump($d->getTrace()) . "\n--END Exception Trace--\n";
    
      $body .= "---DEBUG----\n" . self::dump($debug_info, 8) . "\n\n\n";
      
      $body .= "---GET----\n"   . self::dump($_GET)   . "\n\n";
      $body .= "---POST---\n" . self::dump($_POST) . "\n\n";
      
      if(class_exists('AuthComponent') && AuthComponent::user('id'))
        $body .= "---USER---\n"   . self::dump(AuthComponent::user())   . "\n\n";
      
      $body .= "---TRACE----\n" . Debugger::trace(array('start' => 1, 'depth' => '5')) . "\n\n";
      $body .= "---DATABASE----\n"   . self::get_sql_log()   . "\n\n";
      
      $s = $_SERVER;
      unset($s['PATH'],$s['PATHEXT'],$s['HTTP_ACCEPT'],$s['COMSPEC'],$s['SERVER_ADMIN'],$s['HTTP_CACHE_CONTROL']);
      $body .= "---SERVER----\n"   . self::dump($s)   . "\n\n";
    } catch(Exception $e){
    }
    
    try {
      if(Configure::read('App.suppress_email')){
        CakeLog::write('error_email', $body);
      }
      else {
        // TODO notify admins
        CakeLog::write('error_email', $body);
      }
    } catch(Exception $e){
      return false;
    }
  }
  
  static function dump($var, $depth = 3, $max_len = 30000){
    if(is_array($var) && !count($var))
      return '';
    
    $txt = Debugger::exportVar($var, $depth);
    $txt = preg_replace('/\t/','   ', preg_replace('/(\(int\)|^\t)/im', '', $txt));
    
    if($max_len > 0 && strlen($txt) > $max_len){
      if($depth <= 1)
        return substr($txt, 0, $max_len);
      else
        return AppError::dump($var, $depth>4? ($depth-2):($depth-1));
    }
    
    return $txt;
  }
  
  
}