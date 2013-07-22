$.ajaxSetup({
    cache: false
});
$(document).on('ready', function(){
    $.getJSON("/cmsapi/getcategoriaslist")
        .done(function(res){
            $.each(res, function(key, value){
                if ($.isNumeric(key)) {
                    $('#categoria')
                        .append($('<option>', { value : key })
                        .text(value));
                } else {
                    var optiong = $('<optgroup>', { label : key});
                    $.each(value, function(keyg, valueg){
                        $(optiong)
                            .append($('<option>', { value : keyg })
                            .text(valueg));
                    });
                    $('#categoria')
                        .append(optiong);
                    optiong = null;
                }
            });
        });    
        $.getJSON("/cmsapi/getprogramaslist/" + $('#canal_id').val())
            .done(function(res){
                $('#programa')
                    .find('option')
                    .remove();
                $('#programa')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione programa'));
                $.each(res, function(key, value){
                    $('#programa')
                        .append($('<option>', { value : key })
                        .text(value));
                });
            });
    $('#programa').on('change', function(){
        $('#coleccion').trigger('change');
        $('#lista').trigger('change');
        var programa_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getcoleccioneslist/" + programa_id)
            .done(function(res){
                $('#coleccion')
                    .find('option')
                    .remove();
                $('#coleccion')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione colección'));
                $.each(res, function(key, value){
                    $('#coleccion')
                        .append($('<option>', { value : key })
                        .text(value));
                });
            }); 
    });
    $('#coleccion').on('change', function(){
        $('#lista').trigger('change');
        var coleccion_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getlistaslist/" + coleccion_id)
            .done(function(res){
                $('#lista')
                    .find('option')
                    .remove();
                $('#lista')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione lista'));
                $.each(res, function(key, value){
                    $('#lista')
                        .append($('<option>', { value : key })
                        .text(value));
                });
            }); 
    });
    $('#upload_form').on('submit', function(e){
        e.preventDefault();
        var validForm = true;
        if (validForm) {
            e.target.submit();
        }
    });
    $('#search_form').on('submit', function(e){
        e.preventDefault();
        if ($('#termino').val() !== '') {
            $("div#search_results").html('<div class="text-center"><img src="/system/cms/themes/default/img/loading.gif" /></div>');
            $.getJSON("/cmsapi/search/" + $('#termino').val()+ "/"+$("#fecha_inicio").val()+"/"+$("#fecha_fin").val())
                .done(function(data){
                    $.get("/system/cms/themes/default/js/mustache_templates/cmsapi/search_results.html", function(template){
                        $("div#search_results").html('');
                        if (data.videos == '') {
                            var app = '<div class="text-center"><h3>No hay resultados para la búsqueda:</h3><h4>' + $('#termino').val() + '</h4></div>';
                        } else {
                            var app = $.mustache(template, data);
                        }
                        $("div#search_results").append(app);
                    });
                });
        }
    });
    $(document).on('click', 'a', function(){
        $(this).blur();
    });
    $(document).on('keydown', function(event){
        if(event.which === 27){
            event.preventDefault();
            $('table#table tbody tr').removeClass('success');
            $('a#use-this-video').addClass('disabled');
        }
    });
    $(document).on('click', 'a#use-this-video', function(e){
        e.preventDefault();
        console.log('add to hidden: video_id = "' + $(this).attr('href') + '"');
    });
    $(document).on('click', 'a.corte_video', function(){
        $("div#cut_this_video").html('<div class="text-center"><img src="/system/cms/themes/default/img/loading.gif" /></div>');
        $('ul#myTab li.disabled a').attr('data-toggle', 'tab').parent().removeClass('disabled');
        $('ul#myTab li a#corte_video').trigger('click');
        
        var $this = this;
        var tr_id = $($this).parent().parent().attr('id');
        var split = tr_id.split('_');
        
        $.getJSON("/cmsapi/corte/" + split[1])
            .done(function(data){
                $.get("/system/cms/themes/default/js/mustache_templates/cmsapi/cut_video.html", function(template){
                    $("div#cut_this_video").html('');
                    if (data == '') {
                        var app = '<div class="text-center"><h3>Seleccione un video válido</h3></div>';
                    } else {
                        var app = $.mustache(template, data);
                    }
                    $("div#cut_this_video").append(app);
                });
            });
    });
    function showMessage(type, title) {
        if (type != '' && title != '') {
            $('#flash_title').html('').append(title);
            $("html, body").animate({ scrollTop: 0 }, 600);
            $('#flash_message').removeClass().addClass('alert').addClass('alert-' + type).delay(600).slideDown().delay(3000).slideUp();
        }
    }
    $(document).on('click', '#submit_cut', function(e){
        e.preventDefault();
        var $type = '';
        var $title = '';
        var $valid = true;
        var values = {};
        $.each($('#cut_form').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        values['tematicas'] = $.trim(values['tematicas']);
        values['personajes'] = $.trim(values['personajes']);
        if ($("#dur_corte").val() == '') {
            $type = 'danger'; $title = 'Debe realizar un corte'; $valid = false;
        } else if ($("#dur_corte").val() >= Math.round($("#dur_total").val() * 10) / 10) {
            $type = 'danger'; $title = 'La duracion del nuevo video debe ser diferente al original'; $valid = false;
        } else if (values['titulo'].length == 0) {
            $type = 'danger'; $title = 'Ingrese un título'; $valid = false;
        } else if (values['descripcion'].length == 0) {
            $type = 'danger'; $title = 'Ingrese la descripción del video.'; $valid = false;
        } else if (values['tematicas'].length == 0) {
            $type = 'danger'; $title = 'Ingrese las temáticas.'; $valid = false;
        } else if (values['personajes'].length == 0) {
            $type = 'danger'; $title = 'Ingrese personajes.'; $valid = false;
        }
        
        showMessage($type, $title);
        
        if ($valid) {
            $.ajax({
                type: "POST",
                url: "/admin/videos/insertCorteVideo/" + values['canal_id'] + "/" + values['video_id'],
                dataType: 'json',
                data: $('#cut_form').serialize(),
                success: function(returnValue) //we're calling the response json array 'cities'
                {
                    if (returnValue.value == 0) {
                        showMessage('success', 'El corte se guardó satisfactoriamente');
                        setTimeout(function(){
                            var $use_this_video = '<a class="btn btn-default col-lg-2 text-center" href="' + returnValue.video_id + '" id="use-this-video">Usar este corte de video</a>';
                            $("div#cut_this_video").html('').append($use_this_video);
                        },4200);
                    } else {
                        showMessage('warning', 'Ya existe un video con estos datos.');
                    }
                }
            });                                                  
        }
    });
});