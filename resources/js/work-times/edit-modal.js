var dayjs = require('dayjs')
$(document).ready(function () {

    $( "#modalWorkTimeEdit" ).on('shown.bs.modal', function(e){
        let data = $(e.relatedTarget).data('worktime');
        if (data == undefined) {
            data = JSON.parse($(this).find('#work_time').val());
        }
        else {
            $(this).find('.text-danger').addClass('d-none');
            $(this).find('.has-error').removeClass('has-error');
            $(this).find('#logged_hours_edit').val(parseFloat(data.logged_hours));
            $(this).find('#task_edit').val(data.task);
            let date = dayjs(data.date).format('DD.MM.YYYY')
            $(this).find('#date_edit').val(date);
            $(this).find('#work_time_id').val(data.id);
            $(this).find('.select2-project-edit').val(data.project_id);
            $(this).find('.select2-workTimeType-edit').val(data.work_time_type_id);
        }
        $(this).find('#work_time').val(JSON.stringify(data));
        let route = $(e.relatedTarget).data('route');
        $('#form_edit').attr("action", route);
        $(this).find('#delete-button').data('id', data.id);
        let deleteRoute = $(e.relatedTarget).data('delete');
        $(this).find('#delete-button').data('route', deleteRoute);
        $(".select2-project-edit").select2({
            width: '100%',
        });

        $(".select2-workTimeType-edit").select2({
            width: '100%',
        });
    });
});

