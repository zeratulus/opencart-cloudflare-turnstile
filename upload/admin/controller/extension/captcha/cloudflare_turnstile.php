<?php
class ControllerExtensionCaptchaCloudflareTurnstile extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/captcha/cloudflare_turnstile');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('captcha_cloudflare_turnstile', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        
        $data['entry_site_key'] = $this->language->get('entry_site_key');
        $data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/captcha/cloudflare_turnstile', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/captcha/cloudflare_turnstile', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha', true);

        if (isset($this->request->post['captcha_cloudflare_turnstile_site_key'])) {
            $data['captcha_cloudflare_turnstile_site_key'] = $this->request->post['captcha_cloudflare_turnstile_site_key'];
        } else {
            $data['captcha_cloudflare_turnstile_site_key'] = $this->config->get('captcha_cloudflare_turnstile_site_key');
        }

        if (isset($this->request->post['captcha_cloudflare_turnstile_secret_key'])) {
            $data['captcha_cloudflare_turnstile_secret_key'] = $this->request->post['captcha_cloudflare_turnstile_secret_key'];
        } else {
            $data['captcha_cloudflare_turnstile_secret_key'] = $this->config->get('captcha_cloudflare_turnstile_secret_key');
        }

        if (isset($this->request->post['captcha_cloudflare_turnstile_status'])) {
            $data['captcha_cloudflare_turnstile_status'] = $this->request->post['captcha_cloudflare_turnstile_status'];
        } else {
            $data['captcha_cloudflare_turnstile_status'] = $this->config->get('captcha_cloudflare_turnstile_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/captcha/cloudflare_turnstile', $data));
    }

    public function install() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('captcha_cloudflare_turnstile', ['captcha_cloudflare_turnstile_status' => 0]);
    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('captcha_cloudflare_turnstile');
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/captcha/cloudflare_turnstile')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}