$(document).ready(function () {
    $( "#editTodo" ).on('shown.bs.modal', function(e){
        let data = $(e.relatedTarget).data('task');
        if (data == undefined) {
            data = JSON.parse($(this).find('#task').val());
        } else {
            $(this).find('#content-field').html(data.content);
            $(this).find('.input-content').val(data.content);
        }
        $(this).find('#task_id').val(data.id);
        $(this).find('#task').val(JSON.stringify(data));
        let route = window.location.origin + '/tasks/update/' + data.id;
        $('#form_edit').attr("action", route);
        let deleteRoute = window.location.origin + '/tasks/delete/';
        $(this).find('#delete-button').data('route', deleteRoute);
        $(this).find('#delete-button').data('id', data.id);
    });
});

