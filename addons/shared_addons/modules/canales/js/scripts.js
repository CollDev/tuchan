$(document).ready(function(){
    $('button.enviar_canal').click(function(){
        $.getJSON('admin/canales/actualizar_ibope',
            {
                ibope: $('input.input#input_canal_' + $(this).attr('id').substring(10)).val(),
                canal_id: $(this).attr('id').substring(10)
            }
        ).done(function(res) {
            showMessage(res.type, res.message);
        });
    });
    
    $('button.key_canal').click(function(){
        var result;
        result = generate_key();
        $('.input_key_canal').each(function() {
            if ($(this).val() == result) {
                result = generate_key();
            }
        });
        
        $('#key_canal_' + $(this).attr('id').substring(10)).val(result);
    
        $.getJSON('admin/canales/actualizar_key',
            {
                key: result,
                canal_id: $(this).attr('id').substring(10)
            }
        ).done(function(res) {
            showMessage(res.type, res.message);
        });
    });
    
    function generate_key(){
        var result = '';
        for (i = 0; i < 32; i++) {
            result += Math.round(Math.random()*16).toString(16);
        }
        
        return result;
    }
    
    $(".btn_categoria").on('click', function(e){
        e.preventDefault();
        $("div#categoria-modal").html('');
        var $id = $(this).attr('id');
        var $split = $id.split("_");
        
        $.getJSON('admin/canales/categorias_json/' + $split[1])
            .done(function(data) {
                $.get('addons/shared_addons/modules/canales/js/templates/' + $split[0] + '_category.html', function(template){
                    var app = '<div class="text-center"><h3>No hay resultados para la búsqueda:</h3><h3>' + $('#termino').val() + '</h3></div>';
                    if (data.categorias !== '') {
                        app = $.mustache(template, data);
                    }
                    $("div#categoria-modal").append(app).dialog({
                        maxHeight: 400,
                        position: { my: "center", at: "center", of: "body" },
                        modal: true,
                    }).animate({ scrollTop: 0 }, 600);
                });
        });
    });
    
    $(document).on('submit', "#send-categoria", function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(returnValue) {
                $(".ui-dialog-titlebar-close").trigger('click');
                if (returnValue.id !== '') {
                    showMessage('exit', returnValue.message, 1000, '');
                } else {
                    showMessage('error', 'Error inténtelo nuevamente.', 2000, '');
                }
            }
        });
    });
    
    $(document).on("click", ".btn_close", function(){
        $(".ui-dialog-titlebar-close").trigger('click');
    });
    
    $(document).on("change", "#categoria", function(){
        $("div#categoria-modal").animate({ scrollTop: 0 }, 600);
    });
});