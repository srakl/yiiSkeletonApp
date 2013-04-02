<?php
/* @var $this UserController */
/* @var $model User */

if($model->id===app()->user->id){
    $breadcrumbs = array(
        'Profile' => array('/user/update', 'id' => app()->user->id),
        'Change Password'
    );
} else {
    $breadcrumbs = array(
        'Users' => array('/user/index'),
        $model->email => array('/user/update', 'id' => $model->id),
        'Change Password'
    );
}

$this->layout = app()->user->isAdmin()?'column2':'';
$this->pageTitle = app()->name . ' - Change Password';
$this->breadcrumbs = $breadcrumbs;

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('register')),
);
?>

<h1>Change User <?php echo $model->id; ?> Password</h1>

<p>Fields with <span class="required">*</span> are required.</p>

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'changePass-form',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php //echo $form->errorSummary($model); ?>

    <fieldset>

        <?php if(!app()->user->isAdmin()): ?>

            <?php echo $form->passwordFieldRow($model, 'old_password', array('size' => 60, 'maxlength' => 63)); ?>

        <?php endif; ?>

        <?php echo $form->passwordFieldRow($model, 'pass1', array('size' => 60, 'maxlength' => 63)); ?>

        <?php echo $form->passwordFieldRow($model, 'pass2', array('size' => 60, 'maxlength' => 63)); ?>

    </fieldset>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Change Password')); ?>
    </div>
    
    <?php $this->endWidget(); ?>