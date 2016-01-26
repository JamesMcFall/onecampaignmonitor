<?php
namespace Craft;

class OneCampaignMonitor_BaseService extends BaseApplicationComponent {
    private $_auth;

    protected function auth() {
        if (is_null($this->_auth)) {
            if ($key = craft()->plugins->getPlugin('OneCampaignMonitor')->getSettings()->campaignmonitor_api_key) {
                $this->_auth = ['api_key' => $key];
            } else {
                throw new Exception('Must authenticate with Campaign Monitor');
            }
        }
        return $this->_auth;
    }

    protected function parseCustomFields($fields) {
        $data = array();
        foreach ($fields as $key => $value) {
            $data[] = ['Key' => $key, 'Value' => $value];
        }
        return $data;
    }

    protected function response($result, $errorMessage = null) {
        if ($result->was_successful()) {
            return true;
        } else {
            $error = "{$result->response->Code}: {$result->response->Message}";
            $message = ($errorMessage ?: 'Error') . ': ' . $error;
            OneCampaignMonitorPlugin::log($message, LogLevel::Error);
            return false;
        }
    }
}
