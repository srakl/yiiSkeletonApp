<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = 'column2';
$this->pageTitle = app()->name . ' - Users';
$this->breadcrumbs = array(
    'Users',
);

$this->menu = array(
    array('label'=>'List User', 'url'=>array('index'), 'active' => true),
    array('label' => 'Create User', 'url' => array('register')),
);

$columns = array(
    array('name' => 'id', 'header' => 'ID'),
    array('name' => 'first_name', 'header' => 'Name', 'type' => 'raw', 'value' => 'User::model()->findByPk($data->id)->getFullName()'),
    array('name' => 'email', 'header' => 'Email'),
    array('name' => 'phone', 'header' => 'Phone', 'type' => 'raw', 'value' => 'Shared::formatPhone($data->phone)'),
    array('name' => 'last_login', 'header' => 'Last Login', 'type' => 'raw', 'value' => 'Shared::formatShortUSDate($data->last_login)'),
);

if (app()->user->isAdmin()) {
    $columns[] = array(
        'class' => 'CButtonColumn',
        'template' => '{update} {delete}',
    );
}

$dataArray = array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $columns,
);
?>

<h1>Users</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', $dataArray); ?>
