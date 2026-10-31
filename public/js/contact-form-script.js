/*==============================================================*/
// Raque Contact Form  JS
/*==============================================================*/
(function ($) {
    "use strict"; // Start of use strict
    // Client-side validation only. A valid form is sent by public/js/forms.js;
    // the messages are translated in the Blade view (data-error, data-invalid-message).
    $("#contactForm").validator().on("submit", function (event) {
        if (event.isDefaultPrevented()) {
            // handle the invalid form...
            formError();
            submitMSG($(this).data('invalid-message'));
            // Keep forms.js from sending an invalid form.
            event.stopPropagation();
        }
    });

    function formError(){
        $("#contactForm").removeClass('shake animated').addClass('shake animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $(this).removeClass('shake animated');
        });
    }

    function submitMSG(msg){
        $("#contactForm .form-status").removeClass('text-success').addClass('text-danger').text(msg);
    }
}(jQuery)); // End of use strict
