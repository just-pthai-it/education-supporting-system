<?php

namespace App\Mail;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailNotify extends Mailable
{
    use Queueable, SerializesModels;

    private Account $account;
    private string  $token;
    private string  $frontEndUrl;
    private const RESET_PASSWORD_URL = '/reset-password?token=';
    private const SUBJECT            = 'Yêu cầu đặt lại mật khẩu.';
    private const VIEW               = 'mail-forms.account.reset-password';

    /**
     * Create a new message instance.
     *
     * @param Account $account
     * @param string  $token
     * @param string  $frontEndHost
     */
    public function __construct (Account $account, string $token, string $frontEndHost)
    {
        $this->account     = $account;
        $this->token       = $token;
        $this->frontEndUrl = $frontEndHost;
        $this->__loadUser();
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build () : ResetPasswordMailNotify
    {
        $data = ['name' => $this->account->accountable->name,
                 'url'  => $this->frontEndUrl . self::RESET_PASSWORD_URL .
                           "{$this->token}&email={$this->account->email}",];

        return $this->to($this->account->email)->with($data)
                    ->view(self::VIEW)->subject(self::SUBJECT);
    }

    private function __loadUser ()
    {
        $this->account->load(['accountable']);
    }
}
