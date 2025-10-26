<?php
class ControllerExtensionCaptchaCloudflareTurnstile extends Controller {
    public function index($error = array()) {
        $this->load->language('extension/captcha/cloudflare_turnstile');

        if ($this->config->get('captcha_cloudflare_turnstile_status')) {
            $data['site_key'] = $this->config->get('captcha_cloudflare_turnstile_site_key');
            $data['error_captcha'] = isset($error['captcha']) ? $error['captcha'] : '';

            return $this->load->view('extension/captcha/cloudflare_turnstile', $data);
        }

        return '';
    }

    public function validate() {
        if ($this->config->get('captcha_cloudflare_turnstile_status')) {
            $this->load->language('extension/captcha/cloudflare_turnstile');

            if (empty($this->request->post['cf-turnstile-response'])) {
                return $this->language->get('error_captcha');
            }

            $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
            $data = [
                'secret'   => $this->config->get('captcha_cloudflare_turnstile_secret_key'),
                'response' => $this->request->post['cf-turnstile-response'],
                'remoteip' => $this->request->server['REMOTE_ADDR']
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $response = json_decode($result);

            if (!$response->success) {
                return $this->language->get('error_captcha');
            }
        }

        return '';
    }
}