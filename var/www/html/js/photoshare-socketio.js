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


// Enable pusher logging - don't include this in production
//Pusher.log = function (message) {
//    if (window.console && window.console.log) {
//        window.console.log(message);
//    }
//};
//
//var pusher = new Pusher('28af80ca805f6b91bc31', {
//    encrypted: true
//});
//
//var channel = pusher.subscribe('album');
//
//channel.bind('has_new_comment', function (data) {
//    console.log(data);
//
//    if (data.event.id == getEventId()) {
//        getComments(getEventId());
//    }
//});
//channel.bind('CommentDeleted', function (data) {
//    console.log(data);
//    if (data.event.id == getEventId()) {
//        getComments(getEventId());
//    }
//});
//
//channel.bind('PhotoAdded', function (data) {
//    console.log(data);
//    if (data.event.id == getEventId()) {
//        getPhotos(data.event.id);
//    }
//});
//channel.bind('PhotoDeleted', function (data) {
//    console.log(data);
//    if (data.event.id == getEventId()) {
//        getPhotos(data.event.id);
//    }
//});
//
//
////test
//
//channel.bind('my_event', function (data) {
//    alert(data.message);
//});