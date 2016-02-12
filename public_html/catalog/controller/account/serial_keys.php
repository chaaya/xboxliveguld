<?php
class ControllerAccountSerialkeys extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/serial_keys', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/serial_keys');

		$this->document->setTitle($this->language->get('heading_title'));

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_serial_keys'),
			'href'      => $this->url->link('account/serial_keys', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		$this->load->model('account/serial_keys');
		$serialkey_total = $this->model_account_serial_keys->getTotalSerialkeys();
		$data['sct'] = $serialkey_total;
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_orderid'] = $this->language->get('text_orderid');
		$data['text_productname'] = $this->language->get('text_productname');
		$data['text_serialkey'] = $this->language->get('text_serialkey');
		$data['text_instructions'] = $this->language->get('text_instructions');
		$data['text_downloadlink'] = $this->language->get('text_downloadlink');
		$data['text_dateoforder'] = $this->language->get('text_dateoforder');

			$data['serialkeys'] = array();

			$results = $this->model_account_serial_keys->getSerialkeys(0, 100);

			foreach ($results as $result) {

				$data['serialkeys'][] = array(
					'order_id'   => $result['order_id'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'productname'       => $result['productname'],
					'serialkey'       => $result['serialkey'],
					'downloadlink'       => $result['downloadlink'],
					'instructions_link'       => "index.php?route=information/information&information_id=".$result['information_id'],
					'instructions_title'       => $result['information_title']
				);

			}




			$pagination = new Pagination();
			$pagination->limit = $this->config->get('config_catalog_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('account/serial_keys', 'page={page}', 'SSL');

			$data['pagination'] = $pagination->render();

			$data['continue'] = $this->url->link('account/account', '', 'SSL');


    	$data['button_continue'] = $this->language->get('button_continue');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/serial_keys.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/serial_keys.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/serial_keys.tpl', $data));
		}

  	}

	public function serialkey() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/serial_keys', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->load->model('account/serial_keys');

		if (isset($this->request->get['order_serialkey_id'])) {
			$order_serialkey_id = $this->request->get['order_serialkey_id'];
		} else {
			$order_serialkey_id = 0;
		}
	}
}
?>