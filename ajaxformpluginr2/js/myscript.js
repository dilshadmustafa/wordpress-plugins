jQuery('#submit').on('click', function() {

    let formData = jQuery('#my-form').serializeArray(); // retrieves the form's data as an array

    jQuery.ajax({
        url: ajax_object.ajax_url, // default ajax url of wordpress
        type: 'POST',
        data: {
            action: 'backendProcess', // use this action to handle the event
            route: 'processForm1', // use this route to handle the event
            security: ajax_object.security,
            dataForPHP: formData // this is your form's data that is going to be submitted to PHP by the name of dataForPHP                
        }
    }).done(function( msg ) {
        alert( msg );
    });
    return false;
});