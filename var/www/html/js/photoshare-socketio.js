/**
 * Created by ZE on 2015/11/11.
 */

var socket = io('https://photoshare.space:6001', {secure: true});
socket.on('has_new_comment', function (data) {
        console.log(data);

    if (data.event.id == getEventId()) {
        getComments(getEventId());
    }
});
socket.on('CommentDeleted', function (data) {
    //console.log(data);
    if (data.event.id == getEventId()) {
        getComments(getEventId());
    }
});

socket.on('PhotoAdded', function (data) {
    console.log(data);
    if (data.event.id == getEventId()) {
        getPhotos(data.event.id);
    }
});
socket.on('PhotoDeleted', function (data) {
    console.log(data);
    if (data.event.id == getEventId()) {
        getPhotos(data.event.id);
    }
});