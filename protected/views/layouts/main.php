<?php
/* @var $this Controller */

if(app()->user->hasFlash('success')) {
    cs()->registerScript('alert','showSuccess(\''.app()->user->getFlash('success').'\');');
} elseif(app()->user->hasFlash('error')) {
    cs()->registerScript('alert','showError(\''.app()->user->getFlash('error').'\');');
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <title><?php echo h($this->pageTitle); ?></title>
        <?php app()->bootstrap->register(true); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo app()->request->baseUrl; ?>/css/system.css" media="screen, projection" />
    </head>

    <body>
        
        <div id="fb-root"></div>
        
        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'brand' => h(app()->name),
            'brandUrl' => bu(),
            'collapse' => true,
            'items'=>array(
                array(
                    'class'=>'bootstrap.widgets.TbMenu',
                    'items'=>array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'Profile', 'url' => array('/user/update', 'id' => app()->user->id), 'visible' => !app()->user->isGuest()),
                        array('label' => 'Users', 'url' => array('/user/index'), 'visible' => app()->user->isAdmin()),
                    ),
                ),
                app()->user->isGuest?
                    array(
                        'class' => 'bootstrap.widgets.TbButton',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'label'=>'Login',
                        'type'=>'success',
                        'size'=>'normal',
                        'url'=>array('/site/login'),
                    )
                :
                    array(
                        'class' => 'bootstrap.widgets.TbButton',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'label'=>'Logout',
                        'type'=>'warning',
                        'size'=>'normal',
                        'url'=>array('/site/logout'),
                    )
                ,
                (app()->user->isGuest?array(
                    'class' => 'bootstrap.widgets.TbButton',
                    'htmlOptions' => array('class' => 'pull-right', 'style' => 'margin-right: 5px'),
                    'label'=>'Register',
                    'type'=>'info',
                    'size'=>'normal',
                    'url'=>array('/user/create'),
                ):''),
            ),
        ));
        ?>

        <div id="page">

            <div class="container main-holder">

                <?php
                if(isset($this->breadcrumbs)){
                    $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    ));
                }
                ?>

                <?php if(app()->user->hasFlash('info')) {
                    $this->widget('bootstrap.widgets.TbAlert', array(
                        'block' => true,
                        'fade' => true,
                        'closeText' => '&times;',
                    ));
                } ?>

                <?php echo $content; ?>

            </div>
            
            <footer class="footer" id="footer">
                <div class="container">
                    Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                    All Rights Reserved.<br/>
                    <?php echo Yii::powered(); ?>
                </div>
            </footer>

        </div>

    </body>
</html>
