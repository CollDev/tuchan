$.ajaxSetup({
    cache: false
});
$(document).on('ready', function() {
    $.getJSON("/cmsapi/getcanaleslist")
        .done(function(res) {
            $.each(res, function(key, value) {
                $('#canal_search_id')
                    .append($('<option>', { value: key })
                    .text(value));
            });
        });
    $.getJSON("/cmsapi/getcategoriaslist")
        .done(function(res) {
            $.each(res, function(key, value) {
                if ($.isNumeric(key)) {
                    $('#categoria')
                        .append($('<option>', { value: key })
                        .text(value));
                } else {
                    var optiong = $('<optgroup>', { label: key });
                    $.each(value, function(keyg, valueg) {
                        $(optiong)
                            .append($('<option>', { value: keyg })
                            .text(valueg));
                    });
                    $('#categoria')
                        .append(optiong);
                    optiong = null;
                }
            });
        });
    $.getJSON("/cmsapi/getprogramaslist/" + $('#canal_id').val())
        .done(function(res) {
            $('#programa')
                .find('option')
                .remove();
            $('#programa')
                .append($('<option>', { value: '0' })
                .text('Seleccione programa'));
            $.each(res, function(key, value) {
                $('#programa')
                    .append($('<option>', { value: key })
                    .text(value));
            });
        });
    $('#programa').on('change', function() {
        $('#coleccion').trigger('change');
        $('#lista').trigger('change');
        var programa_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getcoleccioneslist/" + programa_id)
            .done(function(res) {
                $('#coleccion')
                    .find('option')
                    .remove();
                $('#coleccion')
                    .append($('<option>', { value: '0' })
                    .text('Seleccione colección'));
                $.each(res, function(key, value) {
                    $('#coleccion')
                        .append($('<option>', { value: key })
                        .text(value));
                });
            });
    });
    $('#coleccion').on('change', function() {
        $('#lista').trigger('change');
        var coleccion_id = $(this).find(":selected").val();
        $.getJSON("/cmsapi/getlistaslist/" + coleccion_id)
            .done(function(res) {
                $('#lista')
                    .find('option')
                    .remove();
                $('#lista')
                    .append($('<option>', { value: '0' })
                    .text('Seleccione lista'));
                $.each(res, function(key, value) {
                    $('#lista')
                        .append($('<option>', { value: key })
                        .text(value));
                });
            });
    });
    function showMessage(type, title) {
        $('#flash_title').html('').append(title);
        var del = 0;
        if ($(window).scrollTop() !== 0) {
            $("html, body").animate({ scrollTop: 0 }, 600);
            del = 600;
        }
        $('#flash_message').removeClass().addClass('alert').addClass('alert-' + type).delay(del).slideDown().delay(3000).slideUp();
    }
    $('#submit_upload').on('click', function(){
        var titulo = $("input#titulo"),

        video = $("input#video"),

        fecha_transmision = $("input#fecha_transmision"),
        hora_trans_ini = $("input#hora_trans_ini"),
        hora_trans_fin = $("input#hora_trans_fin"),

        descripcion = $("input#descripcion"),
        tematicas = $("input#tematicas"),
        personajes = $("input#personajes"),

        categoria = $("select#categoria"),
        programa = $("select#programa"),
        coleccion = $("select#coleccion"),
        lista = $("select#lista"),

        horaRegexp = /^([0-1][0-9]|[2][0-3]):([0-5][0-9])$/;

        if (titulo.val() === "") {
            showMessage('danger','Debe ingresar un título.');
            titulo.focus();
            return false;
        } else if (video.val() === "") {
            showMessage('danger','Debe seleccionar un video.');
            video.focus();
            return false;
        } else if (fecha_transmision.val() === "") {
            showMessage('danger','Debe seleccionar una fecha de transmisión.');
            fecha_transmision.focus();
            return false;
        } else if (hora_trans_ini.val() === "") {
            showMessage('danger','Debe seleccionar una hora de inicio de transmisión.');
            hora_trans_ini.focus();
            return false;
        } else if (!hora_trans_ini.val().match(horaRegexp)) {
            showMessage('danger','Seleccione una hora de inicio de transmisión válida.');
            hora_trans_ini.focus();
            return false;
        } else if (hora_trans_fin.val() === "") {
            showMessage('danger','Debe seleccionar una hora de fin de transmisión.');
            hora_trans_fin.focus();
            return false;
        } else if (!hora_trans_fin.val().match(horaRegexp)) {
            showMessage('danger','Seleccione una hora de fin de transmisión válida.');
            hora_trans_fin.focus();
            return false;
        } else if (hora_trans_fin.val() <= hora_trans_ini.val()) {
            showMessage('danger','La hora inicio debe ser menor que la hora de fin de transmisión.');
            hora_trans_ini.focus();
            return false;
        } else if (descripcion.val() === "") {
            showMessage('danger','Debe ingresar una descripción.');
            descripcion.focus();
            return false;
        } else if (tematicas.val() === "") {
            showMessage('danger','Debe ingresar una o varias temáticas.');
            tematicas.focus();
            return false;
        } else if (personajes.val() === "") {
            showMessage('danger','Debe ingresar uno o varios personajes.');
            personajes.focus();
            return false;
        } else if (categoria.find(':selected').val() === "0") {
            showMessage('danger','Debe seleccionar una categoría.');
            categoria.focus();
            return false;
        }
    });
    
    var bar = $('.bar'),
    percent = $('.percent'),
    status = $('#status'),
    progress = $('.progress');

    $('form#upload_form').ajaxForm({
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
        success: function() {
            var percentVal = '100%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        complete: function(xhr) {
            progress.delay(1000).slideUp(1000, function(){
            $('.wrap').prepend('<div class="alert alert-' + xhr.responseJSON.type + ' fade in">\n\
                    <button class="close" data-dismiss="alert" type="button">×</button>\n\
                    <strong>' + xhr.responseJSON.title + '</strong>\n\
                    ' + xhr.responseJSON.message + '\n\
                </div>');
            });
            percent.delay(1000).slideUp();
        }
    });
    $('#search_form').on('submit', function(e) {
        e.preventDefault();
        if ($('#termino').val() !== '') {
            $("div#search_results").html('<div class="text-center"><img src="/system/cms/themes/default/img/loading.gif" /></div>');
            $.getJSON("/cmsapi/search/" + $('#termino').val(), {
                canal_id: $("#canal_search_id").val(),
                fecha_inicio: $("#fecha_inicio").val(),
                fecha_fin: $("#fecha_fin").val()
            })
                .done(function(data) {
                    $.get("/system/cms/themes/default/js/mustache_templates/cmsapi/search_results.html", function(template) {
                        $("div#search_results").html('');
                        if (data.videos === '') {
                            var app = '<div class="text-center"><h3>No hay resultados para la búsqueda:</h3><h3>' + $('#termino').val() + '</h3></div>';
                        } else {
                            var app = $.mustache(template, data);
                        }
                        $("div#search_results").append(app);
                    });
                });
        }
    });
    $(document).on('click', 'a', function() {
        $(this).blur();
    });
    $(document).on('keydown', function(event) {
        if (event.which === 27) {
            event.preventDefault();
            $('table#table tbody tr').removeClass('success');
            $('a#use-this-video').addClass('disabled');
        }
    });
    $(document).on('click', 'a#use-this-video', function(e) {
        e.preventDefault();
        console.log($(this).attr('href'));
        parent.getDataIframe($(this).attr('href'));
    });
    $(document).on('click', 'a.corte_video', function() {
        $("div#cut_form_tab").html('<div class="text-center"><img src="/system/cms/themes/default/img/loading.gif" /></div>');
        $('ul#myTab li.disabled a').attr('data-toggle', 'tab').parent().removeClass('disabled');
        $('ul#myTab li a#corte_video').trigger('click');

        var $this = this;
        var tr_id = $($this).parent().parent().attr('id');
        var split = tr_id.split('_');

        $.getJSON("/cmsapi/corte/" + split[1])
            .done(function(data) {
                $.get("/system/cms/themes/default/js/mustache_templates/cmsapi/cut_video.html", function(template) {
                    $("div#cut_form_tab").html('');
                    if (data === '') {
                        var app = '<div class="text-center"><h3>Seleccione un video válido</h3></div>';
                    } else {
                        var app = $.mustache(template, data);
                    }
                    $("div#cut_form_tab").append(app);
                });
            });
    });
    $(document).on('click', '#submit_cut', function(e) {
        e.preventDefault();
        var values = {},
        horaRegexp = /^([0-1][0-9]|[2][0-3]):([0-5][0-9])$/;
        
        $.each($('#cut_form').serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });
        values['tematicas'] = $.trim(values['tematicas']);
        values['personajes'] = $.trim(values['personajes']);
        
        if (values['dur_corte'] === '') {
            showMessage('danger', 'Debe realizar un corte.');
            return;
        } else if (values['dur_corte'] >= Math.round(values['dur_total'] * 10) / 10) {
            showMessage('danger', 'La duracion del nuevo video debe ser diferente al original.');
            $("form#cut_form input#dur_corte").focus();
            return;
        } else if (values['titulo'] === '') {
            showMessage('danger', 'Ingrese un título.');
            $("form#cut_form input#titulo").focus();
            return;
        } else if (values['fec_trans'] === '') {
            showMessage('danger', 'Ingrese una fecha de transmisión.');
            $("form#cut_form input#cut_transmision").focus();
            return;
        } else if (values['hora_trans_ini'] === '') {
            showMessage('danger', 'Debe ingresar una hora de inicio de transmisión.');
            $("form#cut_form input#hora_trans_cut_ini").focus();
            return;
        } else if (!values['hora_trans_ini'].match(horaRegexp)) {
            showMessage('danger', 'Ingrese una hora de inicio de transmisión válida.');
            $("form#cut_form input#hora_trans_cut_ini").focus();
            return;
        } else if (values['hora_trans_fin'] === '') {
            showMessage('danger', 'Debe ingresar una hora de fin de transmisión.');
            $("form#cut_form input#hora_trans_cut_fin").focus();
            return;
        } else if (!values['hora_trans_fin'].match(horaRegexp)) {
            showMessage('danger', 'Ingrese una hora de fin de transmisión válida.');
            $("form#cut_form input#hora_trans_cut_fin").focus();
            return;
        } else if (values['hora_trans_fin'] <= values['hora_trans_ini']) {
            showMessage('danger','La hora inicio debe ser menor que la hora de fin de transmisión.');
            $("form#cut_form input#hora_trans_cut_ini").focus();
            return;
        } else if (values['descripcion'] === '') {
            showMessage('danger', 'Ingrese la descripción del video.');
            $("form#cut_form input#descripcion").focus();
            return;
        } else if (values['tematicas'] === '') {
            showMessage('danger', 'Ingrese las temáticas.');
            $("form#cut_form input#tematicas").focus();
            return;
        } else if (values['personajes'] === '') {
            showMessage('danger', 'Ingrese los personajes.');
            $("form#cut_form input#personajes").focus();
            return;
        }
        $.ajax({
            type: "POST",
            url: "/admin/videos/insertCorteVideo/" + values['canal_id'] + "/" + values['video_id'],
            dataType: 'json',
            data: $('#cut_form').serialize(),
            success: function(returnValue) //we're calling the response json array 'cities'
            {
                if (returnValue.value == 0) {
                    showMessage('success', 'El corte se guardó satisfactoriamente');
                    setTimeout(function() {
                        var $use_this_video = '<div class="row"><a class="btn btn-default col-lg-2 text-center" href="' + $('#motor').val() + '/embed/' + returnValue.video_id + '" id="use-this-video">Usar este corte de video</a></div>';
                        $("div#cut_form_tab").html('').append($use_this_video);
                    }, 4200);
                } else {
                    showMessage('warning', 'Ya existe un video con estos datos.');
                }
            }
        });
    });
    $("#fecha_transmision").datepicker({ altField: "#fec_trans" });
    $(document).on('keypress', "#fecha_transmision", function(){
        $("#fec_trans").val('');
    });
    $("#hora_trans_ini").timepicker();
    $("#hora_trans_fin").timepicker();
    $("#fec_ini").datepicker({
        altField: "#fecha_inicio",
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fec_fin").datepicker("option", "minDate", selectedDate);
        }
    });
    $(document).on('keypress', "#fec_ini", function(){
        $("#fecha_inicio").val('');
    });
    $("#fec_fin").datepicker({
        altField: "#fecha_fin",
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#fec_ini").datepicker("option", "maxDate", selectedDate);
        }
    });
    $(document).on('keypress', "#fec_fin", function(){
        $("#fecha_fin").val('');
    });
    $('#tematicas').tagsInput({
        autocomplete_url: '/admin/videos/tematicas',
        defaultText: '',
        height: '38px',
        width: '100%'
    });
    $('#personajes').tagsInput({
        autocomplete_url: '/admin/videos/personajes',
        defaultText: '',
        height: '38px',
        width: '100%'
    });
    
});