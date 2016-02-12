<?php
/*
* Facebookpage Module
* Developed for OpenCart 2.x
* Author Gedielson Peixoto - http://www.gepeixoto.com.br
* @03/2015
* Under GPL license.
*/
class ControllerModuleFbpage extends Controller {
  public function index() {
    $data['heading_title'] = ($this->config->get('fbpage_title')) ? html_entity_decode($this->config->get('fbpage_title'), ENT_QUOTES, 'UTF-8') : false;
    $data['page_url'] = $this->config->get('fbpage_page_url');
    $data['width'] = $this->config->get('fbpage_width');
    $data['height'] = $this->config->get('fbpage_height');
    $data['show_cover'] = ($this->config->get('fbpage_show_cover') == 'true') ? 'false' : 'true';
    $data['show_faces'] = $this->config->get('fbpage_show_faces');
    $data['show_posts'] = $this->config->get('fbpage_show_posts');
    $data['locale'] = ($this->config->get('fbpage_locale')) ? $this->config->get('fbpage_locale') : 'pt_BR';
    
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/fbpage.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/fbpage.tpl', $data);
		} else {
			return $this->load->view('default/template/module/fbpage.tpl', $data);
		}
  }
}