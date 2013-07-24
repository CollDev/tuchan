/* Inicialización en español para la extensión 'UI date picker' para jQuery. */
jQuery(function($){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '&#x3c;Ant',
        nextText: 'Sig&#x3e;',
        currentText: 'Hoy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
        'Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: "DD dd 'de' MM 'de' yy",
        altFormat: "dd-mm-yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        changeYear: true,
        yearSuffix: '',
        yearRange: "c-20:c"
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    $.timepicker.regional['es'] = {
        timeOnlyTitle: 'Elija la hora',
        timeText: '',
        hourText: 'Hora',
        minuteText: 'Minuto',
        secondText: 'Segundo',
        millisecText: 'Milsegundo',
        timezoneText: 'Zona horaria',
        currentText: 'Ahora',
        closeText: 'Cerrar',
        timeFormat: 'HH:mm',
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false
    };
    $.timepicker.setDefaults($.timepicker.regional['es']);
});

