$(document).ready(function () {
    $(document).on('click', '.delete-warning', function () {
        function trans(key, replace) {
            translation = window._translations[key]
                ? window._translations[key]
                : string_key

            _.forEach(replace, (value, key) => {
                translation = translation.replace(':' + key, value)
            })
            return translation
        }
        var id = $(this).data('id');
        var route = $(this).data('route');
        var text =  trans('element_wont_be_recoverable_!');
        if ($(this).data('text') != undefined) {
            text = $(this).data('text');
        }
        swal({
            title: trans('delete_?'),
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: trans('delete'),
            cancelButtonText: trans('do_not_delete'),
            closeOnConfirm: true,
            closeOnCancel: true
        },
            function () {
                $.ajax({
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route + '?id=' + id,
                    success: function () {
                        location.reload();
                    }
                });
            });
    });
});