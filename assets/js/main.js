jQuery(document).ready(function($){

    if ( $('.datepicker').length > 0 ) {
        $('.datepicker').datepicker();
    }

    if ( $('.customiser').length > 0 ) {
        $('.customiser' ).resizable({
            maxWidth: 700,
            minWidth: 300
        });
    }

    // This should persist across the site
    var customiserState = getCookie("customiser");
    if('open' == customiserState) {
        $( ".customiser" ).show();
        $( "body" ).addClass( "customiser-open" );
    }

    $( ".customiser-open-button, .customiser-close-button" ).click(function() {

        if($('.customiser').is(':hidden')) {
            $( "body" ).addClass( "customiser-open" );
            $( ".customiser" ).show();
            setCookie("customiser","open", 365);
        } else {
            $( "body" ).removeClass( "customiser-open" );
            $( ".customiser" ).hide();
            setCookie("customiser","close", 365);
        }

    });

    $( ".meta-box input, .meta-box textarea" ).keyup(function() {
        
        $(this).parent().find('.dashicons-yes, .loading').hide();
        $(this).parent().find('.dashicons-update').show();

        // Get the value and the selector
        var new_value = $(this).val();
        var selector = $(this).parent().find('.dashicons-update').data('selector');

        // Live update the text
        $(selector).text(new_value);
            
    });

    $( ".meta-box select" ).change(function() {
        
        $(this).parent().find('.dashicons-yes, .loading').hide();
        $(this).parent().find('.dashicons-update').show();

        var new_value = $(this).val();
        var selector = $(this).parent().find('.dashicons-update').data('selector');

        // Live update the text
        $(selector).text(new_value);
            
    });

    $( ".dashicons-star-empty, .dashicons-star-filled" ).click(function() {
        $(this).toggleClass('dashicons-star-empty');
        $(this).toggleClass('dashicons-star-filled');
    });

    $( ".lu-update" ).click(function() {

        // Cache this
        var $this = $(this);

        // Get data attriubute of update clicked
        var field_id = $this.data('field');
        var selector = $this.data('selector');
        var field_type = $this.data('type');

        switch (field_type) {

            case "select":
                var new_value = $("select[name=lu_" + field_id).val();
                break;

            case "author":
                var new_value = $("select[name=lu_" + field_id).val();
                break;                

            case "textarea":
                var new_value = $("textarea[name=lu_" + field_id).val();
                break; 

            case "content":
                var new_value = tinymce.get(field_id).getContent();
                break; 

            case "wysiwyg":
                var new_value = tinymce.get(field_id).getContent();
                break;                 

            case "checkbox":
                var checkbox = $("input[name=lu_" + field_id);
                if ( true == $(checkbox).prop('checked') ) {
                   new_value = 1; 
                }
                break; 

            case "featured":
                var star = $("#lu_featured");
                if ( $(star).hasClass("dashicons-star-filled") ) {
                    new_value = 1;
                }
                break; 

            case "featured-image":
                var new_value = $("input[name=featured-image-id]").val();
                break; 

            default:
                var new_value = $("input[name=lu_" + field_id).val();
            
        }

        // Validate Fields
        if ('email' == field_type) {
            if (!validateEmail(new_value)) {
                $("input[name=lu_" + field_id).addClass('error');
                return;
            } else {
               $("input[name=lu_" + field_id).removeClass('error'); 
            }
        }        

        // Hide the update button
        $this.hide();

        // Show loading animation
        $( "#loading-" + field_id ).show();

        // Disable the input
        $("#lu_" + field_id).prop('disabled', true);

        // Set up the data
        var data = {
            action: 'lu_update_value',
            nonce: lu.nonce,
            post_id: lu.post_id,
            field_id: field_id,
            field_type: field_type,
            new_value: new_value
        };

        $.post(lu.ajaxurl, data, function(response) {

            $('.loading').hide();
            $("#success-" + field_id ).show();

            // Re enable the input
            $("#lu_" + field_id).prop('disabled', false);

        });


    });

    // Uploading files
    var file_frame;

    $('.upload_image_button').live('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            library : {
                type : 'image',
            },
            button: {
                text: 'Choose an Image',
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            if (attachment.sizes.length > 0) {
                $('.featured-image').attr("src", attachment.sizes.medium.url);
            } else {
                $('.featured-image').attr("src", attachment.url);
            }
            $('input[name=featured-image-id]').val(attachment.id);

            $('.upload_image_button').hide();
            $('.existing-image').show();
        });

        // Finally, open the modal
        file_frame.open();
    });
  
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if( !emailReg.test( $email ) ) {
        return false;
    } else {
        return true;
    }
}

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)==0) return c.substring(name.length,c.length);
    }
    return "";
}