<?php
/**
 * NOTICE OF LICENSE
 *
 *  @author    PensoPay A/S
 *  @copyright 2019 PensoPay
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 *  E-mail: support@pensopay.com
 */

/**
 * @since 1.5.0
 */
class PensopayIframeresponseModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $context = Context::getContext();

        $cart_id = Tools::getValue('id_cart');
        $key = Tools::getValue('key');

        if (!$cart_id || !$key) {
            Tools::redirect('index.php');
        }

        $cart = new Cart($cart_id);
        if (!$cart->id || $cart->secure_key !== $key) {
            Tools::redirect('index.php');
        }

        $mode = Tools::getValue(PensoPay::MODE_VARIABLE);
        if ($mode === PensoPay::MODE_CANCEL) {
            $context->cookie->__set(PensoPay::COOKIE_ORDER_CANCELLED, '1');
            $context->cookie->write();
        }
        if (_PS_VERSION_ >= '1.7.0.0') {
            echo $this->l('Please wait...');
        } else {
            echo 'Please wait...';
        }
        exit;
    }
}
