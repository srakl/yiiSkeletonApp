<?php
/* @var $this UserController */
/* @var $model User */

if(app()->user->hasFlash('success')) {
    cs()->registerScript('updates','alert(\''.app()->user->getFlash('success').'\');');
}

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	//array('label'=>'View User', 'url'=>array('view', 'id'=>$model->user_id)),
	//array('label'=>'Manage User', 'url'=>array('admin')),
);

if(app()->user->hasFlash('success')) {
    cs()->registerScript('updates','alert(\''.app()->user->getFlash('success').'\');');
}
?>

<h1>Update User <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>