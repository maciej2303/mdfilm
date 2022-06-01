var dayjs = require('dayjs')
$(document).ready(function () {

    $(".select2-event-type").select2({
        width: '100%',
    });

    $(".select2-projects").select2({
        width: '100%',
    });

    $(".select2-team").select2({
    width: '100%',
    });

    $('#addEndDatepicker').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: 'pl',
        format: "dd.mm.yyyy",
    });

    $('#addStartDatepicker').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: 'pl',
        format: "dd.mm.yyyy",
    });

    $('#addStartDatepicker').datepicker()
        .on("changeDate", function (e) {
            $('#addEndDatepicker').datepicker('setStartDate', e.date);
            setEndDate()
        });

    $('#addEndDatepicker').datepicker()
        .on("changeDate", function (e) {
            $('#addStartDatepicker').datepicker('setEndDate', e.date);
        });

    $('#startTimePicker').datetimepicker({
        format: 'HH:mm',
    });

    $("#startTimePicker").on("change.datetimepicker", ({ date, oldDate }) => {
        if ($('#start_date').val() != '' && $('#end_date').val() != '' && $('#start_date').val() == $('#end_date').val()) {
            $('#endTimePicker').datetimepicker("minDate", date);
            if (start_time >= end_time) {
                $('#end_time').val('')
            }
        }
    })

    $('#endTimePicker').datetimepicker({
        format: 'HH:mm',
    });

    $("#addEvent").on('shown.bs.modal', function (e) {
        let event_type_id = $(e.relatedTarget).data('event-type');
        if (event_type_id != undefined) {
            $('.select2-event-type').val(event_type_id);
            $('.select2-event-type').select2().trigger('change');
            let date = new Date();
            $("#addStartDatepicker").datepicker("setDate", date);
        }
        setEndDate()
    });
});

let setEndDate = () => {
    let date = new Date($("#addStartDatepicker").datepicker("getDate"));
    let maxDate = new Date(date.setDate(date.getDate() +  14));
    $('#addEndDatepicker').datepicker("setEndDate", maxDate);
}
