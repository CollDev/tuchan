(function($){
    $.fn.extend({
        message: function(type, title, title_id){
            $(title_id).html('').append(title);
            var del = 0;
            if ($(window).scrollTop() !== 0) {
                $("html, body").animate({ scrollTop: 0 }, 600);
                del = 600;
            }
            $(this).removeClass().addClass('alert').addClass('alert-' + type).delay(del).slideDown().delay(3000).slideUp();
        }
    });
})(jQuery);
$.ajaxSetup({
    cache: false
});
$(document).on("click","a",function(){
    $(this).blur();
});
$(document).on('ready', function(){
    $('#myTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
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
            beforeSend: function() {
                progress.slideDown();
                percent.slideDown();
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            error: function(){
            },
            success: function() {
                var percentVal = '100%';
                bar.width(percentVal);
                percent.html(percentVal);
                progress.slideUp(1000);
                percent.slideUp(1000);
                status.html('<span class="label label-warning">Procesando</span>&nbsp;<img src="/system/cms/themes/default/img/loading-small.gif">');
            },
            complete: function(xhr) {
        console.log(xhr);
                if (xhr.responseJSON.type === "success") {
                    var response = 0;
                    var intervalId = window.setInterval(
                    function () {
                        if (response === 0) {
                            setTimeout(function(){
                                $.getJSON("/migrate/verificar_estado_video/" + xhr.responseJSON.embed_code)
                                .done(function(data) {
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