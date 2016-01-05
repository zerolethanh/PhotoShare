<?
?>
<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.log = function (message) {
            if (window.console && window.console.log) {
                window.console.log(message);
            }
        };

        var pusher = new Pusher('71dc89130c26ea193c53', {
            encrypted: true
        });
        var channel = pusher.subscribe('my_channel');
        channel.bind('new_message', function (data) {
//            alert(data.message);
            $('#message').append('<br>' + data.name + ':' + data.message);
        });
    </script>
</head>

<body>
<h1>Pusher Test</h1>

<div>
    <span id="message"></span>
</div>
</body>