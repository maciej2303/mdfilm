$(document).ready(function () {

    $(".select2-project").select2({
        width: '100%',
    });

    $(".select2-workTimeType").select2({
        width: '100%',
    });

    $('#new-work-time').on('click', function (event) {
        let inputs = $('.inputs');
        let original = $('.inputs').find('.module:last');

        let selectedWorkTimeType = original.find('.select2-workTimeType option:selected').val();
        let selectedProject = original.find('.select2-project option:selected').val();
        original.find('.select2-project').select2('destroy');
        original.find('.select2-workTimeType').select2('destroy');

        let clone =  original.clone().removeClass('d-none');

        clone.attr("id", $('.module').length + 1);
        clone.find('.logged-hours').val('');
        clone.find('.task').val('');
        clone.find('.text-danger').addClass('d-none');


        original.find('.select2-project').select2({
            width: '100%',
        });

        original.find('.select2-workTimeType').select2({
            width: '100%',
        });

        clone.find('.select2-project').select2({
            width: '100%',
        });

        clone.find('.select2-workTimeType').select2({
            width: '100%',
        });

        $('.inputs').append(clone);

        clone.find('.select2-project').select2('val', selectedProject);
        clone.find('.select2-workTimeType').select2('val', selectedWorkTimeType);
        $('.addWorkTimeDatepicker').datepicker({
            todayBtn: "linked",
            autoclose: true,
            language: 'pl',
            format: "dd.mm.yyyy",
        });
    });
    $('.addWorkTimeDatepicker').datepicker({
        todayBtn: "linked",
        autoclose: true,
        language: 'pl',
        format: "dd.mm.yyyy",
    });

    if ($('#date0').val() == undefined) {
        $('.addWorkTimeDatepicker').datepicker({
            todayBtn: "linked",
            autoclose: true,
            language: 'pl',
            format: "dd.mm.yyyy",
        }).datepicker("setDate", 'now');
    }
});
