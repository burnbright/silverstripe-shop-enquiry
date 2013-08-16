<?php

class EnquiryOrderItem extends DataExtension{
	
	static $has_one = array(
		'Enquiry' => 'Enquiry'	
	);

}