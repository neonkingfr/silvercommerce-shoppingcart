<?php

class CheckoutCatalogueProductControllerExtension extends Extension {
    
    /**
     * Add simple "add item to cart" functionality to products, if
     * catalogue module is installed
     * 
     */
    public function updateForm($form) {
        $object = $this->owner->dataRecord;
        
        // Add object type and classname
        $form
            ->Fields()
            ->push(HiddenField::create('ID')->setValue($object->ID));
            
        $form
            ->Fields()
            ->push(HiddenField::create('ClassName')->setValue($object->ClassName));
            
        $form
            ->Fields()
            ->push(
                QuantityField::create('Quantity', _t('Checkout.Qty','Qty'))
                    ->setValue('1')
                    ->addExtraClass('checkout-additem-quantity')
            );
        
        $form
            ->Actions()
            ->push(
                FormAction::create('doAddItemToCart',_t('Checkout.AddToCart','Add to Cart'))
                    ->addExtraClass('btn')
                    ->addExtraClass('btn-green')
            );

        $requirements = new RequiredFields(array("Quantity"));
    }
    
    public function doAddItemToCart($data, $form) {
        $cart = ShoppingCart::get();
        $cart->add($data["ClassName"], $data["ID"], $data['Quantity']);
        $cart->save();

        $message = _t('Checkout.AddedItemToCart', 'Added item to your shopping cart');
        $message .= ' <a href="'. $cart->Link() .'">';
        $message .= _t('Checkout.ViewCart', 'View cart');
        $message .= '</a>';

        $this->owner->setSessionMessage(
            "success",
            $message
        );

        return $this->owner->redirectBack();
    }
}
