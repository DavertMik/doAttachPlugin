<?php

class AttachableListener extends Doctrine_Record_Listener {

  public function postDelete(Doctrine_Event $event)
  {
    if ($file = $event->getInvoker()->getFile()) unlink($file);
  }

//  public



}

?>