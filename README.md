Yii Framework Skeleton App
======================

This is a generic Yii Framework application with MySQL integration. I built this simple app as the starting point for
many projects I have worked on in the past. This application allows for user registration, user sign-in and sign-out,
facebook sign-in, and password reset.

This skeleton application does not include the Yii Framework itself.

## Extensions

I've included some extensions that I pretty much always use. These include:
* Twitter Bootstrap
* FontAwesome
* LESS PHP Compiler

## Install
To install this skeleton application:

1. Extract everything to your web root.
2. Download the [Yii Framework](https://github.com/yiisoft/yii/tree/master/framework/) and extract to the /yii/ directory.
3. Create a MySQL schema (I name mine 'testdrive') and extract the /protected/data/testdrive.sql dump.
4. Modify the /protected/config/main.php file to reflect your database credentials.
