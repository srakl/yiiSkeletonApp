<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo app()->request->baseUrl; ?>/css/system.css" media="screen, projection" />

        <title><?php echo h($this->pageTitle); ?></title>
        <?php app()->bootstrap->register(); ?>
    </head>

    <body>
        
        <div id="fb-root"></div>
        
        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'brand' => h(app()->name),
            'brandUrl' => '#',
            'collapse' => true,
            'items'=>array(
                array(
                    'class'=>'bootstrap.widgets.TbMenu',
                    'items'=>array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'Profile', 'url' => array('/user/update', 'id' => app()->user->id), 'visible' => !app()->user->isGuest()),
                        array('label' => 'Users', 'url' => array('/user/index'), 'visible' => app()->user->isAdmin()),
                        array('label' => 'Register', 'url' => array('/user/register'), 'visible' => app()->user->isGuest),
                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => app()->user->isGuest),
                        array('label' => 'Logout (' . app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !app()->user->isGuest)
                    ),
                ),
            ),
            ));
        ?>

        <div class="container" id="page">

            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                All Rights Reserved.<br/>
                <?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
