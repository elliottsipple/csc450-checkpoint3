# CSC-450 Car Company Database User Interfaces

This is the final project of CSC-450, developed by Group 6 (Elliott Sipple, Calvin Murphy, and Ethan Ware).

## Description

In this repository, you will find a classes directory with a User class, a css directory with a universal stylesheet, a fonts
directory with custom font files, and a sql file with commonly used sql queries. In the root directory, there are several PHP
files for the different webpages and components of the application.

### PHP Pages

The config file is used by several other webpages, as it includes the Oracle database connection and user setup. The functions file includes a get function for retrieving parameters from the URL. The login file includes a form to log a user in and redirects depending on the username provided. The logout file simply destroys a user's session and redirects to the login page. The __producer__ (Production office), __dealer__, __marketer__ (Marketing office), and __customer__ pages are individualized for the needs of each user. You can log in and test the pages for each user using the above usernames (in bold) and the password '**password**'.

### Setup

Simply drag and drop all files to the root directory of __**username@csweb.hh.nku.edu**__ using FileZilla or Cyberduck, logging in with your NKU username and password. Make sure you are on the NKU Encrypted network or are connected via VPN.

### Branch Naming Scheme

We have a master branch and feature branches for small additions to the project. To create a new feature branch, run `git checkout -b feature/featureName` after performing a `git pull` on the *master* branch.