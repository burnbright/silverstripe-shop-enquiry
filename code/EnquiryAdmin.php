<?php

class EnquiryAdmin extends ModelAdmin
{
    
    private static $url_segment = "enquiries";
    
    private static $menu_title = "Enquiries";
    
    private static $managed_models = array(
        "Enquiry"
    );

    /**
     * Only display enquiries that have been submitted.
     */
    public function getList()
    {
        $list = parent::getList()
                    ->filter("Sent:GreaterThan", 0);
        return $list;
    }
}
