<?php

class App extends CApplicationComponent {
    
    public $enableJS = true;
    protected $_assetsUrl;
    
    public function init() {
        if (Yii::getPathOfAlias('app') === false)
            Yii::setPathOfAlias('app', realpath(dirname(__FILE__).'/..'));
        
        // Prevents the extension from registering scripts and publishing assets when ran from the command line.
        if (app() instanceof CConsoleApplication)
            return;
        
        if ($this->enableJS !== false)
            $this->registerCoreScripts();
        
        parent::init();
    }
    
    public function registerCoreScripts() {
        $this->registerAppJS(app()->clientScript->coreScriptPosition);
    }
    
    public function registerAppJS($position = CClientScript::POS_HEAD) {
        cs()->registerScriptFile($this->getAssetsUrl().'/js/app.js', $position);
        cs()->registerScriptFile($this->getAssetsUrl().'/js/jquery.notifyBar.js', $position);
    }
    
    protected function getAssetsUrl() {
        if (isset($this->_assetsUrl)) {
            return $this->_assetsUrl;
        } else {
            $assetsPath = Yii::getPathOfAlias('app.assets');
            $assetsUrl = app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            return $this->_assetsUrl = $assetsUrl;
        }
    }
}
