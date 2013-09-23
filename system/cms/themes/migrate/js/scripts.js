$.ajaxSetup({
    cache: false
});
$(document).on("click","a",function(){
    $(this).blur();
});
$(document).on('ready', function(){
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});