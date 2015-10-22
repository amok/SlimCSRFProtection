# Slim CSRF protection

Simple protection middleware against [CSRF](http://en.wikipedia.org/wiki/Cross-site_request_forgery) 
attacks for [Slim PHP framework](http://www.slimframework.com). 

* Really protect your app from csr forgery ;)
* Helpers for forms and jQuery
* Versions with and without sessions usage


## Usage

### SlimCSRFProtection

Version with Sessions (must be enabled)

    session_start();
    $app = new Slim(); // init app
    $app->add( new SlimCSRFProtection() );

Also, you can pass custom callback function:

    function show_message() { echo "verification not passed" }

    $app->add( new SlimCSRFProtection('show_message') );

### SlimCSRFProtectionNoSession

Note, if you use **SlimCSRFProtectionNoSession** instead of SlimCSRFProtection, you need to pass  in constructor as first argument some secret string, which will be used for generating csrf token - it's required. And, optionally, constructor takes function, which will be executed, if submitted token is not valid.

    $app->add( new SlimCSRFProtectionNoSession("It is my secret string!") );

With function:

    function show_message() { echo "verification not passed" }

    $app->add( new SlimCSRFProtectionNoSession("It is my secret string!", 'show_message') );

### Important
SlimCSRFProtectionNoSession generates token, related to your secret string, $_SERVER['REMOTE_ADDR'] and $_SERVER['USER_AGENT'], therefore it is not full protection - users from same subnet takes same token. If it is possible, use SlimCSRFProtection instead of SlimCSRFProtectionNoSession

## Usage in View
By default SlimCSRFProtection append to view three variables:

    $csrf_token - random secret token

    $csrf_protection_input - <input> for usage in forms (<input type="hidden" value="<?= $csrf_token ?>">)

    $csrf_protection_jquery - jquery code, which add 'X_CSRF_TOKEN' header to each jQuery.ajax post request

You can use those in HTML like so

    <form action="/login" method="POST">
        <?= $csrf_protection_input ?>
        <input name="login">
        <input name="passwd">
        <input type="submit" value="Submit">
    </form>

    ...
    <head>
        <title>Example</title>
        ....
        <?= $csrf_protection_jquery ?>
    </head>
    ...

# API
**static::create_token()** - returns csrf token

**$this->is_token_valid($token)** - returns true, if token valid

### See also

[Slim-Extras: CsrfGuard middleware](https://github.com/codeguy/Slim-Extras/tree/master/Middleware)
