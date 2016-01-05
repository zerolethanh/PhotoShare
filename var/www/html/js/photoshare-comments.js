/**
 * Created by ZE on 2015/11/11.
 */

//add comments
$("#commentButton").click(function () {
    sendComment();
});
function sendComment() {

    var event_id = getEventId();

    $.post("/events/add-comment/" + event_id, {
            "body": $("#commentBody").val(),
        })
        .always(function(data){
            getComments(event_id);
            $("#commentBody").val('').focus();
        });
        //.success(function (data) {
        //
        //    getComments(event_id);
        //    $("#commentBody").val('').focus();
        //
        //});

}


function getComments(event_id) {

    event_id = event_id || getEventId();

    $.post("/events/get-comments/" + event_id)
        .success(function (data) {
            $("#commentList").empty().append(data.commentsHtml);
        });
}


function commentDelete(comment_id) {
    //console.log(comment_id);
    $.post('/comments/delete/' + comment_id)
        .always(function (data) {
            //console.log(data);
            getComments();
        });
}
