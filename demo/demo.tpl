<!DOCTYPE html>
<html>
    <head>
        <title>SlimCSRFProtection demo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
        <style type="text/css">
            input { width: 200px; }
            td { vertical-align: top; padding: 10px;}
            table { width: 100%; }
            #fail, #success { display: none; }
        </style>
        <?= $csrf_protection_jquery ?>
        <script type="text/javascript">

            var failed  = "<?= (int)$failed ?>" === "1";
            var is_post = "<?= (int) !empty($_POST); ?>" === "1"; 

            $(function(){
                
                if(is_post) {
                    if(failed) {
                        $('#fail').show();
                    } else {
                        $('#success').show();
                    }
                }

                $('#ajax').click(function(){
                    $.post('', {data: $('#ajax-msg').val()}, function(data) {
                        var data = $.parseJSON(data);
                        if(parseInt(data.failed,10) === 1) {
                            $('#success').hide();
                            $('#fail').show();
                        } else {
                            $('#success').show();
                            $('#fail').hide();
                        }
                        
                        $('#data').html( data.msg );
                    })
                });
            });
        </script>
    </head>
    <body>
        <table>
            <tbody>
                <tr>
                    <td style="width: 50%">

                        <h4>AJAX request with protection</h4>
                        <span class="label"> Send me some data: </span>
                        <input id="ajax-msg" type="text" name="data"/>
                        <button id="ajax">Send</button>

                        <hr/>

                        <form method="POST" action="">
                            <h4>POST without protection</h4>
                            <span class="label"> Send me some data: </span>
                            <input type="text" name="data"/>
                            <button>Send</button>
                        </form>

                        <hr/>

                        <form method="POST" action="">
                            <h4>POST with protection (hidden input, $csrf_protection_input)</h4>
                            <?= $csrf_protection_input ?>
                            <span class="label"> Send me some data: </span>
                            <input type="text" name="data"/>
                            <button>Send</button>
                        </form>

                        <hr/>

                        <form method="POST" action="">
                            <h4>POST with protection (hidden input, $csrf_token )</h4>
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <span class="label"> Send me some data: </span>
                            <input type="text" name="data"/>
                            <button>Send</button>
                        </form>

                    </td>
                    <td>
                        <div><u>
                            <span id="success">TEST PASSED! Your message is:</span>
                            <span id="fail">ATENTION, ATTACK!. Your message:</span>
                            <br/>
                            <span id="data"><?= isset($_POST['data']) ? $_POST['data'] : "" ?></data>
                        </div>
                    </td>
                </tr>
            <tbody>
        </table>
    </body>
</html>