<?php

class ProductEnquiryDecorator extends DataExtension{

	private static $db = array(
		'AllowEnquiry' => 'Boolean'
	);

	function updateCMSFields(FieldList $fields){
		$fields->addFieldToTab("Root.Main",
			CheckboxField::create("AllowEnquiry","Allow enquiries on this product"),
			"Metadata"
		);
	}

}

class ProductControllerEnquiryDecorator extends Extension{
	
	private static $allowed_actions = array(
		'enquire',
		'startenquiry',
		'EnquiryForm'
	);
	
	function startenquiry($data,$form) {
		//TODO: update items that are the same
		Enquiry::clear();	//clear any past enquiries
		$quantity = isset($data['Quantity']) ? (int)$data['Quantity'] : 1;
		if($buyable = $form->getBuyable($data)){
			$item = $buyable->createItem($quantity,$data);
			$enquiry = Enquiry::find_or_make();
			$enquiry->getComponents('Items')->add($item);
		}
		if(Director::is_ajax()){
			return $this->EnquiryForm()->forAjaxTemplate();
		}
		$this->owner->redirect(Controller::join_links($this->owner->Link(),"enquire"));
		return;
	}
	
	function enquire() {
		return array(
			'Form' => $this->EnquiryForm(),
			'AddProductForm' => $this->EnquiryForm(), //deprecated
			'VariationForm' => $this->EnquiryForm(), //deprecated
		);
	}
	
	function EnquiryForm() {
		return new EnquiryForm($this->owner);
	}
	
	function updateForm($form) {
		if($this->owner->AllowEnquiry){
			$form->Actions()->push(new FormAction('startenquiry',_t("AddProductForm.ENQUIRE",'Enquire')));
			$form->Actions()->removeByName('action_addtocart');
		}
	}
	
}