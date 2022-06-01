$(document).ready(function () {
    $("#addTodo").on('shown.bs.modal', function (e) {
        let data = $(e.relatedTarget).data('content');
        if (data != undefined) {
            let description = data.replace(/<a[^>]*>|<\/a>/g, "");
            description = description.replace(/<span[^>]*>|<\/span>/g, "");
            description = description.replace(/\<br\>/g, " ");
            $(this).find('#content-field').html(description)
            $(this).find('.input-content').val(description)
        }
    });
});

