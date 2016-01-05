<? ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IO Chat</title>

    <meta name="fromUserId" content="<?= $user->id ?>"/>

    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.7/socket.io.min.js"></script>
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>

    <script>
        var socket = io('http://153.126.144.175:6001');
        //on channel
        //ex. user.1:App\Events\Chat
//        socket.on('user.<?//=$user->id;?>//:App\\Events\\Chat', function (data) {
//            console.log(data);
//            $('#data').append('<br>' + data.user.email + ':' + data.message + '<br>');
//        });

        socket.on('to_user_id.<?=$user->id;?>:App\\Events\\Chat', function (data) {
            $('#data').append('<br>' + data.from_user.email + ':' + data.message + '<br>');
        });
    </script>
</head>
<body>
<h1>Chat </h1>

<div>
    <span id="data"></span>

    <form id="chat_form" action="/chat" method="POST">
        <table>
            <tr>
                <?= csrf_field(); ?>

                <td align="right">TO USER ID :　</td>
                <td><input type="number" name="to_user_id"/></td>
            </tr>
            <tr></tr>
            <tr>
                <td align="right">MESSAGE :　</td>
                <td><textarea name="message" cols=40 rows=4></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" id="send_msg_button">送信</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    $('#send_msg_button').on('click', function (event) {
        event.preventDefault();
        $.post('/chat', $('#chat_form').serialize())
            .done(function (data) {
                console.log(data);
            })
    });
</script>
</body>
</html>
