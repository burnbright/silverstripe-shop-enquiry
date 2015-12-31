<?php

class ProductEnquiryDecorator extends DataExtension
{

    private static $db = array(
        'AllowEnquiry' => 'Boolean'
    );

    public function updateCMSFields(FieldList $fields)
    {
        if (!Enquiry::config()->global_enquire) {
            $fields->addFieldToTab("Root.Main",
                CheckboxField::create("AllowEnquiry", "Allow enquiries on this product"),
                "Metadata"
            );
        }
    }
}

class ProductControllerEnquiryDecorator extends Extension
{
    
    private static $allowed_actions = array(
        'enquire',
        'startenquiry',
        'EnquiryForm'
    );

    /**
     * Add enquiry action to product form.
     */
    public function updateForm($form)
    {
        if ($this->owner->AllowEnquiry || Enquiry::config()->global_enquire) {
            $form->Actions()->push(new FormAction('startenquiry', _t("AddProductForm.ENQUIRE", 'Enquire')));
            $form->Actions()->removeByName('action_addtocart');
        }
    }
    
    /**
     * Start an enquiry.
     */
    public function startenquiry($data, $form)
    {
        $quantity = isset($data['Quantity']) ? (int)$data['Quantity'] : 1;
        if ($buyable = $form->getBuyable($data)) {
            $item = $buyable->createItem($quantity, $data);
            $enquiry = Enquiry::find_or_make();
            $enquiry->Items()->add($item);
        }
        if (Director::is_ajax()) {
            return $this->EnquiryForm()->forAjaxTemplate();
        }
        $this->owner->redirect(Controller::join_links($this->owner->Link(), "enquire"));
        return;
    }
    
    public function enquire()
    {
        return array(
            'Form' => $this->EnquiryForm(),
            'AddProductForm' => $this->EnquiryForm(), //deprecated
            'VariationForm' => $this->EnquiryForm(), //deprecated
        );
    }
    
    public function EnquiryForm()
    {
        return new EnquiryForm($this->owner);
    }
}
