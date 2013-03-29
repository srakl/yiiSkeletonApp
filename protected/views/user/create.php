<?php
/* @var $this UserController */
/* @var $model User */

$this->pageTitle = app()->name . ' - Register';
$this->breadcrumbs = array(
    'Register',
);

$this->menu = array(
    array(
        'label' => 'List Users',
        'url' => array('index')
    )
);
?>

<h1>Register User</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>