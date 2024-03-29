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
class PensopayValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $json = Tools::file_get_contents('php://input');
        if (!$json) {
            $json = $GLOBALS['HTTP_RAW_POST_DATA']; // Deprecated since PHP 5.6
        }

        $cart_id = Tools::getValue('id_cart');
        $key = Tools::getValue('key');

        if (!$cart_id || !$key) {
            PrestaShopLogger::addLog('PensoPay validation callback called without a key and/or cart', 3);
            Tools::redirect('index.php');
        }

        $cart = new Cart($cart_id);
        if (!$cart->id || $cart->secure_key !== $key) {
            PrestaShopLogger::addLog('PensoPay validation callback called without a valid key and/or cart', 3);
            Tools::redirect('index.php');
        }

        $checksum = $_SERVER['HTTP_QUICKPAY_CHECKSUM_SHA256'];

        $pensopay = new PensoPay();
        $pensopay->validate($json, $checksum);
        exit(0);
    }
}
