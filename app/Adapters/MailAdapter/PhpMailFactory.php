<?php

namespace App\Adapters\MailAdapter;

use App\Adapters\MailAdapter\PhpMailFactoryInterface;
use PHPMailer\PHPMailer\PHPMailer;

final class PhpMailFactory implements PhpMailFactoryInterface
{
    public function create(array $options = []): PHPMailer
    {
        $mail = new PHPMailer(true);

        // Defaults
        $defaults = [
            'host'        => HOST_EMAIL,
            'username'    => EMAIL_USER,
            'password'    => EMAIL_PASSWORD,
            'port'        => EMAIL_PORT,
            'secure' => PHPMailer::ENCRYPTION_SMTPS,
            'debug'       => 0,
            'charset'     => 'utf-8',
            'lang'        => 'pt_br',
        ];

        $cfg = array_merge($defaults, $options);

        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->CharSet  = $cfg['charset'];
        $mail->setLanguage($cfg['lang']);
        $mail->isHTML(true);
        $mail->SMTPDebug = $cfg['debug'];

        if ($cfg['host'])     $mail->Host = $cfg['host'];
        if ($cfg['username']) $mail->Username = $cfg['username'];
        if ($cfg['password']) $mail->Password = $cfg['password'];
        if ($cfg['port'])     $mail->Port = $cfg['port'];
        if ($cfg['secure'])   $mail->SMTPSecure = $cfg['secure'];

        return $mail;
    }
}
