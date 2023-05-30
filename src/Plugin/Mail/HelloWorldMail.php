<?php

/**
 * @file
 * A mail sending plugin
 */

namespace Drupal\hello_world\Plugin\Mail;

use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

// TODO move to config
const API_KEY = "some_key";

/**
 * Defines the Hello World mail plugin
 * 
 * @Mail(
 *  id = "hello_world_mail",
 *  label = @Translation("Hello world mailer"),
 *  description = @Translation("Send an email using the smtp2go api"),
 * )
 */
class HelloWorldMail implements MailInterface, ContainerFactoryPluginInterface
{


    /**
     * {@inheritdoc}
     */
    public static function Create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static();
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $message)
    {
        $message['body'] = implode("\n\n", $message['body']);
        $message['body'] = MailFormatHelper::htmlToText($message['body']);
        $message['body'] = MailFormatHelper::wrapMail($message['body']);

        return $message;
    }

    public function mail(array $message)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        curl_setopt(
            $curl,
            CURLOPT_URL,
            "https://api.smtp2go.com/v3/email/send"
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
            "api_key" => API_KEY,
            "sender" => $message['from'],
            "to" => [0 => $message['to']],
            "subject" => $message['subject'],
            "text_body" => $message['body'],
        )));
        $jsonResult = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($jsonResult, true);
        if(!isset($result['data']['error'])){
            return $message;
        }
    }
}
