<?php

class AddProductFormEnquiryDecorator extends Extension{
	
	function updateForm(){
		$this->owner->Actions()->push(new FormAction('startenquiry','Enquire'));
	}
	
}