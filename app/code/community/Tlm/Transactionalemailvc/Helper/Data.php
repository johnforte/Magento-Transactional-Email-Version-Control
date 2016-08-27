<?php
class Tlm_Transactionalemailvc_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getRevisions($id){
	$collection=Mage::getModel('tlm_transactionalemailvc/revision')->getCollection()
							->addFieldToFilter('TemplateId', $id)
							->addFieldToSelect('*')
							->setOrder('created_at', 'DESC');
		$revisions=array();
		$revisions[]=array('value'=>'','label'=> 'Select a Revision');
		foreach($collection as $item){
			$revisions[]=array('value'=> $item->getData('id'),'label'=> $item->getData('created_at'));
		}
		return $revisions;
	}
}
?>
