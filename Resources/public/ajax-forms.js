$(document).ready(function () {
    $('body').on('submit', 'form.ajax', function (e) {
        e.preventDefault();

        var $form = $(this);

        if ($form.data('submitted')) {
            return;
        }

        $form.data('submitted', true).find('[type="submit"]').append(AJAX_LOADER);

        $.ajax({
            data: $form.serialize(),
            type: $form.attr('method'),
            url:  $form.attr('action')
        }).done(function (data) {
            $form.replaceWith(data.html);
        }).error(onAjaxError);
    });
});
