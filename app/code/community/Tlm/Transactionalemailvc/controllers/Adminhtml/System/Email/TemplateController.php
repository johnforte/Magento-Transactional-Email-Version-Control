<?php
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml').DS.'System'.DS.'Email'.DS.'TemplateController.php';
class Tlm_Transactionalemailvc_Adminhtml_System_Email_TemplateController extends Mage_Adminhtml_System_Email_TemplateController
{
    /**
     * Save action
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        $id = $this->getRequest()->getParam('id');

        $template = $this->_initTemplate('id');
        if (!$template->getId() && $id) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('adminhtml')->__('This Email template no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }

        try {
            $template->setTemplateSubject($request->getParam('template_subject'))
                ->setTemplateCode($request->getParam('template_code'))
                ->setTemplateText($request->getParam('template_text'))
                ->setTemplateStyles($request->getParam('template_styles'))
                ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
                ->setOrigTemplateCode($request->getParam('orig_template_code'))
                ->setOrigTemplateVariables($request->getParam('orig_template_variables'));

            if (!$template->getId()) {
                $template->setAddedAt(Mage::getSingleton('core/date')->gmtDate());
                $template->setTemplateType(Mage_Core_Model_Email_Template::TYPE_HTML);
            }

            if ($request->getParam('_change_type_flag')) {
                $template->setTemplateType(Mage_Core_Model_Email_Template::TYPE_TEXT);
                $template->setTemplateStyles('');
            }
            if($request->getParam('versioncontrol')==''){
              $template->save();
              $revision=Mage::getModel('tlm_transactionalemailvc/revision');
              $revision->setData('TemplateId',$template->getId());
              $revision->setData('Code',$request->getParam('template_code'));
              $revision->setData('Subject',$request->getParam('template_subject'));
              $revision->setData('Text', $request->getParam('template_text'));
              $revision->setData('Styles', $request->getParam('template_styles'));
              $revision->save();
            }else{
                $revision=Mage::getModel('tlm_transactionalemailvc/revision')->load($request->getParam('versioncontrol'),'id');
                $template->setTemplateSubject($revision->getData('Subject'));
                $template->setTemplateCode($revision->getData('Code'));
                $template->setTemplateText($revision->getData('Text'));
                $template->setTemplateStyles($revision->getData('Style'));
                $template->save();
            }

            Mage::getSingleton('adminhtml/session')->setFormData(false);
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__('The email template has been saved.')
            );

            $this->_redirect('*/*');
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->setData(
                'email_template_form_data',
                $this->getRequest()->getParams()
            );
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_forward('new');
        }
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        $template = $this->_initTemplate('id');
        if ($template->getId()) {
            try {
                $template->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The email template has been deleted.')
                );
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('adminhtml')->__('An error occurred while deleting email template data. Please review log and try again.')
                );
                Mage::logException($e);
                $this->_redirect('*/*/edit', array('id' => $template));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('adminhtml')->__('Unable to find a Email Template to delete.')
        );
        $this->_redirect('*/*/');
    }
}
