<?php

namespace App\Services;

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class AmazonSES
{
    protected $_client;

    /**
     * AmazonSES constructor. Create AmazonSES Object.
     */
    public function __construct()
    {
        $this->_client = new SesClient([
            'version' => 'latest',
            'region' => config('mail.mailers.ses.region'),
            'credentials' => [
                'key'    => config('mail.mailers.ses.key'),
                'secret' => config('mail.mailers.ses.secret')
            ],
        ]);
    }

    /**
     * Send custom verification email to Merchant
     *
     * @param $email
     */
    public function sendVerifyEmail($email, $template)
    {
        try {
            $this->_client->sendCustomVerificationEmail([
                'EmailAddress' => $email,
                'TemplateName' => $template
            ]);
        } catch (AwsException $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * Create a custom verification email template
     */
    public function createCustomVerificationEmailTemplate($templateName = 'verifySenderEmail')
    {
        // Create custom email template
        $successUrl = 'https://app.bixgrow.com/email-verify-success';
        $failUrl = 'https://app.bixgrow.com/email-verify-fail';

        $content = view('mails.merchants.amazon_verify')->render();
        return $this->_client->createCustomVerificationEmailTemplate([
            'FromEmailAddress' => '"Bixgrow" <' . config('mail.from.address') . '>',
            'SuccessRedirectionURL' => $successUrl,
            'FailureRedirectionURL' => $failUrl,
            'TemplateContent' => $content,
            'TemplateName' => $templateName,
            'TemplateSubject' => 'Confirm your email address for Affiliate marketing system by BixGrow'
        ]);
    }

    public function listCustomVerificationEmailTemplates() {
        return $this->_client->listCustomVerificationEmailTemplates();
    }


    /**
     * update a custom verification email template
     */
    public function updateCustomVerificationEmailTemplate(array $dataUpdate)
    {
        $this->_client->updateCustomVerificationEmailTemplate($dataUpdate);
    }

    /**
     * Delete a custom verification email template
     */
    public function deleteCustomVerificationEmailTemplate($templateName)
    {
        $this->_client->deleteCustomVerificationEmailTemplate([
            'TemplateName' => $templateName
        ]);
    }

    /**
     * Get list verified emails from Amazon
     *
     * @return mixed
     */
    public function listIdentities()
    {
        $result = $this->_client->listIdentities();

        return $result['Identities'];
    }

    /**
     * Delete verified email on Amazon
     *
     * @param $email
     */
    public function deleteIdentity($email)
    {
        $this->_client->deleteIdentity([
            'Identity' => $email
        ]);
    }

    public function listVerifiedEmailAddresses()
    {
        $result = $this->_client->listVerifiedEmailAddresses(['tuanluis93@gmail.com']);

        return $result;
    }

    public function verifyEmailIdentity($email)
    {
        return $this->_client->verifyEmailIdentity([
            'EmailAddress' => $email,
        ]);
    }


    public function sendTestEmail($senderEmail)
    {


        $subject = 'Amazon SES test (AWS SDK for PHP)';
        $plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.' ;
        $html_body =  '<h1>AWS Amazon Simple Email Service Test Email</h1>'.
                    '<p>This email was sent with <a href="https://aws.amazon.com/ses/">'.
                    'Amazon SES</a> using the <a href="https://aws.amazon.com/sdk-for-php/">'.
                    'AWS SDK for PHP</a>.</p>';
        $char_set = 'UTF-8';
        $recipientEmail = ['vi@ucomy.com'];
        try{
            $result = $this->_client->sendEmail([
                'Destination' => [
                    'ToAddresses' => $recipientEmail,
                ],
                'ReplyToAddresses' => [$senderEmail],
                'Source' => $senderEmail,
                'Message' => [
                  'Body' => [
                      'Html' => [
                          'Charset' => $char_set,
                          'Data' => $html_body,
                      ],
                      'Text' => [
                          'Charset' => $char_set,
                          'Data' => $plaintext_body,
                      ],
                  ],
                  'Subject' => [
                      'Charset' => $char_set,
                      'Data' => $subject,
                  ],
                ],
            ]);
            return true;
        }catch(AwsException $e) {
            report($e);
            return false;
        }

        // return $result;
        // $messageId = $result['MessageId'];
    }
}
