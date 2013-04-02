<?php
/* @var $this UserController */
/* @var $model User */

if(app()->user->isAdmin()){
    $pageName = 'Create User';
    $this->layout = 'column2';
    $this->menu = array(
        array('label'=>'List User', 'url'=>array('index')),
        array('label' => 'Create User', 'url' => array('register'), 'active' => true),
    );
} else {
    $pageName = 'Register User';
}

$this->pageTitle = app()->name . ' - ' . $pageName;
$this->breadcrumbs = array(
    $pageName,
);
?>

<h1><?php echo $pageName; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>