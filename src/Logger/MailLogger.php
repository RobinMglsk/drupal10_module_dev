<?php

/**
 * @file
 * Logger
 */

namespace Drupal\hello_world\Logger;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Logger\LogMessageParserInterface;
use Psr\Log\LoggerInterface;

/**
 * A logger that sends an email when the log type is "error"
 */
class MailLogger implements LoggerInterface
{
    use RfcLoggerTrait;

    protected $parser;

    protected $configFactory;

    /**
     * Initializes a new instance of the class.
     *
     * @param ConfigFactoryInterface $config_factory The configuration factory interface.
     * @param LogMessageParserInterface $parser The message placeholder parser interface.
     */
    public function __construct(
        ConfigFactoryInterface $config_factory,
        LogMessageParserInterface $parser
    ){
        $this->configFactory = $config_factory;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {

        if($level !== RfcLogLevel::ERROR) {
            return;
        }

        $to = $this->configFactory->get('system.site')->get('mail');
        $langcode = $this->configFactory->get('system.site')->get('language');
        $variables = $this->parser->parseMessagePlaceholders($message, $context);
        $markup = new FormattableMarkup($message, $variables);
        \Drupal::service('plugin.manager.mail')->mail('hello_world', 'hello_world_log', $to, $langcode, [ 'message' => $markup]);
    }
}
