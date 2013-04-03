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

<?php echo (app()->user->isAdmin()&&$model->isNewRecord?'<div class="alert alert-info">
        <h4>Notice</h4>
        A random password will be generated and sent to the email specified along with an account activation link.
    </div>':''); ?>

<?php //echo $form->errorSummary($model); ?>

<fieldset>

    <?php echo $form->textFieldRow($model, 'first_name', array('size' => 45, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'last_name', array('size' => 45, 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'email', array('size' => 60, 'maxlength' => 63)); ?>

    <?php echo $form->textFieldRow($model, 'address', array('size' => 60, 'maxlength' => 511)); ?>

    <?php echo $form->textFieldRow($model, 'phone', array('size' => 12, 'maxlength' => 12)); ?>

    <?php if(!$model->isNewRecord): // Edit a record (password fields are not shown here, instead a link) ?>
    
        <div class="control-group form-link">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Change Password',
                'type' => 'link',
                'icon' => 'key',
                'url' => array('/user/password/' . $model->id),
            ));
            ?>
        </div>

    <?php else: // Create new record ?>

    <?php if(!app()->user->isAdmin()): // Create new record as non admin user (password and captcha required) ?>
    
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

    <?php endif; ?>

    <?php if(app()->user->isAdmin()): // Create OR Edit a record as admin (extra options availible) ?>

        <?php if(app()->user->id !== $model->id): // admin cannot take privileges from themselves ?>

            <?php echo $form->checkBoxRow($model, 'admin'); ?>

            <?php echo $form->checkBoxRow($model, 'login_disabled'); ?>

        <?php endif; ?>
    
    <?php endif; ?>
    
</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label' => $model->isNewRecord ? app()->user->isAdmin() ? 'Create User' : 'Register' : 'Update')); ?>
</div>

<?php $this->endWidget(); ?>