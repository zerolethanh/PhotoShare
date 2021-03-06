/**
 * Created by ZE on 26/09/15.
 */

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}
var app = require('http').createServer(handler);
var io = require('socket.io')(app);

var Redis = require('ioredis');
var redis = new Redis();

app.listen(6001, function () {
    console.log('Server is running! at Port:' + String(6001));
});


io.on('connection', function (socket) {
    //
    console.log(socket.id);
});

redis.psubscribe('*', function (err, count) {
    //
});
//
redis.on('pmessage', function (subscribed, channel, message) {
    console.log('emitting');
    console.log(channel);

    message = JSON.parse(message);
    console.log(message);

    //emit channel
    //ex. user.1:App\Events\Chat
    io.emit(channel + ':' + message.event, message.data);//

});