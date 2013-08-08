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
});