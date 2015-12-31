<?php

class EnquiryOrderItem extends DataExtension
{
    
    public static $has_one = array(
        'Enquiry' => 'Enquiry'
    );
}
