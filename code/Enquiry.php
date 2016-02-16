<?php

class Enquiry extends DataObject
{
    
    private static $db = array(
        'FirstName' => 'Varchar',
        'Surname' => 'Varchar',
        'Email' => 'Varchar',
        
        'Title' => 'Varchar',
        'Message' => 'Text',

        'Sent' => 'Datetime'
    );
    
    private static $has_many = array(
        'Items' => 'OrderItem'
    );
    
    private static $summary_fields = array(
        'Sent.Nice' => 'Sent',
        'Name',
        'Email'
    );
    
    private static $searchable_fields = array(
        'FirstName',
        'Surname',
        'Email'
    );
    
    private static $default_sort = "Created DESC";

    public static $sessionkey = "ShopEnquiryID";
    
    public static function find_or_make()
    {
        $enquiry = Enquiry::get()->byID(
            (int)Session::get(self::$sessionkey)
        );
        if (!$enquiry) {
            $enquiry = new Enquiry();
            $enquiry->write();
            Session::set(self::$sessionkey, $enquiry->ID);
        }
        
        return $enquiry;
    }
    
    public static function clear()
    {
        Session::set(self::$sessionkey, null);
        Session::clear(self::$sessionkey);
    }
    
    public function getCMSFields()
    {
        return FieldList::create(
            LiteralField::create("content",
                $this->renderWith("EnquiryEmail_content")
            )
        );
    }

    public function canEdit($member = null)
    {
        return false;
    }

    public function getName()
    {
        return implode(' ', array_filter(array($this->FirstName, $this->Surname)));
    }
    
    public function createEmail($subject = "Website Enquiry", $template = "GenericEmail")
    {
        $content = $this->renderWith("EnquiryEmail_content");
        $to = Email::getAdminEmail();
        $email = new Email($this->Email, $to, $subject);
        $email->setTemplate($template);
        $email->populateTemplate($this);
        $email->populateTemplate(array('Body' => $content));
        $this->extend('updateReceiptEmail', $email);

        return $email;
    }
}
