/**
 * Created by ZE on 2015/11/11.
 */

var socket = io('https://photoshare.link:6001', {secure: true});
socket.on('has_new_comment', function (data) {
    if (data.event.id == getEventId()) {
        try {
            gallery.close();
        } catch (e) {

        }
        getComments(getEventId());
    }
});
socket.on('CommentDeleted', function (data) {

    if (data.event.id == getEventId()) {
        try {
            gallery.close();
        } catch (e) {

        }
        getComments(getEventId());
    }
});

socket.on('PhotoAdded', function (data) {

    if (data.event.id == getEventId()) {

        try {
            window.gallery.close();
        }catch(e){

        }
        getPhotos();

    }
});
socket.on('PhotoDeleted', function (data) {

    if (data.event.id == getEventId()) {
        try {
            window.gallery.close();
        }catch(e){

        }
        getPhotos();
    }
});
