<?php

$this->pageTitle = app()->name . ' - Forgot Password';
$this->breadcrumbs = array(
    'Login' => array('/site/login'),
    'Forgot Password',
);
?>

<h1>Forgot Password</h1>

<p>Please provide your email address in the field below to reset your password.</p>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'horizontal',
    'focus' => array($model, 'email'),
));
?>

<?php //echo $form->errorSummary($model); ?>

<fieldset>

    <?php echo $form->textFieldRow($model, 'email', array('autocomplete' => 'off', 'placeholder' => 'email address')); ?>

    <div class="control-group ">
        <?php echo CHtml::activeLabel($model, 'verify', array('required' => true, 'class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'verify', array('class' => 'input-small')); ?>
            <?php $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'imageOptions' => array('style' => 'vertical-align: top; margin-top: -10px; cursor: pointer;'))); ?>
            <?php echo $form->error($model, 'verify'); ?>
        </div>
    </div>

</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'icon' => 'icon-envelope', 'label'=>'Request Reset')); ?>
</div>

<?php $this->endWidget(); ?>
