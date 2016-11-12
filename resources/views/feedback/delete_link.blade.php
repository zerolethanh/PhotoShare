<script>
    function community_delete($id) {
        if ($id == null)
            return;
        console.log('/community/delete/' + $id);
        $.post('/community/delete/' + $id)
                .always(function ($response) {

//                    debug($response)

                    if ($response.succ !== undefined && $response.succ == true) {
                        $('#delete_success_modal .modal-body').html('<p>DELETED</p>');
                        location.reload();
                    } else {
                        $('#delete_success_modal .modal-body').html('<p>FAILED</p>');
                        $('#delete_success_modal').modal();
                    }
                    //show modal
//

                });
    }
</script>

