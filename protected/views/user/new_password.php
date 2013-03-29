<?php

$this->pageTitle = app()->name . ' - Reset Password';
$this->breadcrumbs = array(
    'Login' => array('/site/login'),
    'Reset Password',
);
?>

<h1>Reset Password</h1>

<?php if (!$model->isNewRecord): ?>

    <p>Please provide your desired password in the fields below to complete the password reset.</p>

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'reset-form',
        'type' => 'horizontal',
        'focus' => array($model, 'pass1'),
    ));
    ?>

    <?php //echo $form->errorSummary($model); ?>
    
    <fieldset>

        <?php echo $form->passwordFieldRow($model, 'pass1', array('maxlength' => 63)); ?>

        <?php echo $form->passwordFieldRow($model, 'pass2', array('maxlength' => 63)); ?>
        
    </fieldset>
    
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Reset Password')); ?>
    </div>

    <?php $this->endWidget(); ?>

<?php else: ?>

    <p>Please check your email for instructions on resetting your account password. The email was sent to <code><?php echo $model->email_address; ?></code>.</p>

<?php endif; ?>