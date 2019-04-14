<?php

// takes parameter holding a $_GET key as a string,
// returns GET variable if GET variable isset
function get($key) {
    if(isset($_GET[$key])) {
        return $_GET[$key];
    }
    else {
        return '';
    }
}