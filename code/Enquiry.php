<?php

class Enquiry extends DataObject{
	
	private static $db = array(
		'FirstName' => 'Varchar',
		'Surname' => 'Varchar',
		'Email' => 'Varchar',
		
		'Title' => 'Varchar',
		'Message' => 'Text'
	);
	
	private static $has_many = array(
		'Items' => 'OrderItem'	
	);
	
	private static $summary_fields = array(
		'Created',
		'Name'
	);
	
	private static $searchable_fields = array(
		'FirstName',
		'Surname',
		'Email'	
	);
	
	public static function find_or_make() {
		$enquiry = Session::get('ShopEnquiry');
		if(!$enquiry){
			$enquiry = new Enquiry();
			$enquiry->write();
			Session::set('ShopEnquiry',$enquiry);
		}
		
		return $enquiry;
	}
	
	public static function clear() {
		Session::set('ShopEnquiry',null);
		Session::clear('ShopEnquiry');
	}
	
	function getCMSFields() {
		return FieldList::create(
			LiteralField::create("content",
				$this->renderWith("EnquiryEmail_content")
			)
		);
	}

	function canEdit($member = null){
		return false;
	}

	function getName() {
		return implode(' ',array_filter(array($this->FirstName,$this->Surname)));
	}
	
	function createEmail($subject = "Website Enquiry", $template = "GenericEmail") {
		$content = $this->renderWith("EnquiryEmail_content");
		$to = Email::getAdminEmail();
		$email = new Email($this->Email,$to,$subject);
		$email->setTemplate($template);
		$email->populateTemplate($this);
		$email->populateTemplate(array('Body' => $content));
		$this->extend('updateReceiptEmail',$email);

		return $email;
	}
	
}