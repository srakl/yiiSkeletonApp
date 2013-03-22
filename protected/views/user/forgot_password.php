<?php
$this->breadcrumbs = array(
    'Login' => array('/site/login'),
    'Forgot Password',
);
?>

<h1>Forgot Password</h1>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'htmlOptions' => array('class' => 'reset'),
        'focus' => array($model, 'email'),
            ));
    ?>
    <?php if ($model->isNewRecord): ?>                    
        <p>Please provide your email address in the field below to reset your password.</p>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <?php echo $form->textField($model, 'email', array('autocomplete' => 'off', 'placeholder' => 'email address')); ?>
        </div>

        <?php echo CHtml::hiddenField('ajax', '0'); ?>

        <div class="row buttons">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'label' => 'Request Reset',
                'icon' => 'icon-envelope',
            ));
            ?>
        </div>

    <?php else: ?>
        <p>Password reset request is instantiated. In the future, this will send an email to the users email address with the reset link. For now, <a href="<?php echo url('user/newPassword') . '?req=' . $hash; ?>">click here</a>. This link is valid for 24 hours only.</p>
    <?php
    endif;
    $this->endWidget();
    ?>
</div>