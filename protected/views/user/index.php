<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Users',
);

$this->menu = array(
    array('label' => 'Create User', 'url' => array('create')),
        //array('label'=>'Manage User', 'url'=>array('admin')),
);

$columns = array(
    array('name' => 'id', 'header' => 'ID'),
    array('name' => 'first_name', 'header' => 'Name', 'type' => 'raw', 'value' => 'User::model()->findByPk($data->id)->getFullName()'),
    array('name' => 'email', 'header' => 'Email'),
    array('name' => 'phone', 'header' => 'Phone', 'type' => 'raw', 'value' => 'Shared::formatPhone($data->phone)'),
    array('name' => 'last_login', 'header' => 'Last Login', 'type' => 'raw', 'value' => 'Shared::formatDateShort($data->last_login)'),
);

if (app()->user->isAdmin()) {
    $columns[] = array(
        'class' => 'CButtonColumn',
        'template' => '{update} {delete}',
    );
}

$dataArray = array(
    'dataProvider' => $dataProvider,
    'columns' => $columns,
);
?>

<h1>Users</h1>

<?php $this->widget('zii.widgets.grid.CGridView', $dataArray); ?>
