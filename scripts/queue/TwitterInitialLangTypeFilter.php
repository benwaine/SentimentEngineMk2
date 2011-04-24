<?php

// Set Up 0MQ connection - incoming tweets

$context = new ZMQContext();

$reciever = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$reciever->connect('tcp://localhost:5557');

// Set up ZMQ connection - fan in point

$sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$sender->connect('tcp://localhost:5560');

// Process the incoming Tweets.

while(true)
{

    $tweet = $reciever->recv();

    $tweet = json_decode($tweet, true);

    if(array_key_exists('delete', $tweet) || (array_key_exists('truncated', $tweet) && $tweet['truncated'] == true))
    {
        echo "Tweet Disgarded: Not Tweet. \n";
        continue;
    }
    elseif(array_key_exists('user', $tweet) && $tweet['user']['lang'] != 'en')
    {
        echo "Tweet Disgarded: Not English. \n";
        continue;
    }

    $outTweet = array();

    $outTweet['text'] = $tweet['text'];

    $outString = json_encode($outTweet);

    $sender->send($outString);

    echo "Tweet Sent ... \n";


}


?>