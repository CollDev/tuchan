$(document).on('ready', function(){
    $.getJSON("/cmsapi/getcanaleslist")
        .done(function(res){
            $.each(res, function(key, value){
                $('#lista_canales')
                    .append($('<option>', { value : key })
                    .text(value));
            });
        });
    $.getJSON("/cmsapi/getcategoriaslist")
        .done(function(res){
            $.each(res, function(key, value){
                if ($.isNumeric(key)) {
                    $('#lista_categorias')
                        .append($('<option>', { value : key })
                        .text(value));
                } else {
                    var optiong = $('<optgroup>', { label : key});
                    $.each(value, function(keyg, valueg){
                        $(optiong)
                            .append($('<option>', { value : keyg })
                            .text(valueg));
                    });
                    $('#lista_categorias')
                        .append(optiong);
                    optiong = null;
                }
            });
        });    
    $('#lista_canales').on('change', function(){
        $('#lista_programas').trigger('change');
        $('#lista_colecciones').trigger('change');
        $('#lista_listas').trigger('change');
        var canal_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getprogramaslist/" + canal_id)
            .done(function(res){
                $('#lista_programas')
                    .find('option')
                    .remove();
                $('#lista_programas')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione programa'));
                $.each(res, function(key, value){
                    $('#lista_programas')
                        .append($('<option>', { value : key })
                        .text(value));
                });
            });
    });
    $('#lista_programas').on('change', function(){
        $('#lista_colecciones').trigger('change');
        $('#lista_listas').trigger('change');
        var programa_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getcoleccioneslist/" + programa_id)
            .done(function(res){
                $('#lista_colecciones')
                    .find('option')
                    .remove();
                $('#lista_colecciones')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione colección'));
                $.each(res, function(key, value){
                    $('#lista_colecciones')
                        .append($('<option>', { value : key })
                        .text(value));
                });
            }); 
    });
    $('#lista_colecciones').on('change', function(){
        $('#lista_listas').trigger('change');
        var coleccion_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getlistaslist/" + coleccion_id)
            .done(function(res){
                $('#lista_listas')
                    .find('option')
                    .remove();
                $('#lista_listas')
                    .append($('<option>', { value : '0' })
                    .text('Seleccione lista'));
                $.each(res, function(key, value){
                    $('#lista_listas')
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
});