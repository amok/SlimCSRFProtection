<?php
/**
 * SlimCSRFProtection
 *
 * This middleware provides protection from CSRF attacks
 * 
 * USAGE
 *
 * // Adding middleware
 * $app = new Slim();
 * $app->add( new SlimCSRFProtection() );
 *
 * // Setting token in view
 *
 * <meta name="csrftoken" content="<?= $csrf_token ?>"/> 
 * OR
 * <?php header('X-CSRF-Token', $csrf_token); ?>
 * 
 * // Usage with jQuery.ajax
 * 
 * $(document).ajaxSend(function(e, xhr, options) {
 *      var token = $("meta[name='csrftoken']").attr("content");
 *      xhr.setRequestHeader("X-CSRF-Token", token);
 * });
 *
 * see also README.md
 *  
 * @author komaval, https://github.com/komaval
 * @version 0.2
 */

class SlimCSRFProtection extends Slim_Middleware {
    
    /**
     * @var {String} secret key, wich will be used for generating csrf token
     */
    protected $secret;

    /**
     * @constructor
     */
    public function __construct( String $secret = "SlimCSRFProtection" ) {
        $this->secret = $secret;
    }

    /**
     * Call middleware
     */
    public function call() {
        // Attach as hook
        $this->app->hook('slim.before', array($this, 'check'));

        // Call next middleware
        $this->next->call();
    }

    /**
    * Checking token, which was sent with headers
    */
    public function check() {
        // Create token
        $env = $this->app->environment();
        $token = md5( $this->secret . '|' . $env['REMOTE_ADDR'] . '|' . $env['USER_AGENT'] );

        $usertoken = $env['X_CSRF_TOKEN'];

        if( in_array($this->app->request()->getMethod(), array('POST', 'PUT', 'DELETE')) ) {
            if ( $token !== $usertoken ) {
               $this->app->halt(400, 'Missing protection token');
            }   
        }

        // Assign to view
        $this->app->view()->setData(array(
            'csrf_token' => $token,
        ));
    }
}

?>