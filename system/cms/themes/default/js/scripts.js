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
    $(document).on('click', 'a#use-this-video', function(){
        console.log('add to hidden');
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

});