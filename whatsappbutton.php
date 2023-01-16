<?php
/**
 * 2007-2023 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2023 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class WhatsAppButton extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'whatsappbutton';
        $this->tab = 'social_networks';
        $this->version = '1.0.0';
        $this->author = 'Christian Betancourt';
        $this->need_instance = 1;
        $this->logo_path = $this->_path . 'logo.png';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('WhatsApp Button');
        $this->description = $this->l('Prestashop module that displays a button to contact by whatsapp');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('WHATSAPPBUTTON_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayFooterAfter');
    }

    public function uninstall()
    {
        Configuration::deleteByName('WHATSAPPBUTTON_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWhatsAppButtonModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWhatsAppButtonModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_LINK',
                        'label' => $this->l('Link'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_PHONE',
                        'label' => $this->l('Phone'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_COUNTRYCODE',
                        'label' => $this->l('Country Code'),
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'WHATSAPPBUTTON_MESSAGE',
                        'label' => $this->l('Message'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_WIDTH',
                        'label' => $this->l('Width'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_HEIGHT',
                        'label' => $this->l('Height'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_RIGHT',
                        'label' => $this->l('Right'),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'WHATSAPPBUTTON_BOTTOM',
                        'label' => $this->l('Bottom'),
                    ]
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return [
            'WHATSAPPBUTTON_LINK' => Configuration::get('WHATSAPPBUTTON_LINK'),
            'WHATSAPPBUTTON_PHONE' => Configuration::get('WHATSAPPBUTTON_PHONE'),
            'WHATSAPPBUTTON_COUNTRYCODE' => Configuration::get('WHATSAPPBUTTON_COUNTRYCODE'),
            'WHATSAPPBUTTON_MESSAGE' => Configuration::get('WHATSAPPBUTTON_MESSAGE'),

            'WHATSAPPBUTTON_WIDTH' => Configuration::get('WHATSAPPBUTTON_WIDTH', null, null, null, 80),
            'WHATSAPPBUTTON_HEIGHT' => Configuration::get('WHATSAPPBUTTON_HEIGHT', null, null, null, 80),
            'WHATSAPPBUTTON_RIGHT' => Configuration::get('WHATSAPPBUTTON_RIGHT', null, null, null, 15),
            'WHATSAPPBUTTON_BOTTOM' => Configuration::get('WHATSAPPBUTTON_BOTTOM', null, null, null, 15),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS([
            $this->_path . '/views/js/front.js',
            $this->_path . '/views/js/lottie-player.js',
        ]);
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }


    /**
     * Add variables to js
     */
    public function hookDisplayFooterAfter()
    {
        $this->context->smarty->assign([
            'WHATSAPPBUTTON_LINK' => Configuration::get('WHATSAPPBUTTON_LINK'),
            'WHATSAPPBUTTON_PHONE' => Configuration::get('WHATSAPPBUTTON_PHONE'),
            'WHATSAPPBUTTON_COUNTRYCODE' => Configuration::get('WHATSAPPBUTTON_COUNTRYCODE'),
            'WHATSAPPBUTTON_MESSAGE' => Configuration::get('WHATSAPPBUTTON_MESSAGE'),

            'WHATSAPPBUTTON_WIDTH' => Configuration::get('WHATSAPPBUTTON_WIDTH', null, null, null, 80),
            'WHATSAPPBUTTON_HEIGHT' => Configuration::get('WHATSAPPBUTTON_HEIGHT', null, null, null, 80),
            'WHATSAPPBUTTON_RIGHT' => Configuration::get('WHATSAPPBUTTON_RIGHT', null, null, null, 15),
            'WHATSAPPBUTTON_BOTTOM' => Configuration::get('WHATSAPPBUTTON_BOTTOM', null, null, null, 15),
        ]);

        return $this->display(dirname(__FILE__), 'views/templates/front/variables.tpl');
    }
}
