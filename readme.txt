Contents of this folder:

Oblig3
    /css
    --- styles.css                  (All styles for the application)
    /img
    --- images                      (All images for the application)

    // Misc files
    setup.php                       (Creates database)
    connect.php                     (Connection to database)
    functions.php                   (General functions)
    header.php                      (Menu)
    --- logout.php                  (PHP for destroying the current session)



    //Pages and related includes/backend
    index.php                       (Main page, see all items and sort items)
    --- index_backend.php           (Backend for index.php)
    login.php                       (Loging in user using email and password)
    --- login_backend.php           (Backend for login)
    item.php                        (Page to see one item)
    --- itemMsg.php                 (The part of item.php related to starting conversation)
    --- --- itemMsg_backend.php     (Backend for starting conversation at item.php)
    register.php                    (Page for registering a new user)
    --- register_backend.php        (Backend for registering new user)
    login.php                       (Loging in user using email and password)
    profile.php                     (Page to see users own profile and send messages)
    --- profile_backend.php         (Backend for profile)
    --- messages.php                (The part of profile.php related to sending messages)
    --- --- messages_backend.php    (Backend for sending messages)
    my_items.php                    (Page to see users own items, post new and delete old.)
    --- my_items_backend.php        (Backend for my items)





How to get started:
1. The files are php files, so this application must use localhost.
--- Move folder to localhost, if you use XAMPP move this to the htdocs-folder.

2. The application need a database, we have made one with some dummy-data.
--- Change connection details in the beginning of connect.php as needed.
--- Run the setupfile by using URL  localhost/oblig3/setup.php

3. You must access the application
--- Enter URL localhost/oblig3/

4. You can now browse the application
--- Feel free to register, or log in using the emails below.

Different emails for logging in:
gunnar_grefsen@gmail.com
ole_kristiansen@gmail.com
bjarne_bakken@gmail.com
helene_svendsen@gmail.com
chistoffer_horvei@gmail.com

Password for all users:
Password123



NOTES:
Only the first five counties actually has items in them,
so trying to filter on any of the other counties will provide nothing.

We choose to have all the messages on the profile page,
on the item-pages you can only start a new conversation.

Folder structure could have been better
using a folder for only backend, and a folder for only includes.
