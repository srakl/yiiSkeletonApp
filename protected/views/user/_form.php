<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'first_name'); ?>
        <?php echo $form->textField($model, 'first_name', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'last_name'); ?>
        <?php echo $form->textField($model, 'last_name', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 63)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'address'); ?>
        <?php echo $form->textField($model, 'address', array('size' => 60, 'maxlength' => 511)); ?>
        <?php echo $form->error($model, 'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone', array('size' => 12, 'maxlength' => 12)); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <?php if (!$model->isNewRecord): ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Change Password',
            'type' => 'link',
            'url' => array('/user/password/' . $model->id),
        ));
        ?>
    <?php else: ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array('size' => 60, 'maxlength' => 63)); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'pass2'); ?>
            <?php echo $form->passwordField($model, 'pass2', array('size' => 60, 'maxlength' => 63)); ?>
            <?php echo $form->error($model, 'pass2'); ?>
        </div>
    <?php endif; ?>

    <?php if (app()->user->isAdmin()): ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'admin'); ?>
            <?php echo $form->checkBox($model, 'admin'); ?>
            <?php echo $form->error($model, 'admin'); ?>
        </div>

        <?php if (app()->user->getUser()->id !== $model->id): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'login_disabled'); ?>
                <?php echo $form->checkBox($model, 'login_disabled'); ?>
                <?php echo $form->error($model, 'login_disabled'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row buttons">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label' => $model->isNewRecord ? 'Create' : 'Update',
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->