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
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'user', 'label' => 'Login')); ?>
    <?php $this->widget('application.components.widgets.FBConnect'); ?>
    <?php $this->widget('application.components.widgets.GConnect'); ?>
</div>

<div id="processing" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="processingLabel" aria-hidden="true">
    <div class="modal-header">
        <h3 id="processingLabel"><i class="icon-spinner icon-spin"></i> Processing Request</h3>
    </div>
    <div class="modal-body">
        <p>One moment while we processes your request. This shouldn't take too long.</p>
    </div>
    <div class="modal-footer"></div>
</div>

<?php $this->endWidget(); ?>