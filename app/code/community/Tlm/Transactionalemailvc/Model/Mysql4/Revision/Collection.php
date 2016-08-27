<?php
class Tlm_Transactionalemailvc_Model_Mysql4_Revision_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('tlm_transactionalemailvc/revision');
    }
}
