Yii Framework Skeleton App
======================
This is a generic Yii Framework application with MySQL integration. I built this simple app as the starting point for many projects I have worked on in the past. This application allows for user registration, user sign-in and sign-out, Facebook sign-in, and password reset.

This skeleton application does not include the Yii Framework itself. Download it [here](https://github.com/yiisoft/yii/).

## Extensions
I've included some extensions that I pretty much always use. These include:
* Twitter Bootstrap ([Yii Bootstrap](http://www.cniska.net/yii-bootstrap/))
* Font Awesome
* LESS PHP Compiler
* Notify Bar ([GitHub](https://github.com/dknight/jQuery-Notify-bar))

## Install
To install this skeleton application:

1. Unpack everything to a web accessible directory.
2. Download the [Yii Framework](https://github.com/yiisoft/yii/) and extract just the core framework to the `/yii/` directory.
3. Create a MySQL schema (the app uses `testdrive` as default) and extract the `/protected/data/testdrive.sql` dump to that schema.
4. Modify the `/protected/config/main.php` file to reflect your database credentials.

## Features
I tried to make this as generic of a skeleton app as possible so it can be a good starting point for almost any project possible. These are some of the features it contains right out of the box.

* Twitter Bootstrap themed. Adheres to the design standards of the popular front-end framework.
* Font Awesome integration. I find myself using this everywhere, so it seemed fitting for a skeleton app.
* Database driven user management:
    1. User registration with email verification. Users receive an email after registration and must click the link to be fully registered.
    2. User forgot password reset. Users can request a password reset and will receive an email with a link to complete the process.
    3. User login and Facebook SSO. Once registered, users can log in with username/password or with Facebook single sign-on.
    4. Multi user level. Three user levels of Guest, User or Admin.
        * Admin can disable login for users (individually).
        * Admin can change user passwords without needing to know their current password.
* LESS CSS ready. Currently set to force compile `/css/system.less` every time. Expand and alter this in the `/protected/config/main.php` file.
* Notify Bar jQuery plugin. I find this more appealing than vanilla JavaScript alerts. Relies on some css in the `/css/system.less` file.