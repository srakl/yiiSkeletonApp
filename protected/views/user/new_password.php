<h1>Set New Password</h1>

<?php if (!$model->isNewRecord): ?>
    <p>Please provide your desired password in the fields below to complete the password reset.</p>
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-form',
            'htmlOptions' => array('class' => 'reset'),
            'enableAjaxValidation' => false,
            'focus' => array($model, 'pass1'),
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'pass1'); ?>
            <?php echo $form->passwordField($model, 'pass1', array('maxlength' => 63)); ?>
            <?php echo $form->error($model, 'pass1'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'pass2'); ?>
            <?php echo $form->passwordField($model, 'pass2', array('maxlength' => 63)); ?>
            <?php echo $form->error($model, 'pass2'); ?>
        </div>

        <div class="row buttons">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'label' => 'Reset Password',
            ));
            ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
<?php else: ?>
    <p>Please check your email for instructions on resetting your account password. The email was sent to <code><?php echo $model->email_address; ?></code>.</p>
<?php endif; ?>