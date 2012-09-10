<?php

/**
 * CSRF protection for Slim framework (version 2)
 * https://github.com/komaval/SlimCSRFProtection/wiki
 * @author komaval
 */

class Slim2CSRFProtectionNoSession extends \Slim\Middleware {
    
    protected $_secret, $_token;

    public function get_token() {
        $env = $this->app->environment();
        return md5(sha1(md5("CSRF" . str_repeat($this->_secret . $env['REMOTE_ADDR'] . $env['USER_AGENT'], 10))));
    }

    public function __construct($secret, $onerror = false) {
        $this->_secret = $secret;

        if($onerror && is_callable($this->_onerror)) {
            $this->_onerror = $onerror;
        }
    }

    public function call() {
        $this->_token = $this->get_token();
        $this->app->hook('slim.before', array($this, 'check'));
        $this->next->call();
    }

    public function is_token_valid($token) {
        return $token == $this->_token;
    }

    public function check() {
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

        $token = $this->_token;
        $this->app->view()->setData(array(
            'csrf_token' => $token,
            'csrf_protection_input'  => '<input type="hidden" name="csrf_token" value="' . $token . '"/>',
            'csrf_protection_jquery' => 
                '<script type="text/javascript">$(document).ajaxSend(function(e,xhr){xhr.setRequestHeader("X-CSRF-Token","' . $token . '");});</script>'
        ));
    }
}

?>