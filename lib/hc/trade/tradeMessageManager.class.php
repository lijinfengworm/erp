<?php

/**
 * Description of tradeMessageManager
 *
 * @author hcsyp
 */
class tradeMessageManager {

    private $handle = null;

    public function __construct($id) {
        $this->id = $id;
    }

    private function getHandle() {
        if ($this->handle === null) {
            $this->handle = new tradeCache();
        }
        return $this->handle;
    }
    
    private function getMessageKey() {
        return 'trade_message_content_' . $this->id;
    }
    

    public function getMessage() {
        $key = $this->getMessageKey();
        $m = $this->getHandle()->get($key);
        
        if (!$m) {
            $m = $this->updateMessage();
        }
        return $m;
    }

    public function updateMessage() {
        if (!$m = trdNewsTable::getNewEventMessage($this->id)) {
            return null;
        }
        $this->getHandle()->set($this->getMessageKey(), $m, 10);
        return $m;
    }
    
    public function clearMessageCache(){
        $key = $this->getMessageKey();
        $this->getHandle()->delete($key);
    }   
}
?>
