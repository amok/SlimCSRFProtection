SlimCSRFProtection
==================

Simple protection middleware against [CSRF](http://en.wikipedia.org/wiki/Cross-site_request_forgery) 
attacks for [Slim framework](http://www.slimframework.com). 

### Benefits

* Token sent with HTTP headers, so you do not need every time manually send it
* Easy integrates with AJAX (given an jQuery.ajax example)

### Usage

Init middleware:

    $app = new Slim();
    // Constructor takes string, which will be used for generating csfr token. 
    $app->add( new SlimCSRFProtection("my secret string") );

Then, set up token in view:

    <meta name="csrftoken" content="<?= $csrf_token ?>"/>

OR

    <?php header('X-CSRF-Token', $csrf_token); ?>

Working with jQuery.ajax:

    $(document).ajaxSend(function(e, xhr, options) {
        var token = $("meta[name='csrftoken']").attr("content");
        xhr.setRequestHeader("X-CSRF-Token", token);
     });

### See also

[Slim-Extras: CsrfGuard middleware](https://github.com/codeguy/Slim-Extras/tree/master/Middleware)