<?php

namespace App\Traits;

trait MustVerifyFromEmail
{
    /**
     * Determine if the user has verified their email address.
     * 
     * Xác định xem người dùng đã xác minh địa chỉ email của họ chưa
     * bằng việc kiểm tra trường from_email_verified_at có null hay không
     * 
     * @return bool
     */
    public function hasVerifiedFromEmail()
    {
        return ! is_null($this->from_email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * Đánh dấu email của người dùng đã được xác minh
     * bằng việc cập nhật timestamp ở trường from_email_verified_at
     * 
     * @return bool
     */
    public function markFromEmailAsVerified()
    {
        return $this->forceFill([
            'from_email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * Gửi thông báo xác minh email
     * bằng việc sử dụng Notifications
     * 
     * @return void
     */
    public function sendFromEmailVerificationNotification()
    {
        $ses = new \App\Services\AmazonSES();
        $ses->sendVerifyEmail($this->email);
    }
}