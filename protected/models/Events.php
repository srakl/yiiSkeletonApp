<?php

/**
 * This is the model class for table "events".
 *
 * The followings are the available columns in table 'events':
 * @property string $id
 * @property string $title
 * @property integer $all_day
 * @property string $start
 * @property string $end
 * @property string $url
 * @property string $class_name
 * @property integer $editable
 */
class Events extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Events the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'events';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, all_day, start, url, class_name, editable', 'required'),
			array('all_day, editable', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>64),
			array('url', 'length', 'max'=>255),
			array('class_name', 'length', 'max'=>32),
			array('end', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, all_day, start, end, url, class_name, editable', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'all_day' => 'All Day',
			'start' => 'Start',
			'end' => 'End',
			'url' => 'Url',
			'class_name' => 'Class Name',
			'editable' => 'Editable',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('all_day',$this->all_day);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('class_name',$this->class_name,true);
		$criteria->compare('editable',$this->editable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}