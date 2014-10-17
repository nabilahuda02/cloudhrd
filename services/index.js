var amqpConnectionOptions = {
    host: (process.env['RABBITMQ_PORT_5672_TCP_ADDR'] || 'localhost'), 
    port: 5672, 
    login: 'guest', 
    password: 'guest'
};
var amqpQueueOptions = {
    durable: true,
    autoDelete: false
};
var amqpExchangeName      = 'deploy';
var amqpQueueName         = 'deploy-company';
var serviceName           = 'Company Deploy';

var async = require('async');
var amqp = require('amqp');
var fs = require('fs');

async.waterfall([
    /**
     * Creates AMQP Connection
     */
    function(callback) {
        var connection = amqp.createConnection(amqpConnectionOptions, {
            reconnect: true
        });
        connection.on('ready', function(){
            callback(null, {
                connection: connection
            });
        });
    },
    /**
     * Creates Exchanges
     */
    function(context, callback) {
        context.connection.exchange(amqpExchangeName, {
            durable: true,
            type: 'fanout',
            autoDelete: false
        }, function (queue) {
            callback(null, context);
        });
    },
    /**
     * Creates Queues
     */
    function(context, callback) {
        context.connection.queue(amqpQueueName, amqpQueueOptions, function (queue) {
            queue.bind(amqpExchangeName, 'all', function(){
                context.queue = queue;
                callback(null, context);
            });
        });
    }
], function(err, context){
    console.info(serviceName + ' Ready');

    var queue = context.queue;

    queue.subscribe(function(payload){
        var path = JSON.parse((new Buffer(payload.data)).toString());
        console.info(path);
    });

    process.on('exit', function() {
      context.connection.close();
    });
});



