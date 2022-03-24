<?php

require_once('settings.php');

function toLocation($location){
    header('Location: '.Settings::webDomain.$location);
    die();
}

function getAbsoluteLocation($location){
    return Settings::webDomain.$location;
}