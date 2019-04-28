<?php

// include config file with db connection
include('config.php');

// destroy current session
session_destroy();

// redirect to login page
header('location: login.php');