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
});