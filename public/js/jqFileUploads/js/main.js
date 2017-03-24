$(function () {
    'use strict';

    var f = $('#fileupload');

    f.bind('fileuploadsubmit', function (e, data) {

        var gid = $("meta[name=gid]").attr('content');
        var _token = $("meta[name=_token]").attr('content');

        var event_id = $("meta[name=event_id]").attr('content');


        data.formData = [
            {name: 'gid', value: gid},
            {name: 'event_id', value: event_id},
            {name: '_token', value: _token},
        ];
    });

    // Enable iframe cross-domain access via redirect option:
    f.fileupload(
        {
            url: '/photo',
            //done:function(res){
            //    console.log('done');
            //    console.log(res);
            //}
        }
    );

    f.addClass('fileupload-processing');
    $.ajax({
        url: f.fileupload('option', 'url'),
        dataType: 'json',
        context: f[0],
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, $.Event('done'), {result: result});
    });

});
