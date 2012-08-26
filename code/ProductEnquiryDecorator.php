<?php

class ProductEnquiryDecorator extends Extension{
	
	static $allowed_actions = array(
		'enquire',
		'startenquiry',
		'EnquiryForm'
	);
	
	function startenquiry($data,$form){
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
	
	function enquire(){
		return array(
			'Form' => $this->EnquiryForm(),
			'AddProductForm' => $this->EnquiryForm(), //deprecated
			'VariationForm' => $this->EnquiryForm(), //deprecated
		);
	}
	
	function EnquiryForm(){
		$form =  new EnquiryForm($this->owner);
		return $form;
	}
	
	function updateForm($form){
		$form->Actions()->push(new FormAction('startenquiry','Enquire'));
	}
	
}