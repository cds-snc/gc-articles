<?php

use CDS\Utils;

test("adds https", function () {
    $url = Utils::addHttp("canada.ca");
    expect($url)->toEqual("https://canada.ca");
});

test("doesn't add https if it exist already", function () {
    $url = Utils::addHttp("https://canada.ca");
    expect($url)->toEqual("https://canada.ca");
});

test("adds http", function () {
    $url = Utils::addHttp("canada.ca", "http");
    expect($url)->toEqual("http://canada.ca");
});

test("doesn't add http if it exist already", function () {
    $url = Utils::addHttp("http://canada.ca", "http");
    expect($url)->toEqual("http://canada.ca");
});


test("adds https when passing //", function () {
    $url = Utils::addHttp("//canada.ca");
    expect($url)->toEqual("https://canada.ca");
});

test("don't add : or // for localhost", function () {
    $url = Utils::addHttp("localhost", "");
    expect($url)->toEqual("localhost");
});

test("127.0.0.1", function () {
    $url = Utils::addHttp("http://127.0.0.1", "");
    expect($url)->toEqual("http://127.0.0.1");
});

test("removes trailing /", function () {
    $url = Utils::addHttp("https://canada.ca/");
    expect($url)->toEqual("https://canada.ca");
});
