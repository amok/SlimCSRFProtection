SlimCSFRProtection
==================

Simple protection against [CSFR](http://en.wikipedia.org/wiki/Cross-site_request_forgery) attacks 

Benefits
--------
* Token sent with HTTP headers, so you do not need every time manually send it
* Easy integrates with AJAX (given jQuery.ajax exaple)

Usage
-----
First, init middleware:

    $app = new Slim();
    $app->add( new SlimCSFRProtection() );

Than set up token in view by adding next meta tag:

    <meta name="csrftoken" content="<?= $csrf_token ?>"/>

OR

    <?php header('X-CSRF-Token', $csrf_token); ?>

You can also integrate this middleware with AJAX, f.e, jQuery.ajax library:

    $(document).ajaxSend(function(e, xhr, options) {
        var token = $("meta[name='csrftoken']").attr("content");
        xhr.setRequestHeader("X-CSRF-Token", token);
     });

See also
--------
Slim-Extras CsfrGuard middleware - https://github.com/codeguy/Slim-Extras/tree/master/Middleware