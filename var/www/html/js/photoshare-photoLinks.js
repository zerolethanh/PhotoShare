/**
 * Created by ZE on 2015/11/11.
 */

getPhotos();

var getPhotosData;

function getPhotos(event_id, group_by) {

    event_id = event_id || getEventId();

    var group_by_params = group_by ? "?group_by=" + group_by : "";
    $.post('/events/photos/' + event_id + group_by_params)
        .success(function (data) {//return html

            getPhotosData = data;
            photos = data.photos;
            //global_event = data.event;

            //$('#photos_links').html(data.html.photos_links).append(data.blueimp_gallery);
            $('#photos_links').html(data.photoHTML.links).append(data.blueimp_gallery);

            $('#buttons').html(data.html.buttons);

            //console.log(data.html.other_albums);
            $('#other_albums').html(data.html.other_albums);
            $('#members').html('Members: ' + data.event.members);

            var menuButtonHtml = "<span style='font-size: xx-small' class='text-muted'>"
                + data.event.or_time + '</span>'
                + " <span style='font-size: larger;font-weight: bolder'>"
                + data.event.event_name
                + '</span> <span class="caret"></span>';
            $("#eventsDropDownMenu").html(menuButtonHtml);

            document.title = data.event.event_name + " - PhotoShare";
            focusSelfOrSharedButton(data.event.pivot.admin);

            //initBlueimp();

            setupBlueImp();

            setEventId(data.event.id);
            getComments(data.event.id);

            //var targetY = $("#eventsDropDownMenu").offset().top - 10;
            //$("html,body").animate({scrollTop: 0});
        })
        .fail(function () {
            location.href = "/"
        });
}

function getPhotoIndex() {
    return $('meta[name=photo_index]').attr('content');
}
function setPhotoIndex($newIndex) {
    return $('meta[name=photo_index]').attr('content', $newIndex);
}

moment.locale('ja');

function setupBlueImp() {

    var ids = getPhotosData.photoHTML.ids;

    for (var i = 0; i < ids.length; i++) {
        initBlueimp(ids[i]);
    }
}
function initBlueimp(id) {

    document.getElementById(id /*'links'*/).onclick = function (event) {
        event = event || window.event;

        var can_be_delete_trues = photos.can_be_deleted.true,
            photo;

        var target = event.target || event.srcElement,
            link = target.src ? target.parentNode : target,
            options = {
                index: link,
                event: event,
                toggleControlsOnReturn: false,
                continuous: false,
                carousel: false,
                onslide: function (index, slide) {
                    $('[name="photo_description"]').fadeTo('fast', 0);

                },
                onslideend: function (index, slide) {

                    photo = photos.all[index];
                    //$('[name="photo_title"]').text(data.event.event_name);
                    $('[name="photo_description"]').html(
                        "<div style='font-size:x-small'>"
                        + photo.title
                        + "<br>by " + photo.user.name
                        + " at " + moment(photo.created_at).fromNow()
                        + "</div>"
                    ).fadeTo('fast', 1);


                    if (can_be_delete_trues.indexOf(photos.ids[index]) >= 0) {
                        //delete button show
                        //console.log('can be deleted');
                        $('[name="photo_delete_button"]').attr('disabled', false);
                    } else {
                        //hide photo delete button
                        //console.log('can not be deleted');
                        $('[name="photo_delete_button"]').attr('disabled', true);
                    }

                    setPhotoIndex(index);
                },
                onclosed: function () {
                    getPhotos();
                }

            },

            links = this.getElementsByTagName('a');

        //console.log(link);
        gallery = blueimp.Gallery(links, options);
    };

}


function getLastSelfEvent() {
    $.post('/events/last-self-event')
        .success(function (data) {
            getPhotos(data.event.id);
        })
        .fail(function () {
            location.href = "/"
        });
}
function getLastSharedEvent() {
    $.post('/events/last-shared-event')
        .success(function (data) {
            getPhotos(data.event.id);
        })
        .fail(function () {
            location.href = "/"
        });
}

function focusSelfOrSharedButton(byself) {
    var focus = {on: {'background-color': 'rgb(178,64,48)'}, off: {'background-color': 'white'}};
    $('#self_events_button').css(byself ? focus.on : focus.off);
    $('#shared_events_button').css(byself ? focus.off : focus.on);
}

function ChangeGroupBy($group_by) {
    getPhotos(null, $group_by);
}