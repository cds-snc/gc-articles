<?php

use CDS\Modules\Forms\Messenger;
use GuzzleHttp\Exception\ClientException;


test('asserts sendMail returns success message for a successful call', function () {
    /* Mocking a class within a class: https://docs.mockery.io/en/latest/cookbook/mocking_class_within_class.html */
    $clientMock = mock('NotifyClientMock')->expect(
        sendMail: fn () => true,
    );

    $messenger = mock(Messenger::class)->makePartial();
    $messenger->shouldReceive("getNotifyClient")->andReturn($clientMock);
 
    expect($messenger->sendMail('paul.com', 'message'))->toMatchArray(
        ['success' => 'Thanks for the message']
    );
});

test('asserts sendMail returns error message if NotifyClient throws an exception', function () {
    /* Mocking a class within a class: https://docs.mockery.io/en/latest/cookbook/mocking_class_within_class.html */
    $clientMock = mock('NotifyClientMock');
    $clientMock->shouldReceive('sendMail')->andThrow(new \Exception('Bad template ID or something'));

    $messenger = mock(Messenger::class)->makePartial();
    $messenger->shouldReceive("getNotifyClient")->andReturn($clientMock);
 
    expect($messenger->sendMail('paul@paul.ca', 'message'))->toMatchArray(
        [
            'error' => 'Bad template ID or something',
            'error_message' => 'Error sending email'
        ]
    );
});

test('asserts adds Demo tag for demo requests', function () {
    $messenger = new Messenger();
    $goal = "I'm looking for a demo of GC Articles";
    $tags = $messenger->mergeTags($goal);
    expect($tags)->toMatchArray(["articles_api","demo_request"]);

    $goal = "Je cherche une démo de GC Articles";
    $tags = $messenger->mergeTags($goal);
    expect($tags)->toMatchArray(["articles_api","demo_request"]);    
});

test('asserts no demo tag added when not requested', function () {
    $messenger = new Messenger();
    $goal = "I'm looking for contact information";
    $tags = $messenger->mergeTags($goal);
    expect($tags)->toMatchArray(["articles_api"]);

    $goal = "je recherche des coordonnées";
    $tags = $messenger->mergeTags($goal);
    expect($tags)->toMatchArray(["articles_api"]);
});
