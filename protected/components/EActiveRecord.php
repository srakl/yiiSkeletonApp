<?php

/**
 * This extension optimizes UPDATE operations and helps caching the whole objects
 * It stores original state of each object and save only modified fields. This
 * behavior might be a slight overhead.
 *
 * @author one
 */
class EActiveRecord extends CActiveRecord {

    /** Values loaded from database */
    protected $_oldValues;

    /** Enable cache in each model to save the model
     * to cache on insert / update 
     */
    public $cachable = false;

    /**
     * ID is always dependent on data table
     * @return integer
     */
    public function getId() {
        $attr = $this->tableName() . '_id';
        if (isset($this->attributes[$attr])) {
            return $this->attributes[$attr];
        }
        return 0;
    }
    
    /**
     * Creates new model of given class with given attributes. The model is
     * not saved to database yet;
     */
    public static function createModel($className, $attributes = array()) {
        $model = new $className;
        $model->attributes = $attributes;
        return $model;
    }

    /**
     * Save original state of object to temporary storage
     */
    public function afterFind() {
        parent::afterFind();
        $this->_oldValues = $this->attributes;
    }

    /**
     * Has there been a change to this object we have to save back to database? 
     */
    public function isDirty() {
        $attrCount = count($this->attributes);
        // cannot say
        if (count($this->_oldValues) != $attrCount)
            return true;
        if ($this->isNewRecord)
            return true;

        foreach ($this->attributes as $attr => $val) {
            if ($this->_oldValues[$attr] != $val)
                return true;
        }
        return false;
    }
    
    public function isDirtyAttribute($attribute){
        if (isset($this->attributes[$attribute])){
            if ($this->_oldValues[$attribute] !== $this->attributes[$attribute]) return true;
        }
        return false;
    }

    /**
     * Return an array of all the modified attributes
     * @return type
     */
    public function getDirtyAttributes() {
        $attributes = array();
        foreach ($this->attributes as $attr => $val) {
            if ($this->_oldValues[$attr] != $val) {
                $attributes[] = $attr;
            }
        }
        return $attributes;
    }

    /**
     * Returns value stored in model loaded from database
     */
    public function getOriginalAttribute($attribute) {
        if (isset($this->_oldValues[$attribute]))
            return $this->_oldValues[$attribute];
        return null;
    }

    /**
     * Set the original state of attribute
     * @param String $attribute
     */
    public function restoreAttribute($attribute) {
        if (isset($this->_oldValues[$attribute]))
            $this->setAttribute($attribute, $this->_oldValues[$attribute]);
    }

    /**
     * Overwritten function saves only modified attributes
     * @param type $runValidation
     * @param type $attributes
     * @return boolean 
     */
    public function save($runValidation = true, $attributes = null) {
        if (!$runValidation || $this->validate($attributes)) {
            if ($this->getIsNewRecord()) {
                if ($this->insert($attributes)){
                    if ($this->cachable) {
                        Yii::app()->cache->set(tableName() . '-' . $this->getId(), $this, 300);
                    }
                    return true;
                }
                return false;
            }
            if ($attributes != null) {
                if ($this->update($attributes)) {
                    if ($this->cachable) {
                        Yii::app()->cache->set(tableName() . '-' . $this->getId(), $this, 300);
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                // only dirty ones
                $dirty = $this->getDirtyAttributes();
                //Shared::debug($dirty);
                if (!empty($dirty)) {
                    if ($this->update($dirty)) {
                        if ($this->cachable) {
                            Yii::app()->cache->set($this->tableName() . '-' . $this->getId(), $this, 300);
                        }
                        return true;
                    } else {
                        return false;
                    }
                }
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Return an instance of ActiveDataProvider
     * http://www.yiiframework.com/wiki/173/an-easy-way-to-use-escopes-and-cactivedataprovider/
     * This might not work since class is AOActiveRecord
     * @ Param Criteria $ CDbCriteria 
     * @ Return CActiveDataProvider 
     */
    /*public function getDataProvider($criteria = null, $pagination = null) {
        if ((is_array($criteria)) || ($criteria instanceof CDbCriteria))
            $this->getDbCriteria()->mergeWith($criteria);
        $pagination = CMap:: mergeArray(array('pageSize' => 10), (array) $pagination);
        return new CActiveDataProvider(__CLASS__, array(
                    'criteria' => $this->getDbCriteria(),
                    'pagination' => $pagination
                ));
    }*/

    /**
     * Check user access to this resoure. Function compares vendor_id
     * in this object (if found) to member vendor id
     * @return boolean 
     */
    public function hasVendorAccess() {
        if (isset($this->vendor_id)) {
            if (app()->user->isAdmin()) return true;
            $user = app()->user->getUser();
            // we don't want to authorize guests (TODO: in some cases guests might have read permission)
            if ($user === false)
                return false;
            if ($this->vendor_id === app()->user->getActiveVendor()) {
                return true;
            }
            // ok, it is not the active one, so let us check the whole list
            $all = $user->getVendorIds();
            if (in_array($this->vendor_id, $all)) {
                return true;
            }
        }
        return false;
    }
    
    

}

?>