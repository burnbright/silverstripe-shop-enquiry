<?php
class EnquiryForm extends Form
{
    
    public function __construct($controller, $name = "EnquiryForm")
    {
        $content = SiteConfig::current_site_config()->EnquiryFormMesssage;
        $fields = new FieldList(
            new TextField("FirstName", _t('Enquiry.FIRST_NAME', 'First Name')),
            new TextField("Surname", _t('Enquiry.SURNAME', 'Surname')),
            new EmailField("Email", _t('Enquiry.EMAIL', 'Email')),
            new TextareaField("Message", _t('Enquiry.MESSAGE', 'Your Message')),
            new LiteralField("Content", "<div>$content</div>")
        );
        $actions = new FieldList(
            new FormAction('submitenquiry', _t('Enquiry.SUBMIT', 'Send Enquiry'))
        );
        $validator = new RequiredFields(
            'FirstName',
            'Email',
            'Message'
        );
        parent::__construct($controller, $name, $fields, $actions, $validator);
        $this->extend('updateForm');
    }
    
    public function validate()
    {
        $data = $this->getData();
        $valid = parent::validate();
        if (isset($data['AgreeToTerms']) && !(bool)$data['AgreeToTerms']) {
            $this->sessionMessage(_t("EnquiryForm.MUSTAGREEETERMS", "You must agree to the terms and conditions"), "bad");
            return false;
        }
        return $valid;
    }
    
    public function submitenquiry($data, $form)
    {
        $enquiry = Enquiry::find_or_make();
        $form->saveInto($enquiry);
        $enquiry->Sent = date('Y-m-d H:i:s');
        $enquiry->write();
        $email = $enquiry->createEmail();
        $this->extend('beforeEnquirySend', $enquiry, $email);
        $email->send();
        $this->extend('afterEnquirySend', $enquiry);
        Enquiry::clear();
        $form->sessionMessage(_t('Enquiry.THANK_YOU', 'Thankyou for your enquiry.'),"good");
        $this->Controller()->redirect(
            Controller::join_links(
                $this->Controller()->Link(),
                'enquire'
            )
        );
    }
    
    public function forAjaxTemplate()
    {
        $rendered = parent::forAjaxTemplate();
        if ($scripts = Requirements::get_custom_scripts()) {
            $rendered .= "<script type=\"text/javascript\">\n//<![CDATA[ \n".$scripts."</script>";
        }
        return $rendered;
    }
}
