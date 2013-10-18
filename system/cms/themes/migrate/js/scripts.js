$.ajaxSetup({
    cache: false
});
$(document).on("click","a",function(){
    $(this).blur();
});
$(document).on('ready', function(){
    $('#myTab a').click(function(e){
        e.preventDefault();
        $(this).tab('show');
    });
    
    var i = 0;
    var actual = 0;
    var response = 0;
    var intervalId = window.setInterval(
    function(){
        if (response === 0) {
            if ($videos[i] == null) {
                response = 2;
                clearInterval(intervalId);
            } else {
                if (i == actual) {
                    actual++;
                    var $url = $videos[i].url;
                    var $filearr = $url.split('/');
                    var $status = $('#' + $videos[i].id + '_status');
                    $status.html('<span class="label label-info">Descargando&nbsp;<img src="/system/cms/themes/default/img/loading-small.gif"></span>');
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "/migrate/wget/",
                        data: { filename: $filearr[$filearr.length - 1], url: $url, titulo: $videos[i].titulo }
                    }).done(function(xhr){
                        $status.html('<span class="label label-warning">Procesando&nbsp;<img src="/system/cms/themes/default/img/loading-small.gif"></span>');
                        var one_response = 0;
                        var one_intervalId = window.setInterval(
                        function(){
                            if (one_response === 0) {
                                setTimeout(function(){
                                    $.getJSON("/migrate/verificar_estado_video/" + xhr.embed_code)
                                    .done(function(data){
                                        if (data.status === 'live') {
                                            one_response = 2;
                                            clearInterval(one_intervalId);
                                            $status.html(
                                               '<span class="label label-default">Publicado</span>'
                                            );
                                            $('span#elapsed').html(parseInt($('span#elapsed').html()) + 1);
                                            $.ajax({
                                                async: false,
                                                type: "POST",
                                                url: '/migrate/actualizar_video/',
                                                data: { id: $videos[i].id, embed_code: xhr.embed_code, titulo: $videos[i].titulo }
                                            })
                                            .done(function(res){
                                                $status.html(
                                                    '<span class="label label-success">Actualizado</span>'
                                                );
                                            });
                                            i++;
                                        } else if (data.status === 'duplicate') {
                                            one_response = 4;
                                            clearInterval(one_intervalId);
                                            $('span#elapsed').html(parseInt($('span#elapsed').html()) + 1);
                                            $status.html(
                                               '<span class="label label-info">Duplicado</span>'
                                            );
                                            i++;
                                        } else if (data.status === 'error') {
                                            one_response = 4;
                                            clearInterval(one_intervalId);
                                            $status.html(
                                               '<span class="label label-info">Error</span>'
                                            );
                                            i++;
                                        } else if (data.status !== 'processing' && data.status !== 'uploaded') {
                                            one_response = 4;
                                            clearInterval(one_intervalId);
                                            $status.html(
                                               '<span class="label label-info">' + data.status + '</span>'
                                            );
                                            i++;
                                        }
                                    });
                                },1000);
                            }
                        }, 80000);
                    });
                }
            }
        }
    }, 1000);

    $('form.upload_video button#submit_upload').on('click', function(){
        var $form_id = $(this).parent().attr('id');
        var $idarr = $form_id.split('_');
        var $id = $idarr[1];
        var video = $('form#' + $form_id + ' input#video');
        if (video.val() === "") {
            $('#flash_message').message('danger','Debe seleccionar un video.', '#flash_title');
            video.focus();
            return false;
        }
        $('form#' + $form_id).hide();
        
        var bar = $('#' + $id + '_bar'),
        percent = $('#' + $id + '_percent'),
        status = $('#' + $id + '_status'),
        progress = $('#' + $id + '_progress');

        $('form#' + $form_id).ajaxForm({
            beforeSend: function(){
                progress.slideDown();
                percent.slideDown();
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete){
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            success: function(){
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
                progress.slideUp(1000);
                percent.slideUp(1000);
                status.html('<span class="label label-warning">Procesando</span>&nbsp;<img src="/system/cms/themes/default/img/loading-small.gif">');
            },
            complete: function(xhr){
                if (xhr.responseJSON.type === "success") {
                    var response = 0;
                    var intervalId = window.setInterval(
                    function(){
                        if (response === 0) {
                            setTimeout(function(){
                                $.getJSON("/migrate/verificar_estado_video/" + xhr.responseJSON.embed_code)
                                .done(function(data){
                                    if (data.status == 'live') {
                                        response = 2;
                                        clearInterval(intervalId);
                                        status.html(
                                           '<span class="label label-success">Publicado</span>'
                                        );
                                    } else if (data.status == 'duplicate') {
                                        response = 4;
                                        clearInterval(intervalId);
                                        status.html(
                                           '<span class="label label-info">Duplicado</span>'
                                        );
                                    } else if (data.status == 'error') {
                                        response = 4;
                                        clearInterval(intervalId);
                                        status.html(
                                           '<span class="label label-info">Error</span>'
                                        );
                                    } else {
                                        response = 4;
                                        clearInterval(intervalId);
                                        status.html(
                                           '<span class="label label-info">' + data.status + '</span>'
                                        );
                                    }
                                });
                            },1000);
                        }
                    }, 80000);
                }
            }
        });
    });
});