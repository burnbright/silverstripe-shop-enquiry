<?php

class EnquiryOrderItem extends DataObjectDecorator{
	
	function extraStatics(){
		return array(
			'has_one' => array(
				'Enquiry' => 'Enquiry'	
			)	
		);
	}
	
}