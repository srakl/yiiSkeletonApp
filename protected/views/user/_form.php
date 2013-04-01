<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'register-form',
    'type' => 'horizontal',
    'enableAjaxValidation' => false,
));
?>

<p>Fields with <span class="required">*</span> are required.</p>

<?php //echo $form->errorSummary($model); ?>

<fieldset>

    <?php echo $form->textFieldRow($model, 'first_name', array('size' => 45, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'last_name', array('size' => 45, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'email', array('size' => 60, 'maxlength' => 63)); ?>

    <?php echo $form->textFieldRow($model, 'address', array('size' => 60, 'maxlength' => 511)); ?>

    <?php echo $form->textFieldRow($model, 'phone', array('size' => 12, 'maxlength' => 12)); ?>

    <?php if(!$model->isNewRecord): ?>
    
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Change Password',
            'type' => 'link',
            'icon' => 'key',
            'url' => array('/user/password/' . $model->id),
        ));
        ?>
    <?php else: ?>

        <?php echo $form->passwordFieldRow($model, 'password', array('size' => 60, 'maxlength' => 63)); ?>

        <?php echo $form->passwordFieldRow($model, 'pass2', array('size' => 60, 'maxlength' => 63)); ?>

        <div class="control-group ">
            <?php echo CHtml::activeLabel($model, 'verify', array('required' => true, 'class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, 'verify', array('class' => 'input-small')); ?>
                <?php $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'imageOptions' => array('style' => 'vertical-align: top; margin-top: -10px; cursor: pointer;'))); ?>
                <?php echo $form->error($model, 'verify'); ?>
            </div>
        </div>

    <?php endif; ?>

    <?php if(app()->user->isAdmin()): ?>

        <?php echo $form->checkBoxRow($model, 'admin'); ?>

        <?php if(app()->user->getUser()->id !== $model->id): ?>

            <?php echo $form->checkBoxRow($model, 'login_disabled'); ?>

        <?php endif; ?>

    <?php endif; ?>
    
</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label' => $model->isNewRecord ? 'Register' : 'Update')); ?>
</div>

<?php $this->endWidget(); ?>