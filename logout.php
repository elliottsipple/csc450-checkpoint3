<?php

// include config file
include('config.php');

// include functions file
include('functions.php');

// destroy current session
session_destroy();

// redirect to login page
header('location: login.php');