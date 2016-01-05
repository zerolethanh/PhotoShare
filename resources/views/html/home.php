<? ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.7/socket.io.min.js"></script>
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script>
        var socket = io('http://welapp.net:6001');
        socket.on('user.<?=$user->id;?>:App\\Events\\UserLogin', function (data) {
            console.log(data);
            $('#data').append('<br>' + data.user.name);
        });
    </script>
</head>
<body>
<h1>Works!</h1>

<div>
    <span id="data"></span>
</div>
</body>
</html>
