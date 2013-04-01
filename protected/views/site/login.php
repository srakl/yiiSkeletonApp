<?php
$this->pageTitle = app()->name . ' - Login';
$this->breadcrumbs = array('Login');

cs()->registerScript('login-submit',
    "function submitLoginForm(){
        $.ajax({
            data: $('#login-form').serialize(0),
            type: 'post',
            url: '" . url('/site/login') . "',
            completedText: 'redirecting...',
            beforeSend: function(){ $('#submit-signin').button('loading') },
            success: function(response){
                if (response == 'bad'){
                    showError('Sign in failed. Please try again.');
                    $('#submit-signin').button('reset');
                } else {
                    $(location).attr('href', response);
                }
            }
        });
    }
    $('#submit-signin').click(function(){submitLoginForm()});
    $('#login-form input').keypress(function(e){
        if(e.which == 13){
            submitLoginForm()
        }
    });", CClientScript::POS_READY);
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
    <?php echo CHtml::hiddenField('ajax', '1'); ?>

</fieldset>

<div class="control-group"><a href="<?php echo url('user/forgotPassword'); ?>">Forgot password?</a></div>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => '',
        'icon' => 'user',
        'htmlOptions' => array(
            'id' => 'submit-signin',
        ),
        'loadingText' => '<i class="icon-spinner icon-spin"></i> Processing...',
        'label' => 'Login',
    ));
    ?>
    
    <?php $this->widget('application.components.widgets.FBConnect'); ?>
</div>

<?php $this->endWidget(); ?>