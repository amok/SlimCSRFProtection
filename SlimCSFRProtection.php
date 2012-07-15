<?php
/**
 * SlimCSFRProtection
 *
 * This middleware provides protection from CSRF attacks
 * 
 * USAGE
 *
 * // Adding middleware
 * $app = new Slim();
 * $app->add( new SlimCSFRProtection() );
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
 * @author komaval, https://github.com/komaval
 * @version 0.1
 */

class SlimCSFRProtection extends Slim_Middleware {
    /**
     * @constructor
     */
    public function __construct() {}

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
        $token = md5('SlimCSFRProtection' . '|' . $env['REMOTE_ADDR'] . '|' . $env['USER_AGENT']);

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