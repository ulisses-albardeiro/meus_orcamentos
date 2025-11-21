<?php

namespace App\Adapters\MailAdapter;

interface MailInterface
{
    public function options(?array $options): void;
    public function attachment(string $path, ?string $filename): void;
    public function create(string $subject, string $content, string $recipient, string $recipientName, ?string $replyTo, ?string $replyName): void;
    public function send(): bool;
}
