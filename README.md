Yii Framework Skeleton App
======================
This is a generic Yii Framework application with MySQL integration. I built this simple app as the starting point for many projects I have worked on in the past. This application allows for user registration, user sign-in and sign-out, Facebook sign-in, and password reset.

This skeleton application does not include the Yii Framework itself. Download it [here](https://github.com/yiisoft/yii/).

[Click Here](http://www.travisstroud.co.uk/yiiSkeletonApp/) to see the demo.

## Extensions
I've included some extensions that I pretty much always use. These include:
* Twitter (Yii) Bootstrap ([Bitbucket](https://bitbucket.org/Crisu83/yii-bootstrap))
* Font Awesome ([GitHub](https://github.com/FortAwesome/Font-Awesome))
* Notify Bar ([GitHub](https://github.com/dknight/jQuery-Notify-bar))
* Randomness ([GitHub](https://github.com/tom--/Randomness))
* PHPMailer (YiiMailer) ([GitHub](https://github.com/vernes/YiiMailer))
* Facebook PHP SDK ([GitHub](https://github.com/splashlab/yii-facebook-opengraph))
* [Google PHP Client Library](https://code.google.com/p/google-api-php-client/)
* [Windows Live JS SDK](http://msdn.microsoft.com/en-us/library/live/hh243643.aspx)
* LESS CSS - PHP Compiler

## Install
To install this skeleton application:

1. Unpack everything to a web accessible directory.
2. Download the [Yii Framework](https://github.com/yiisoft/yii/) and extract just the core framework to the `/yii/` directory.
3. Create a MySQL schema (the app uses `testdrive` as default) and extract the `/protected/data/testdrive.sql` dump to that schema.
4. Modify the `/protected/config/main.php` file to reflect your database credentials.
5. Customize to your needs and desires.

## Important
My skeleton application relies on PHPs APC extension for session and caching. This may not be enabled on your web host. If that's the case, I recommend falling back on the CFileCache provided in the Yii Framework. These settings are in the `/protected/config/main.php` file.