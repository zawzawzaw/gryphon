<?php
class Manic_Jobsubmission_IndexController extends Mage_Core_Controller_Front_Action
{
   	public function indexAction() {

   		if ($this->getRequest()->isPost()) {
    
            $first_name = $this->getRequest()->getPost('first_name', array());
            $last_name = $this->getRequest()->getPost('last_name', array());
            $email = $this->getRequest()->getPost('email', array());
            $mobile = $this->getRequest()->getPost('mobile', array());
            $subject = $this->getRequest()->getPost('subject', array());
            $resume = $this->getRequest()->getPost('resume', array());

            if(!empty($first_name) && !empty($last_name) && !empty($email) && !empty($mobile) && !empty($subject) && !empty($resume) ) {
            	$storeId = Mage::app()->getStore()->getStoreId();
	            $templateId = "Job Application Email";
	            $receiveEmail = Mage::getStoreConfig('trans_email/ident_custom1/email', $storeId);;
	            $receiveName = Mage::getStoreConfig('trans_email/ident_custom1/name', $storeId);;

	            $emailTemplateVariables = array();
				$emailTemplateVariables['first_name'] = $first_name;
				$emailTemplateVariables['last_name'] = $last_name;
				$emailTemplateVariables['email'] = $email;
				$emailTemplateVariables['mobile'] = $mobile;
				$emailTemplateVariables['subject'] = $subject;			

				$emailTemplate = Mage::getModel('core/email_template')->loadByCode($templateId);
				
				$emailTemplate->getProcessedTemplate($emailTemplateVariables);

				$file = Mage::getBaseDir('media') . DS . 'careers' . DS . 'cvs/' . $resume;
				$file = file_get_contents($file);
			    $attachment = $emailTemplate->getMail()->createAttachment($file);
			    $attachment->type = 'application/pdf';
			    $attachment->filename = $resume;

				$emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));

				$emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));

				$emailTemplate->send($receiveEmail, $receiveName, $emailTemplateVariables);

				$response['status'] = 'SUCCESS';
            	$response['message'] = 'Successfully submitted your resume.';			
            }else {
            	$response['status'] = 'ERROR';
            	$response['message'] = 'Please fill all the input fields.';	
            }
    
        }else {
        	$response['status'] = 'ERROR';
            $response['message'] = '';
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

	}

	public function uploadcvAction() {

		ini_set('display_errors',1);
		ini_set('display_startup_errors',1);
		error_reporting(-1);

		// Set the uplaod directory
		$uploadDir = Mage::getBaseDir('media') . DS . 'careers' . DS . 'cvs/';

		// Set the allowed file extensions
		$fileTypes = array('pdf', 'doc', 'docx'); // Allowed file extensions
		
		$timestamp = $this->getRequest()->getPost('timestamp', array());
		$token = $this->getRequest()->getPost('token', array());
		$verifyToken = md5('unique_salt' . $timestamp);

		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$tempFile   = $_FILES['Filedata']['tmp_name'];			
			$rand_no = mt_rand();
			$targetFile = $uploadDir . $rand_no . '_' . $_FILES['Filedata']['name'];

			// Validate the filetype
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

				// Save the file
				move_uploaded_file($tempFile, $targetFile);
				$result['success']  = true;
	        	$result['error']    = false;
	        	$result['file_name'] = $rand_no . '_' . $_FILES['Filedata']['name'];
	        	$result['message'] = $_FILES['Filedata']['name'];

			} else {

				// The file type wasn't allowed
				$result['success']  = false;
	        	$result['error']    = true;
	        	$result['message'] = 'Invalid file type.';

			}
		}else {
			// The file type wasn't allowed
			$result['success']  = false;
        	$result['error']    = true;
        	$result['message'] = 'Token Mismatch.';
		}

		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

	}

	public function checkexistsAction() {

		$targetFolder = Mage::getBaseDir('media') . DS . 'careers' . DS . 'cvs/'; // Relative to the root and should match the upload folder in the uploader script

		if (file_exists($targetFolder . '/' . $_POST['filename'])) {
			echo 1;
		} else {
			echo 0;
		}

	}
}