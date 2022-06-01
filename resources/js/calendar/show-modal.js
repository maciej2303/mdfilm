var dayjs = require('dayjs')
window.openShowModal = (event) => {
    $(event.target).fadeIn('slow');
    $(event.target).modal('show');
    let data = event.event;
    $('.select2-show-event-type').val(data.event_type_id);
    $('#show_start_date').val(dayjs(data.start).format('DD.MM.YYYY'));
    $('#show_start_time').val(data.start_time);

    if (data.end != null) {
        $('#show_end_date').val(dayjs(data.end).format('DD.MM.YYYY'));
        $('#show_end_time').val(data.end_time);
    } else {
        $('#show_end_date').val('');
        $('#show_end_time').val('');
    };
    $('#show_event').val(data.event);
    $('#show_description').val(data.description);
    $('#show_project_id').val(data.project_id);
    $('#show_members').select2().val(data.members.map(s => s.id)).trigger("change");
    $('#show_members').prop("disabled", true);
    $('.delete-warning').data("id", data.id);
    $('.edit-modal-button').data("event", data);
}
