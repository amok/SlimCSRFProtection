<?

require_once 'Slim/Slim/Slim.php';

spl_autoload_register(function($className) {
    \Slim\Slim::autoload( 'Slim\\' . $className );
});

// require_once 'Slim/Slim/Slim.php';
require_once '../Slim2CSRFProtection.php';
require_once '../Slim2CSRFProtectionNoSession.php';

$app = new \Slim\Slim(array(
    'templates.path' => './'
));

session_start();

$failed = 0;

$app->add( new Slim2CSRFProtection(function() use($app) {
    global $failed;
    $failed = 1;
}));

$app->map('/', function() use($app) {
    global $failed;

    if( $app->request()->isAjax() ) {
        echo "{ \"failed\": \"{$failed}\" , \"msg\":\"{$_POST['data']}\"}";
    } else {
        $app->render('demo.tpl', array('failed' => $failed));
    }

})->via('GET', 'POST');

$app->run();

?>
