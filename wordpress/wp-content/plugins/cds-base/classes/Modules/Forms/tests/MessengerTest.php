<?php

use CDS\Modules\Forms\Messenger;


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
    $clientMock->shouldReceive('sendMail')->andThrow(new Exception('Bad template ID or something'));

    $messenger = mock(Messenger::class)->makePartial();
    $messenger->shouldReceive("getNotifyClient")->andReturn($clientMock);
 
    expect($messenger->sendMail('paul.com', 'message'))->toMatchArray(
        [
            'error' => 'Bad template ID or something',
            'error_message' => 'Error sending email'
        ]
    );
});