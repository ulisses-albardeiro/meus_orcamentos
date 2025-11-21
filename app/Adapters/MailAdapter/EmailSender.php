<?php

namespace App\Adapters\MailAdapter;

use PHPMailer\PHPMailer\PHPMailer;

class EmailSender implements MailInterface
{
    private array $attachments = [];
    private array $storedOptions = [];
    private ?PHPMailer $mailer = null;

    public function __construct(private PhpMailFactoryInterface $phpMailFactory) {}

    public function options(?array $options): void
    {
        $this->storedOptions = $options ?? [];
    }

    public function attachment(string $path, ?string $filename): void
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $filename,
        ];
    }

    public function create(
        string $subject,
        string $content,
        string $recipient,
        string $recipientName,
        ?string $replyTo,
        ?string $replyName
    ): void {


        $this->mailer = $this->phpMailFactory->create($this->storedOptions);

        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $content;
        $this->mailer->addAddress($recipient, $recipientName);

        if ($replyTo) {
            $this->mailer->addReplyTo($replyTo, $replyName ?? '');
        }

        foreach ($this->attachments as $a) {
            $this->mailer->addAttachment($a['path'], $a['name']);
        }

        $this->storedOptions = [];
        $this->attachments   = [];
    }

    public function send(): bool
    {
        if (!$this->mailer) {
            return false;
        }

        try {
            return $this->mailer->send();
        } catch (\Exception $e) {
            return false;
        }
    }
}
