<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $phone
 * @property string $password
 * @property string $activate
 * @property string $last_login
 * @property string $password_reset
 * @property integer $admin
 * @property integer $email_verified
 * @property integer $login_disabled
 */
class User extends CActiveRecord {

    /**
     * Used for password change and registration
     */
    public $pass1;
    public $pass2;
    public $old_password;
    public $verify;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email', 'required'),
            array('email', 'exist', 'on' => 'forgotPassword'),
            array('old_password, pass1, pass2', 'required', 'on' => 'resetPass, changePassword'),
            array('old_password', 'application.components.validate.ECurrentPassword', 'on' => 'changePassword'),
            array('pass2', 'compare', 'compareAttribute' => 'pass1', 'on' => 'resetPass, changePassword'),
            array('pass1, pass2', 'application.components.validate.EPasswordStrength', 'on' => 'resetPass, changePassword'),
            array('password, pass2', 'required', 'on' => 'register'),
            array('pass2', 'compare', 'compareAttribute' => 'password', 'on' => 'register'),
            array('verify', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register, forgotPassword'),
            array('admin, email_verified, login_disabled', 'numerical', 'integerOnly' => true),
            array('email, password', 'length', 'max' => 63),
            array('address', 'length', 'max' => 511),
            array('phone', 'length', 'max' => 12),
            array('first_name, last_name', 'length', 'max' => 45),
            array('password_reset', 'numerical', 'on' => 'passwordReset', 'integerOnly' => true),
            array('password, pass2', 'application.components.validate.EPasswordStrength', 'on' => 'register'),
            array('email', 'email', 'on' => 'update, create, register'),
            array('first_name, last_name', 'application.components.validate.ENameValidator'),
            array('email', 'unique', 'on' => 'create, register'),
            array('email, phone', 'default', 'setOnEmpty' => true), // make empty values stored as NULL
            array('last_login', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, email, first_name, last_name, phone_number, super_admin, email_verified, login_disabled', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'phone' => 'Phone',
            'password' => 'Password',
            'activate' => 'Activate',
            'last_login' => 'Last Login',
            'password_reset' => 'Password Reset',
            'admin' => 'Admin',
            'email_verified' => 'Email Verified',
            'login_disabled' => 'Login Disabled',
            'pass1' => 'New Password',
            'pass2' => 'Confirm Password',
            'old_password' => 'Current Password',
            'verify' => 'Validate',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('email', $this->email, true);

        return $criteria;
    }

    public function isAdmin() {
        return $this->admin;
    }

    public static function findByEmail($email) {
        return self::model()->findByAttributes(array('email' => $email));
    }

    /**
     * Create new user object, do not save it to database yet
     * all permission should be valid now
     * @param type $input
     */
    public static function create($input) {

        if (isset($input['email'])) {
            $model = self::findByEmail($input['email']);
        } else {
            $model = new User;
        }
        $password = isset($input['password']) ? $input['password'] : '';
        if (null == $model) {
            // used by admin for creating a user (be sure to send the random password to the user...)
            $model = new User;
            $model->attributes = $input;

            if (strlen($model->password) == 0) {
                // create new one if nothing is provided (only applies to admins creating new users)
                $password = Shared::generateMnemonicPassword(8);
                $model->password = crypt($password, Randomness::blowfishSalt());
                $model->pass1 = $password;
            } else {
                $model->password = crypt($password, Randomness::blowfishSalt());
            }
            $model->email = strtolower($model->email);
        }
        return $model;
    }

    /**
     * Cache generic user object used from WebUser. We want to cache the menus and
     * roles.
     * @param type $pk
     */
    public static function findInCache($pk) {
        $user = app()->cache->get('user-' . $pk);
        if ($user === false) {
            //Shared::debug("save user to cache");
            $user = self::model()->findByPk($pk);
            if ($user == null)
                return null;

            // preload
            //$user->getUserMenu();
            // and save
            $user->saveToCache();
            return $user;
        } else {
            //Shared::debug("found in cache");
            // found, but we still have to construct the object
            $model = new User;
            $model->loadFromCache($user);
            $model->id = $pk; // PK is not part of attributes
            return $model;
        }
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Generate an encrypted hash for the user
     * @param type $string
     * @param type $hash
     * @return type
     */
    public static function encrypt($string = "", $hash = "md5") {
        if ($hash == "md5")
            return md5($string);
        if ($hash == "sha1")
            return sha1($string);
        else
            return crypt($hash, $string);
    }

    /**
     * Function called before update. 
     * @param type $password
     */
    public function setPassword($password) {
        if (strlen($password) > 0) {
            // change password
            $this->password = crypt($password, Randomness::blowfishSalt());
        } else {
            // keep the original one
            $this->restoreAttribute('password');
        }
    }

    /**
     * Save User menu and attributes to cache as an associative array
     */
    public function saveToCache() {
        $attributes = array(
            'attributes' => $this->attributes,
        );
        app()->cache->set('user-' . $this->id, $attributes, 1200);
    }

}