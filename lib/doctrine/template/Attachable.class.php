<?php

class Attachable extends Doctrine_Template {
  
  protected $_options = array();
  protected $_plugin;

  public function __construct(array $options = array())
  {
    parent::__construct($options);
  }

  public function setTableDefinition()
  {
    $this->hasColumn('filename', 'string', '255', array(
      'default' => null
    ));

    $this->addListener(new AttachableListener());
  }

  public function attach($file) {
    if (!$file) return;

    $namespace = $this->getNamespace();
    if (!file_exists(sfConfig::get('sf_upload_dir').'/'.$namespace)) {
      mkdir(sfConfig::get('sf_upload_dir').'/'.$namespace);
    }
    if ($this->getFile()) unlink($this->getFile());
   
    $path = sfConfig::get('sf_upload_dir').'/'.$namespace.'/'.$this->getInvoker()->getOid().'-'.$file->getOriginalName();
    $this->getInvoker()->set('filename', $file->getOriginalName());
    $file->save($path);
    return $this->getInvoker();
  }

  public function getFileUrl() {
    $filename = $this->getInvoker()->get('filename');
    if (!$filename) return false;
    $url = '/'.str_replace(sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR,'',sfConfig::get('sf_upload_dir')).'/'.$this->getNamespace().'/'.$this->getInvoker()->getOid().'-'.$filename;
    return $url;
  }

  public function getFile() {
    $filename = $this->getInvoker()->get('filename');
    if (!$filename) return false;
    $path = sfConfig::get('sf_upload_dir').'/'.$this->getNamespace().'/'.$this->getInvoker()->getOid().'-'.$filename;
    return $path;
  }

  protected function getNamespace() {
    return strtolower(get_class($this->getInvoker()));
  }

}