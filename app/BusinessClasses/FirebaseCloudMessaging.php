<?php

namespace App\BusinessClasses;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use function config as config;

class FirebaseCloudMessaging
{
    private Messaging $messaging;
    private array $token_list;
    private AndroidConfig $config;
    private string $credentials_path;

    public function setUpData (array $info, array $token_list)
    {
        $this->credentials_path = config('filesystems.disks.credentials.file_path');
        $this->token_list       = array_chunk($token_list, 500);
        $this->_setConfig($info);
        $this->_initFactory();
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function send (): array
    {
        $invalid_tokens = [];

        $message = $this->_applyConfig();

        foreach ($this->token_list as $tokens)
        {
            $report = $this->messaging->sendMulticast($message, $tokens);

            if ($report->hasFailures())
            {
                $temp_invalid_tokens = array_merge($report->invalidTokens(), $report->unknownTokens());
                $invalid_tokens      = array_merge($invalid_tokens, $temp_invalid_tokens);
            }
        }

        return $invalid_tokens;
    }

    private function _applyConfig (): CloudMessage
    {
        return CloudMessage::withTarget('token', 'all')
            ->withAndroidConfig($this->config);
    }

    private function _setConfig (array $info)
    {
        $this->config = AndroidConfig::fromArray([
                                                     'ttl'          => '172800s',
                                                     'priority'     => 'high',
                                                     'notification' => [
                                                         'title' => $info['title'],
                                                         'body'  => $info['content'],
                                                     ],
                                                 ]);
    }

    private function _initFactory ()
    {
        $factory         = new Factory();
        $factory         = $factory->withServiceAccount($this->credentials_path);
        $this->messaging = $factory->createMessaging();
    }
}
