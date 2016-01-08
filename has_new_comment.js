/**
 * Created by ZE on 26/09/15.
 */

//function handler(req, res) {
//    res.writeHead(200);
//    res.end('');
//}

var SSL_KEY = '/etc/letsencrypt/live/photoshare.space/privkey.pem';
var SSL_CERT = '/etc/letsencrypt/live/photoshare.space/cert.pem';
var CA = '/etc/letsencrypt/live/photoshare.space/chain.pem';

var fs = require('fs');

var httpsOptions = {
    key: fs.readFileSync(SSL_KEY).toString(),
    cert: fs.readFileSync(SSL_CERT).toString(),
    ca: fs.readFileSync(CA).toString()
};

var app = require('https').createServer(httpsOptions);


var io = require('socket.io').listen(app);


app.listen(6001, function () {
    console.log('Server is running! at Port:' + String(6001));
});


io.on('connection', function (socket) {
    //
    console.log(socket.id);
});


var Redis = require('ioredis');
var redis = new Redis(
    {
        port: 6379,          // Redis port
        host: '127.0.0.1',   // Redis host
        family: 4,           // 4 (IPv4) or 6 (IPv6)
        password: 'password',//'OYtjt7TRs72StScD2mI4Lfsl5Ce0',
        db: 0
    }
);

redis.psubscribe('*', function (err, count) {
    //
    console.log('psubscribe');
    console.log(count);
});
//
redis.on('pmessage', function (subscribed, channel, message) {
    console.log('emitting');
    console.log(channel);

    message = JSON.parse(message);
    console.log(message);

    //emit channel
    //ex. user.1:App\Events\Chat
    io.emit(channel, message.data);//

});