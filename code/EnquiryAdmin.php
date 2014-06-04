<?php

class EnquiryAdmin extends ModelAdmin{
	
	private static $url_segment = "enquiries";
	
	private static $menu_title = "Enquiries";
	
	private static $managed_models = array(
		"Enquiry"
	);
	
}