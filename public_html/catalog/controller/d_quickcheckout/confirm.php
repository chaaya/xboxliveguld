<?php

class ControllerDQuickcheckoutConfirm extends Controller {

	public function index($config){

        $this->load->model('d_quickcheckout/method');
        $this->load->model('module/d_quickcheckout');
        $this->model_module_d_quickcheckout->logWrite('Controller:: confirm/index');

        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/confirm.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/confirm.js');
        }

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_continue'] = $this->language->get('button_continue');

        $data['col'] = $config['account']['guest']['confirm']['column'];
        $data['row'] = $config['account']['guest']['confirm']['row'];

        $json['account'] = $this->session->data['account'];
        $json['confirm'] = $this->session->data['confirm'];

        $this->load->model('d_quickcheckout/order');
        $json['show_confirm'] = $this->model_d_quickcheckout_order->showConfirm();
        $json['payment_popup'] =  $this->model_d_quickcheckout_method->getPaymentPopup($this->session->data['payment_method']['code']);


        $data['json'] = json_encode($json);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/confirm.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/d_quickcheckout/confirm.tpl', $data);
		} else {
			return $this->load->view('default/template/d_quickcheckout/confirm.tpl', $data);
		}
	}

	public function generateRandomVerificationCode() {
			return sprintf("%06d", mt_rand(1, 999999));
	}


	public function sendOrderVerificationCode() {
		$orderVerificationCode = $this->generateRandomVerificationCode();
		$json = array();
		$json['code'] =  $orderVerificationCode;


		$this->session->data['email_fraud_protection_order_verification_code'] = $orderVerificationCode;
		$this->session->data['email_fraud_protection_verified'] = false;

		$message  = "An order was just made on www.xboxliveguld.se" . "\n";
		$message .= "You need to confirm the order with the following code: " . $orderVerificationCode . "\n\n";
		$message .= "If you didnt make the order, please contact PayPal immediately! You may be a victim of fraud.";

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->session->data['payment_address']['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode('XboxLiveGuld.se', ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(sprintf('Your order verification code', html_entity_decode('XboxLiveGuld.se', ENT_QUOTES, 'UTF-8')));
		$mail->setText($message);
		$mail->send();
		return true;

	}

	public function validateCode() {
			  $verificationCode = $this->request->post['code'];
				$json = array();

				if($verificationCode == $this->session->data['email_fraud_protection_order_verification_code']) {
					$this->session->data['email_fraud_protection_verified'] = true;
					$json['isValid'] = true;
				} else {
					$json['isValid'] = false;
				}

				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
	}

	public function updateField(){
        $json['confirm'] = $this->session->data['confirm'] = array_merge($this->session->data['confirm'], $this->request->post['confirm']);
        $this->session->data['comment'] = $this->session->data['confirm']['comment'];

        //statistic
        $this->load->model('module/d_quickcheckout');
        $statistic = array(
            'update' => array(
                'confirm' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function recreateOrder(){
        $this->load->model('d_quickcheckout/order');
        $this->model_d_quickcheckout_order->recreateOrder();
        return true;
    }

		private function preformCheckoutAntiFraudControl() {
		  $continue2Checkout = true;
		  $orderIsVerifiedByEmail = $this->session->data['email_fraud_protection_verified'];

		  if($orderIsVerifiedByEmail==false) {
		    $continue2Checkout = false;
		  } else {
		    $KEY = "aidyumvzwo6rcrtuxbm54rc3cshy"; // Account API Key
		    $IP = $_SERVER['REMOTE_ADDR']; // IP to Check for Proxy/VPN status
		    $UA = $_SERVER['HTTP_USER_AGENT']; // User Browser (optional) - provides better forensics for our algorithm.
		    $IM = 0; // Ignore Mobile Browsers - If you are passing the browser variable to us with the paramater above and this setting is set to 1 then we will not analyze the IP for mobile browsers. Mobile operators tend to recycle their IPs so proxy tests will frequently list them as false-positives. Set this option to 0 to analyze all mobile browsers.
		    $STRICT = 1; // This option controls the level of strictness for the lookup. Setting this option higher will increase the chance for false-positives as well as the time needed to perform the IP analysis. Increase this setting if you still continue to see fraudulent IPs with our base setting or decrease this setting for faster lookups with less false-positives. Current options for this parameter are 0 (fastest), 1 (recommended), 2 (more strict), or 3 (strictest).
		    $ipQualityScoreCheckRequestUrl = 'http://www.ipqualityscore.com/api/ip_lookup.php?KEY='.$KEY.'&IP='.$IP.'&UA='.$UA.'&IM='.$IM.'&STRICT='.$STRICT;
		    $ipQualityScoreCheckResponse = file_get_contents($ipQualityScoreCheckRequestUrl);

		    if($ipQualityScoreCheckResponse==1) {
		      $continue2Checkout = false;
		    }
		  }



		  if($continue2Checkout==false) {
				//clear token that are used for paypal just for security
				unset($this->session->data['token']);;
				$this->session->data['ANTI_FRAUD_CHECK_FAILED'] = true;
				header('HTTP/1.1 401 Unauthorized', true, 401);
				die();
		  }

		}

    public function update(){
				$this->preformCheckoutAntiFraudControl();
				$json = array();
        $this->load->model('account/address');
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/address');
        $this->load->model('d_quickcheckout/order');

        if($this->customer->isLogged()){

            if (empty($this->session->data['payment_address']['address_id'])) {
                $json['addresses'] = $this->model_d_quickcheckout_address->getAddresses();
                $json['payment_address']['address_id'] = $this->customer->getAddressId();
                $json['shipping_address']['address_id'] = $this->customer->getAddressId();
            }

            if($this->session->data['payment_address']['address_id'] == 'new'){
                $json['payment_address']['address_id'] = $this->session->data['payment_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['payment_address']);
            }
            if($this->model_d_quickcheckout_address->showShippingAddress()){
                if($this->session->data['shipping_address']['address_id'] == 'new'){
                    $json['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'] = $this->model_account_address->addAddress($this->session->data['shipping_address']);
                }
            }

        }else{
            if($this->session->data['account'] == 'register'){

                $this->load->model('account/customer');
                $this->model_account_customer->addCustomer($this->session->data['payment_address']);

                if($this->customer->login($this->session->data['payment_address']['email'], $this->session->data['payment_address']['password'])){
                    $json['account'] = $this->session->data['account'] = 'logged';

                    $json['addresses'] = $this->model_d_quickcheckout_address->getAddresses();
                    if (empty($this->session->data['payment_address']['address_id'])) {
                        $json['payment_address']['address_id'] = $this->customer->getAddressId();
                        $json['shipping_address']['address_id'] = $this->customer->getAddressId();
                    }
                }

                //2.1.0.1 fix
                $this->model_d_quickcheckout_order->updateCartForNewCustomerId();
            }
        }
        $this->load->model('d_quickcheckout/method');
        if($this->model_d_quickcheckout_method->getPaymentPopup($this->session->data['payment_method']['code'])){
            $json['cofirm_order'] = true;
            $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);
        }

        $json['order_id'] = $this->session->data['order_id'] = $this->updateOrder();

        //statistic
        $statistic = array(
            'click' => array(
                'confirm' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function updateOrder(){

        $order_data = array();

        $this->load->model('d_quickcheckout/order');
        $this->model_d_quickcheckout_order->getTotals($order_data['totals'], $total, $taxes);


        $this->load->language('checkout/checkout');

        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');

        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            $order_data['store_url'] = HTTP_SERVER;
        }

        if ($this->customer->isLogged()) {
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

            $order_data['customer_id'] = $this->customer->getId();
            $order_data['customer_group_id'] = $customer_info['customer_group_id'];
            $order_data['firstname'] = $customer_info['firstname'];
            $order_data['lastname'] = $customer_info['lastname'];
            $order_data['email'] = $customer_info['email'];
            $order_data['telephone'] = $customer_info['telephone'];
            $order_data['fax'] = $customer_info['fax'];
            $order_data['custom_field'] = unserialize($customer_info['custom_field']);
        } elseif (isset($this->session->data['guest'])) {
            $order_data['customer_id'] = 0;
            $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
            $order_data['firstname'] = $this->session->data['guest']['firstname'];
            $order_data['lastname'] = $this->session->data['guest']['lastname'];
            $order_data['email'] = $this->session->data['guest']['email'];
            $order_data['telephone'] = $this->session->data['guest']['telephone'];
            $order_data['fax'] = $this->session->data['guest']['fax'];
            $order_data['custom_field'] = (isset($this->session->data['guest']['custom_field']['account'])) ? $this->session->data['guest']['custom_field']['account'] : array();
        }

        $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
        $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
        $order_data['payment_company'] = $this->session->data['payment_address']['company'];
        $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
        $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
        $order_data['payment_city'] = $this->session->data['payment_address']['city'];
        $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
        $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
        $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
        $order_data['payment_country'] = $this->session->data['payment_address']['country'];
        $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
        $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
        $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']['address']) ? $this->session->data['payment_address']['custom_field']['address'] : array());

        if (isset($this->session->data['payment_method']['title'])) {
            $order_data['payment_method'] = $this->session->data['payment_method']['title'];
        } else {
            $order_data['payment_method'] = '';
        }

        if (isset($this->session->data['payment_method']['code'])) {
            $order_data['payment_code'] = $this->session->data['payment_method']['code'];
        } else {
            $order_data['payment_code'] = '';
        }

        if ($this->cart->hasShipping()) {
            $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
            $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
            $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
            $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
            $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
            $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
            $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
            $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
            $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
            $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
            $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
            $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
            $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']['address']) ? $this->session->data['shipping_address']['custom_field']['address'] : array());

            if (isset($this->session->data['shipping_method']['title'])) {
                $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
            } else {
                $order_data['shipping_method'] = '';
            }

            if (isset($this->session->data['shipping_method']['code'])) {
                $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
            } else {
                $order_data['shipping_code'] = '';
            }
        } else {
            $order_data['shipping_firstname'] = '';
            $order_data['shipping_lastname'] = '';
            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = '';
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = '';
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '';
            $order_data['shipping_country'] = '';
            $order_data['shipping_country_id'] = '';
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = array();
            $order_data['shipping_method'] = '';
            $order_data['shipping_code'] = '';
        }

        $order_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );
        }

        // Gift Voucher
        $order_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $order_data['vouchers'][] = array(
                    'description'      => $voucher['description'],
                    'code'             => substr(md5(mt_rand()), 0, 10),
                    'to_name'          => $voucher['to_name'],
                    'to_email'         => $voucher['to_email'],
                    'from_name'        => $voucher['from_name'],
                    'from_email'       => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message'          => $voucher['message'],
                    'amount'           => $voucher['amount']
                );
            }
        }



        $order_data['comment'] = $this->session->data['comment'];
        $order_data['total'] = $total;

        if (isset($this->request->cookie['tracking'])) {
            $order_data['tracking'] = $this->request->cookie['tracking'];

            $subtotal = $this->cart->getSubTotal();

            // Affiliate
            $this->load->model('affiliate/affiliate');

            $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

            if ($affiliate_info) {
                $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
                $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
            }

            // Marketing
            $this->load->model('checkout/marketing');

            $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

            if ($marketing_info) {
                $order_data['marketing_id'] = $marketing_info['marketing_id'];
            } else {
                $order_data['marketing_id'] = 0;
            }
        } else {
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';
        }

        $order_data['language_id'] = $this->config->get('config_language_id');
        $order_data['currency_id'] = $this->currency->getId();
        $order_data['currency_code'] = $this->currency->getCode();
        $order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
        $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $order_data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $order_data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $order_data['accept_language'] = '';
        }
        $this->load->model('d_quickcheckout/order');
        $this->model_module_d_quickcheckout->logWrite('Controller:: confirm/updateOrder for order ='.$this->session->data['order_id'].' with $order_data =' .json_encode($order_data));
        return $this->model_d_quickcheckout_order->updateOrder($this->session->data['order_id'], $order_data);
    }
}
