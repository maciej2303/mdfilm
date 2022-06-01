var dayjs = require('dayjs')
$(document).ready(function () {

     $('#addEndDatepickerEdit').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: 'pl',
        format: "dd.mm.yyyy",
    });

    $('#addStartDatepickerEdit').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: 'pl',
        format: "dd.mm.yyyy",
    });

    $('#addStartDatepickerEdit').datepicker()
        .on("changeDate", function (e) {
            $('#addEndDatepickerEdit').datepicker('setStartDate', e.date);
            setEndDate();
        });

    $('#addEndDatepickerEdit').datepicker()
        .on("changeDate", function (e) {
            $('#addStartDatepickerEdit').datepicker('setEndDate', e.date);
        });

    $("#editEvent").on('shown.bs.modal', function (e) {
        $("#event_type_id_edit").select2({
            width: '100%',
        });

        $("#project_id_edit").select2({
            width: '100%',
        });

        $("#members_edit").select2({
        width: '100%',
        });

        $('#startTimePickerEdit').datetimepicker({
            format: 'HH:mm',
        });

        $('#endTimePickerEdit').datetimepicker({
            format: 'HH:mm'
        });

        $("#startTimePickerEdit").on("change.datetimepicker", ({ date, oldDate }) => {
            if ($('#start_date_edit').val() != '' && $('#end_date_edit').val() != '' && $('#start_date_edit').val() == $('#end_date_edit').val()) {
                $('#endTimePickerEdit').datetimepicker("minDate", date);
                if (start_time >= end_time) {
                    $('#end_time').val('')
                }
            }
        })

        let data = $(e.relatedTarget).data('event');
        if (data == undefined) {
            data = JSON.parse($(this).find('#event').val());
        }
        else {
            let route = $(e.relatedTarget).data('route');
            let routeFinal = route.replace(':id', data.id);
            $('#form_event_edit').attr("action", routeFinal);
            $('#event_type_id_edit').val(data.event_type_id);
            $('#start_date_edit').val(dayjs(data.start).format('DD.MM.YYYY'));
            $('#addStartDatepickerEdit').datepicker("setDate", dayjs(data.start).format('DD.MM.YYYY'));
            $('#start_time_edit').val(data.start_time);
            if (data.end != null) {
                $('#end_date_edit').val(dayjs(data.end).format('DD.MM.YYYY'));
                $('#addEndDatepickerEdit').datepicker("setDate", dayjs(data.end).format('DD.MM.YYYY'));
                $('#end_time_edit').val(data.end_time);
            } else {
                $('#end_date_edit').val('');
                $('#end_time_edit').val('');
            };
            $('#event_edit').val(data.event);
            $('#description_edit').val(data.description);
            $('#project_id_edit').val(data.project_id);
            $('#members_edit').select2().val(data.members.map(s => s.id)).trigger("change");
        }
        $('#event').val(JSON.stringify(data));


        $("#project_id_edit").select2({
            width: '100%',
        });

    });
});

let setEndDate = () => {
    let date = new Date($("#addStartDatepickerEdit").datepicker("getDate"));
    let maxDate = new Date(date.setDate(date.getDate() +  14));
    $('#addEndDatepickerEdit').datepicker("setEndDate", maxDate);
}