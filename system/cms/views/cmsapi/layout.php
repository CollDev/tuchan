<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Mi Canal API</title>
<!--        <link href="/system/cms/themes/default/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/system/cms/themes/default/js/tinytable/style.css" rel="stylesheet">
        <link href="/system/cms/themes/default/css/colorbox.css" rel="stylesheet">
        <link href="/system/cms/themes/pyrocms/css/nouislider.fox.css" rel="stylesheet">
        <link href="/system/cms/themes/pyrocms/css/mediasplitter.css" rel="stylesheet">
        <link href="/system/cms/themes/default/js/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <link href="/system/cms/themes/default/js/jquery-ui/css/jquery-ui-timepicker-addon.css" rel="stylesheet">
        <link href="/system/cms/themes/default/css/jquery.tagsinput.css" rel="stylesheet">
        <link href="/system/cms/themes/default/css/styles.css" rel="stylesheet">-->
        <link href="/system/cms/themes/default/css/main.css?_=<?php echo $cache; ?>" rel="stylesheet">
        <link href="/system/cms/themes/default/img/favicon.ico" rel="shortcut icon">
    </head>
    
    <body>
        <div class="wrap">
            <?php echo $content; ?>
        </div>
        <iframe src="<?php echo $post_url; ?>" id="iframeProxy" width="0" height="0" style="border:none"></iframe>
        <div id="myModal" class="modal fade">
            <div class="alert alert-success fade in">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <strong>Listo!</strong>
                Video seleccionado con exito.
            </div>
        </div>
        <div id="myUploadModal" class="modal fade"></div>
<!--        <script src="/system/cms/themes/default/js/jquery.js"></script>
        <script src="/system/cms/themes/default/js/jquery.mustache.js"></script>
        <script src="/system/cms/themes/default/bootstrap/js/bootstrap.min.js"></script>
        <script src="/system/cms/themes/default/js/tinytable/packed.js"></script>
        <script src="/system/cms/themes/default/js/jquery.colorbox-min.js"></script>
        <script src="/system/cms/themes/pyrocms/js/lib/jwplayer.js"></script>
        <script src="/system/cms/themes/pyrocms/js/lib/jquery.nouislider.min.js"></script>
        <script src="/system/cms/themes/pyrocms/js/lib/splitter.js"></script>
        <script src="/system/cms/themes/default/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/system/cms/themes/default/js/jquery-ui/js/jquery.ui.datepicker-es.js"></script>
        <script src="/system/cms/themes/default/js/jquery-ui/js/jquery-ui-timepicker-addon.js"></script>
        <script src="/system/cms/themes/default/js/jquery.tagsinput.min.js"></script>
        <script src="/system/cms/themes/pyrocms/js/jquery/jquery.form.js"></script>
        <script src="/system/cms/themes/default/js/scripts.js"></script>-->
        <script src="/system/cms/themes/default/js/main.js?_=<?php echo $cache; ?>"></script>
    </body>
</html>