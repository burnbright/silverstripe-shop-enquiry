<?php

class ProductEnquiryDecorator extends Extension{
	
	static $allowed_actions = array(
		'enquire',
		'startenquiry',
		'EnquiryForm'
	);
	
	function startenquiry($data,$form){
		//TODO: update items that are the same
		//TODO: properly handle Variations
			//$this->owner->getBuyable($data) ??
			
		Enquiry::clear();	//clear any past enquiries
		
		$quantity = isset($data['Quantity']) ? (int)$data['Quantity'] : 1;
		$item = $this->owner->createItem($quantity,$data);
		$enquiry = Enquiry::find_or_make();
		$enquiry->getComponents('Items')->add($item);
		
		if(Director::is_ajax()){
			return $this->EnquiryForm()->forAjaxTemplate();
		}
		$this->owner->redirect(Controller::join_links($this->owner->Link(),"enquire"));
		return;
	}
	
	function enquire(){
		return array(
			'AddProductForm' => $this->EnquiryForm(), //could be changd to just Form
			'VariationForm' => $this->EnquiryForm()
		);
	}
	
	function EnquiryForm(){
		$form =  new EnquiryForm($this->owner);
		return $form;
	}
	
}