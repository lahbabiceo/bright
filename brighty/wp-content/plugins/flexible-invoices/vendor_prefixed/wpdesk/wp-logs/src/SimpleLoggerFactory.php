<?php

declare (strict_types=1);
namespace WPDeskFIVendor\WPDesk\Logger;

use WPDeskFIVendor\Monolog\Handler\HandlerInterface;
use WPDeskFIVendor\Monolog\Handler\NullHandler;
use WPDeskFIVendor\Monolog\Logger;
use WPDeskFIVendor\Monolog\Handler\ErrorLogHandler;
use WPDeskFIVendor\WPDesk\Logger\WC\WooCommerceHandler;
final class SimpleLoggerFactory implements \WPDeskFIVendor\WPDesk\Logger\LoggerFactory
{
    /** @var Settings */
    private $options;
    /** @var string */
    private $channel;
    /** @var Logger */
    private $logger;
    public function __construct(string $channel, \WPDeskFIVendor\WPDesk\Logger\Settings $options = null)
    {
        $this->channel = $channel;
        $this->options = $options ?? new \WPDeskFIVendor\WPDesk\Logger\Settings();
    }
    public function getLogger($name = null) : \WPDeskFIVendor\Monolog\Logger
    {
        if ($this->logger) {
            return $this->logger;
        }
        $logger = new \WPDeskFIVendor\Monolog\Logger($this->channel);
        if ($this->options->use_wc_log && \function_exists('wc_get_logger')) {
            $logger->pushHandler(new \WPDeskFIVendor\WPDesk\Logger\WC\WooCommerceHandler(\wc_get_logger(), $this->channel));
        }
        // Adding WooCommerce logger may have failed, if so add WP by default.
        if ($this->options->use_wp_log || empty($logger->getHandlers())) {
            $logger->pushHandler($this->get_wp_handler());
        }
        return $this->logger = $logger;
    }
    private function get_wp_handler() : \WPDeskFIVendor\Monolog\Handler\HandlerInterface
    {
        if (\defined('WPDeskFIVendor\\WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            return new \WPDeskFIVendor\Monolog\Handler\ErrorLogHandler(\WPDeskFIVendor\Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM, $this->options->level);
        }
        return new \WPDeskFIVendor\Monolog\Handler\NullHandler();
    }
}
