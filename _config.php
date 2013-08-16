<?php

Product::add_extension("ProductEnquiryDecorator");
Product_Controller::add_extension("ProductControllerEnquiryDecorator");
OrderItem::add_extension("EnquiryOrderItem");