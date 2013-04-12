<?php

$this->pageTitle = app()->name . ' - Login';
$this->breadcrumbs = array('Login');

?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'login-form',
    'type' => 'horizontal',
));
?>

<fieldset>

    <?php echo $form->textFieldRow($model, 'username'); ?>
    <?php echo $form->passwordFieldRow($model, 'password'); ?>
    <?php echo $form->checkboxRow($model, 'rememberMe'); ?>

</fieldset>

<div class="control-group form-link"><a href="<?php echo url('user/forgotPassword'); ?>">Forgot password?</a></div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'icon' => 'user', 'label'=>'Login')); ?>
    
    <?php $this->widget('application.components.widgets.FBConnect'); ?>
    
    <?php $this->widget('application.components.widgets.GConnect'); ?>
</div>

<?php $this->endWidget(); ?>