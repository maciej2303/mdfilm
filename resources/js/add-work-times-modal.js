$(document).ready(function () {

    $(".global-select2-project").select2({
        width: '100%',
    });

    $(".global-select2-workTimeType").select2({
        width: '100%',
    });

    $('#global-new-work-time').on('click', function (event) {
        let inputs = $('.global-inputs');
        let original = $('.global-inputs').find('.module:last');

        let selectedWorkTimeType = original.find('.global-select2-workTimeType option:selected').val();
        let selectedProject = original.find('.global-select2-project option:selected').val();
        original.find('.global-select2-project').select2('destroy');
        original.find('.global-select2-workTimeType').select2('destroy');

        let clone =  original.clone().removeClass('d-none');

        clone.attr("id", $('.module').length + 1);
        clone.find('.global-logged-hours').val('');
        clone.find('.global-task').val('');
        clone.find('.text-danger').addClass('d-none');


        original.find('.global-select2-project').select2({
            width: '100%',
        });

        original.find('.global-select2-workTimeType').select2({
            width: '100%',
        });

        clone.find('.global-select2-project').select2({
            width: '100%',
        });

        clone.find('.global-select2-workTimeType').select2({
            width: '100%',
        });

        $('.global-inputs').append(clone);

        clone.find('.global-select2-project').select2('val', selectedProject);
        clone.find('.global-select2-workTimeType').select2('val', selectedWorkTimeType);
        $('.datePickerGlobal').datepicker({
            todayBtn: "linked",
            autoclose: true,
            language: 'pl',
            format: "dd.mm.yyyy",
        });
    });

    $('.datePickerGlobal').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: _lang,
        format: "dd.mm.yyyy",
    });

    if ($('#dateGlobal0').val() == undefined) {
        $('.datePickerGlobal').datepicker({
            todayBtn: "linked",
            autoclose: true,
            language: _lang,
            format: "dd.mm.yyyy",
        }).datepicker("setDate", 'now');
    }
});
