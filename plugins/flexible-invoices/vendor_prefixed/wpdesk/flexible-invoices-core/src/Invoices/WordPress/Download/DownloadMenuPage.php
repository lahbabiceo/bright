<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Download;

use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FixedSubmitField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\GroupedFields;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Register document creators.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class DownloadMenuPage implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string;
     */
    const MENU_SLUG = 'download';
    const NONCE_ACTION = 'batch_download';
    const NONCE_NAME = 'download_invoices';
    /**
     * @var string
     */
    private $template_dir;
    /**
     * @param string $template_dir
     */
    public function __construct(string $template_dir)
    {
        $this->template_dir = $template_dir;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('admin_menu', function () {
            \add_submenu_page(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_MENU_URL, $this->get_tab_name(), $this->get_tab_name(), 'manage_options', self::MENU_SLUG, [$this, 'render_page_action'], 20);
        });
    }
    /**
     * @return Renderer
     */
    private function get_renderer()
    {
        $resolver = new \WPDeskFIVendor\WPDesk\View\Resolver\ChainResolver();
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\View\Resolver\DirResolver($this->template_dir . 'settings'));
        $resolver->appendResolver(new \WPDeskFIVendor\WPDesk\Forms\Resolver\DefaultFormFieldResolver());
        return new \WPDeskFIVendor\WPDesk\View\Renderer\SimplePhpRenderer($resolver);
    }
    /**
     * @return void
     */
    public function render_page_action()
    {
        $renderer = $this->get_renderer();
        $url = 'https://docs.flexibleinvoices.com/article/804-printing-and-downloading-documents?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=download-invoices';
        if (\get_locale() === 'pl_PL') {
            $url = 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=download-invoices#hurtowe-pobieranie-faktur';
        }
        $content = '<div class="wrap"><h1 class="wp-heading-inline">' . \esc_html__('Download', 'flexible-invoices') . '</h1>';
        $content .= '<div class="support-url-wrapper"><a href="' . \esc_url($url) . '" target="_blank">' . \esc_html__('Read user\'s manual &rarr;', 'flexible-invoices') . '</a></div>';
        $content .= '<hr class="wp-header-end">';
        $content .= $renderer->render('form-start', ['form' => $this, 'method' => 'POST', 'action' => '']);
        $content .= $this->render_fields($renderer);
        $content .= $renderer->render('form-end');
        $content .= '</div>';
        echo $content;
        //phpcs:ignore
    }
    /**
     * @param Renderer $renderer
     *
     * @return string
     */
    public function render_fields(\WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer) : string
    {
        $content = '';
        foreach ($this->get_fields() as $field) {
            $content .= $renderer->render($field->should_override_form_template() ? $field->get_template_name() : 'form-field', ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $this->get_form_id(), 'value' => '', 'template_name' => $field->get_template_name()]);
        }
        return $content;
    }
    /**
     * @return array|Field[]
     */
    protected function get_fields() : array
    {
        return [(new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('Download Invoices', 'flexible-invoices'))->set_description(\esc_html__('If the download fails select smaller date range and try again.', 'flexible-invoices')), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\GroupedFields())->set_name('grouped_field')->set_grouped_fields([(new \WPDeskFIVendor\WPDesk\Forms\Field\DateField())->set_name('start_date')->set_label(\esc_html__('From:', 'flexible-invoices'))->add_class('medium-text hs-beacon-search')->set_default_value(\date('Y-m-d', \strtotime('NOW - 1 months')))->set_attribute('data-beacon_search', 'Download'), (new \WPDeskFIVendor\WPDesk\Forms\Field\DateField())->set_name('end_date')->set_label(\esc_html__('To:', 'flexible-invoices'))->add_class('medium-text hs-beacon-search')->set_default_value(\date('Y-m-d'))->set_attribute('data-beacon_search', 'Download')]), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FixedSubmitField())->set_name('download_documents')->set_label(\esc_html__('Download', 'flexible-invoices'))->add_class('button-primary'), (new \WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField(self::NONCE_ACTION))->set_name(self::NONCE_NAME)];
    }
    /**
     * @return string
     */
    public function get_method() : string
    {
        return 'POST';
    }
    /**
     * @return string
     */
    public function get_action() : string
    {
        return \admin_url('admin-ajax.php?action=documents-batch-download');
    }
    /**
     * @return string
     */
    public function get_form_id() : string
    {
        return 'download';
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'download';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('Download', 'flexible-invoices');
    }
}
