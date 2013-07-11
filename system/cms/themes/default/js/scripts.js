$(document).on('ready', function(){
    $.getJSON("/cmsapi/getcanaleslist")
        .done(function(res){
            $.each(res, function(key, value){
                $('#canal_id')
                    .append($('<option>', { value : key })
                    .text(value));
            });
        });
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
    $('#canal_id').on('change', function(){
        $('#programa').trigger('change');
        $('#coleccion').trigger('change');
        $('#lista').trigger('change');
        var canal_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getprogramaslist/" + canal_id)
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
        if ($('#jerarquia').val() !== '') {
            $.getJSON("/cmsapi/jerarquia/" + $('#jerarquia').val())
                .done(function(res){
                    console.log(res);
                });
        }
    });
});