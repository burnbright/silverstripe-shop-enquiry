<?php

class EnquiryAdmin extends ModelAdmin{
	
	static $url_segment = "enquiries";
	
	static $menu_title = "Enquiries";
	
	static $managed_models = array(
		"Enquiry","EnquiryType"
	);
	
}