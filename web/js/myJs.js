$(document).ready(function() {
    $overlay = $('<div id="overlay"></div>');
    $("body").append($overlay);

    $('#body-cover').height($(window).height());

    $('#overlay').click(function(){
        $(this).hide();
        $('#newAppointment').hide();
        $('#client').show();
        $('#product').show();
        $('#event').hide();
    });
});
