<?php

/**
 * CSRF protection for Slim framework (version 2)
 * https://github.com/komaval/SlimCSRFProtection/wiki
 * @author komaval
 */

class Slim2CSRFProtection extends \Slim\Middleware {

    public static function get_token() {
        if( isset($_SESSION['csrf_token']) ) return $_SESSION['csrf_token'];
        $token = md5( microtime() . rand() . uniqid() );
        return $token;
    }

    public function __construct($onerror = false) {
        if($onerror && is_callable($onerror)) {
            $this->_onerror = $onerror;
        }
    }

    public function call() {        
        $this->app->hook('slim.before', array($this, 'check'));
        $this->next->call();
    }

    public function is_token_valid($usertoken) {
        return $usertoken === $_SESSION['csrf_token'];
    }

    public function check() {
        if(!isset($_SESSION)) {
            $this->app->halt(400, "SlimCSRFProtection: session not started.");
        }

        $env = $this->app->environment();

        $usertoken = $env['X_CSRF_TOKEN'] ?: $this->app->request()->post( 'csrf_token' );

        if( in_array($this->app->request()->getMethod(), array('POST', 'PUT', 'DELETE')) ) {
            if ( !$this->is_token_valid($usertoken) ) {
                if(property_exists($this, '_onerror')) {
                    call_user_func($this->_onerror);
                } else {
                    $this->app->halt(400, "CSRF protection: wrong token");
                }
            }   
        }

        $token = static::get_token();

        $_SESSION['csrf_token'] = $token;

        $this->app->view()->setData(array(
            'csrf_token' => $token,
            'csrf_protection_input'  => '<input type="hidden" name="csrf_token" value="' . $token . '"/>',
            'csrf_protection_jquery' => 
                '<script type="text/javascript">$(document).ajaxSend(function(e,xhr){xhr.setRequestHeader("X-CSRF-Token","' . $token . '");});</script>'
        ));
    }
}

?>