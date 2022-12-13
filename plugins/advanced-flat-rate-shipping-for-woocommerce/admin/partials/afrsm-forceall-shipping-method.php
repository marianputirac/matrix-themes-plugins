<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Forceall shipping method
 */
if ( !class_exists( 'AFRSM_Forceall_Shipping_Method' ) ) {
    class AFRSM_Forceall_Shipping_Method extends WC_Shipping_Method {
        
        /**
         * Constructor for your shipping class
         *
         * @access public
         * @return void
         */
        public function __construct() {
            $this->id = 'forceall';
            $this->method_title = __('Advanced Flat Rate Shipping (Force All Shipping Methods)', AFRSM_PRO_TEXT_DOMAIN);
            $this->method_description = __('You can configure this special shipping option from Advanced Flat Rate Shipping Method settings.', AFRSM_PRO_TEXT_DOMAIN); // 

            $this->init();

            $this->enabled = 'yes';
        }

        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        public function init() {
            // Load the settings API
            $this->init_form_fields();
            $this->init_settings();

            // Save settings in admin if you have any defined
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        }
        
        /**
         * Define settings field for this shipping
         * @return void 
         */
        public function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('', AFRSM_PRO_TEXT_DOMAIN),
                    'type' => 'hidden',
                    'default' => 'yes'
                )
            );
        }

        /**
         * calculate_shipping function.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping($package = array()) {
            
            global $woocommerce_wpml;
            $forceall_shipping_cost = 0;
            $final_cost = 0;
            $forceall_shipping_label = '';
            
            if( !empty($package['rates']) ) {
                foreach ($package['rates'] as $pkey => $pvalue) {
                    
                    if( isset($woocommerce_wpml) && !empty($woocommerce_wpml->multi_currency) ) {
                        $final_cost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $pvalue->cost );
                    } else {
                        $final_cost = $pvalue->cost;
                    }
                    
                    $tax_sum = array_sum($pvalue->taxes);
                    $forceall_taxable_shipping_cost = $final_cost + $tax_sum;
                    $forceall_shipping_cost += $forceall_taxable_shipping_cost;
                    $forceall_shipping_label .= "( " . $pvalue->label . ": " . get_woocommerce_currency_symbol() . $forceall_taxable_shipping_cost . " )";
                }
            }
            
            $rate = array(
                'id'    => $this->id,
                'label' => $forceall_shipping_label,
                'cost'  => $forceall_shipping_cost,
                'taxes' => false
            );

            // Register the rate
            $this->add_rate($rate);

            do_action('woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate, $package);
        }
        
    }
}