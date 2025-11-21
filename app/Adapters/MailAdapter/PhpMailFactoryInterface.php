<?php
namespace App\Adapters\MailAdapter;

use PHPMailer\PHPMailer\PHPMailer;

interface PhpMailFactoryInterface
{
    public function create(array $options = []): PHPMailer;
}
