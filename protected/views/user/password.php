<?php
/* @var $this UserController */
/* @var $model User */
?>

<h1>Change User <?php echo $model->id; ?> Password</h1>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php if(!app()->user->isAdmin()): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'old_password'); ?>
        <?php echo $form->passwordField($model, 'old_password', array('size' => 60, 'maxlength' => 63)); ?>
        <?php echo $form->error($model, 'old_password'); ?>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'pass1'); ?>
        <?php echo $form->passwordField($model, 'pass1', array('size' => 60, 'maxlength' => 63)); ?>
        <?php echo $form->error($model, 'pass1'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'pass2'); ?>
        <?php echo $form->passwordField($model, 'pass2', array('size' => 60, 'maxlength' => 63)); ?>
        <?php echo $form->error($model, 'pass2'); ?>
    </div>
    
    <div class="row buttons">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label' => 'Change Password',
        )); ?>
    </div>
    
    <?php $this->endWidget(); ?>
</div>