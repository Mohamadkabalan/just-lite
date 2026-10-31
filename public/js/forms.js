/*==============================================================*/
// Public forms (contact page, booking modal)
/*==============================================================*/
// Forms marked data-ajax-form are sent with AJAX. The result is shown inside
// the form: server errors under their field, other messages in .form-status.
// The texts come from the form's data-messages (translated in Blade) and from
// the server's JSON. data-hide-on-success hides the fields after a booking
// until the modal is closed.
(function ($) {
    'use strict';

    function clearErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid').removeAttr('aria-invalid');
        $form.find('.field-error').remove();
    }

    function showStatus($form, type, text) {
        $form.find('.form-status')
            .removeClass('text-success text-danger')
            .addClass(type === 'success' ? 'text-success' : 'text-danger')
            .text(text || '');
    }

    function showFieldErrors($form, errors, fallback) {
        var unplaced = [];

        $.each(errors, function (field, messages) {
            var $input = $form.find('[name="' + field + '"]').not('[type=hidden]');

            if ($input.length && field !== 'website') {
                $input.addClass('is-invalid').attr('aria-invalid', 'true');
                $('<div class="field-error text-danger"></div>').text(messages[0]).insertAfter($input);
            } else {
                unplaced.push(messages[0]);
            }
        });

        showStatus($form, 'error', unplaced.length ? unplaced.join(' ') : fallback);
        $form.find('.is-invalid').first().trigger('focus');
    }

    $(document).on('submit', 'form[data-ajax-form]', function (event) {
        // The contact page's validator plugin cancels invalid submissions itself.
        if (event.isDefaultPrevented()) {
            return;
        }
        event.preventDefault();

        var form = this;
        var $form = $(form);
        var messages = $form.data('messages') || {};
        var $button = $form.find('[type=submit]').prop('disabled', true).attr('aria-busy', 'true');

        clearErrors($form);
        showStatus($form, 'error', '');

        $.ajax({ url: form.action, method: 'POST', data: $form.serialize(), dataType: 'json' })
            .done(function (data) {
                if (data.status == 200) {
                    form.reset();
                    showStatus($form, 'success', data.message);
                    if ($form.is('[data-hide-on-success]')) {
                        $form.find('.form-fields').hide();
                        // The focused button is gone: keep focus in the dialog, on the message.
                        $form.find('.form-status').attr('tabindex', '-1').trigger('focus');
                    }
                } else {
                    showStatus($form, 'error', data.message || messages.failed);
                }
            })
            .fail(function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    showFieldErrors($form, xhr.responseJSON.errors, messages.check);
                } else if (xhr.status === 429) {
                    showStatus($form, 'error', messages.tooMany);
                } else {
                    showStatus($form, 'error', messages.failed);
                }
            })
            .always(function () {
                $button.prop('disabled', false).removeAttr('aria-busy');
            });
    });

    // Clear a field's error as soon as it is edited.
    $(document).on('input change', 'form[data-ajax-form] .is-invalid', function () {
        $(this).removeClass('is-invalid').removeAttr('aria-invalid').next('.field-error').remove();
    });

    // A closed booking modal starts fresh next time.
    $(document).on('hidden.bs.modal', '.modal', function () {
        var $form = $(this).find('form[data-ajax-form]');
        clearErrors($form);
        showStatus($form, 'error', '');
        $form.find('.form-fields').show();
    });
}(jQuery));
