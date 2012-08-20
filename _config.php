<?php

Object::add_extension("AddProductForm", "AddProductFormEnquiryDecorator");
Object::add_extension("VariationForm", "AddProductFormEnquiryDecorator");
Object::add_extension("Product_Controller", "ProductEnquiryDecorator");
Object::add_extension("OrderItem", "EnquiryOrderItem");