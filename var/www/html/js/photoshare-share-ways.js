/**
 * Created by ZE on 2015/11/11.
 */

$('#newAlbum').on('shown.bs.modal', function () {

    $(this).find('input:text:visible:first').focus();

    switch ($('#or_time').prop('type')) {
        case 'date':
            document.getElementById('or_time').valueAsDate = new Date();
            break;
        default :
            $('#or_time').val(new Date().toISOString().slice(0, 10).replace('T', ' '));
    }

});
$('#share_via_mail').on('shown.bs.modal', function () {

    $(this).find('input:text:visible:first').focus();

});
