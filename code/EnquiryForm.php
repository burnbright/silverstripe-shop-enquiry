<?php
class EnquiryForm extends Form{
	
	function __construct($controller, $name = "EnquiryForm"){
		$content = SiteConfig::current_site_config()->EnquiryFormMesssage;
		$fields = new FieldList(
			new TextField("FirstName","First Name"),
			new TextField("Surname","Surname"),
			new EmailField("Email","Email"),
			new TextareaField("Message","Your Message"),
			new LiteralField("Content","<div>$content</div>")
		);	
		$actions = new FieldList(
			new FormAction('submitenquiry',"Send Enquiry")	
		);
		$validator = new RequiredFields(
			'FirstName',
			'Email',
			'Message'
		);
		parent::__construct($controller, $name, $fields, $actions,$validator);
		$this->extend('updateForm');
	}
	
	function validate(){
		$data = $this->getData();
		$valid = parent::validate();
		if(isset($data['AgreeToTerms']) && !(bool)$data['AgreeToTerms']){
			$this->sessionMessage(_t("EnquiryForm.MUSTAGREEETERMS","You must agree to the terms and conditions"), "bad");
			return false;
		}
		return $valid;
	}
	
	function submitenquiry($data,$form){
		$enquiry = Enquiry::find_or_make();
		$form->saveInto($enquiry);
		$enquiry->write();
		$email = $enquiry->createEmail();
		$email->send();
		Enquiry::clear();
		$this->Controller()->Form()->sessionMessage("Thankyou for your enquiry.","good");
		$this->Controller()->redirect($this->Controller()->Link());
	}
	
	function forAjaxTemplate(){
		$rendered = parent::forAjaxTemplate();
		if($scripts = Requirements::get_custom_scripts()){
			$rendered .= "<script type=\"text/javascript\">\n//<![CDATA[ \n".$scripts."</script>";
		}
		return $rendered;
	}

}