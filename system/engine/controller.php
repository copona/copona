<?php

/**
 * @property string $id
 * @property string $template
 * @property array $children
 * @property array $data
 * @property string $output
 * @property Loader $load
 * @property ConfigManager $config
 * @property User $user
 * @property Url $url
 * @property Log $log
 * @property Request $request
 * @property Response $response
 * @property Cache $cache
 * @property Session $session
 * @property Language $language
 * @property Document $document
 * @property Customer $customer
 * @property Affiliate $affiliate
 * @property Currency $currency
 * @property Tax $tax
 * @property Weight $weight
 * @property Length $length
 * @property Cart $cart
 * @property Encryption $encryption
 * @property Event $event
 * @property ModelAccountActivity $model_account_activity
 * @property ModelAccountAddress $model_account_address
 * @property ModelAccountApi $model_account_api
 * @property ModelAccountCustomerGroup $model_account_customer_group
 * @property ModelAccountCustomer $model_account_customer
 * @property ModelAccountCustomField $model_account_custom_field
 * @property ModelAccountDownload $model_account_download
 * @property ModelAccountOrder $model_account_order
 * @property ModelAccountRecurring $model_account_recurring
 * @property ModelAccountReturn $model_account_return
 * @property ModelAccountReward $model_account_reward
 * @property ModelAccountSearch $model_account_search
 * @property ModelAccountTransaction $model_account_transaction
 * @property ModelAccountWishlist $model_account_wishlist
 * @property ModelAffiliateActivity $model_affiliate_activity
 * @property ModelAffiliateAffiliate $model_affiliate_affiliate
 * @property ModelAffiliateTransaction $model_affiliate_transaction
 * @property ModelCatalogCategory $model_catalog_category
 * @property ModelCatalogInformation $model_catalog_information
 * @property ModelCatalogManufacturer $model_catalog_manufacturer
 * @property ModelCatalogProduct $model_catalog_product
 * @property ModelCatalogReview $model_catalog_review
 * @property ModelCheckoutMarketing $model_checkout_marketing
 * @property ModelCheckoutOrder $model_checkout_order
 * @property ModelCheckoutRecurring $model_checkout_recurring
 * @property ModelDesignBanner $model_design_banner
 * @property ModelDesignLayout $model_design_layout
 * @property ModelDesignTheme $model_design_theme
 * @property ModelDesignTranslation $model_design_translation
 * @property ModelExtensionEvent $model_extension_event
 * @property ModelExtensionExtension $model_extension_extension
 * @property ModelExtensionModule $model_extension_module
 * @property ModelLocalisationCountry $model_localisation_country
 * @property ModelLocalisationCurrency $model_localisation_currency
 * @property ModelLocalisationLanguage $model_localisation_language
 * @property ModelLocalisationLocation $model_localisation_location
 * @property ModelLocalisationReturnReason $model_localisation_return_reason
 * @property ModelLocalisationZone $model_localisation_zone
 * @property ModelSettingApi $model_setting_api
 * @property ModelSettingSetting $model_setting_setting
 * @property ModelSettingStore $model_setting_store
 * @property ModelToolImage $model_tool_image
 * @property ModelToolOnline $model_tool_online
 * @property ModelToolUpload $model_tool_upload
 * @property ModelCatalogAttributeGroup $model_catalog_attribute_group
 * @property ModelCatalogAttribute $model_catalog_attribute
 * @property ModelCatalogDownload $model_catalog_download
 * @property ModelCatalogFilter $model_catalog_filter
 * @property ModelCatalogOption $model_catalog_option
 * @property ModelCatalogRecurring $model_catalog_recurring
 * @property ModelCatalogUrlAlias $model_catalog_url_alias
 * @property ModelCustomerCustomerGroup $model_customer_customer_group
 * @property ModelCustomerCustomer $model_customer_customer
 * @property ModelCustomerCustomField $model_customer_custom_field
 * @property ModelDesignLanguage $model_design_language
 * @property ModelDesignMenu $model_design_menu
 * @property ModelExtensionModification $model_extension_modification
 * @property ModelLocalisationGeoZone $model_localisation_geo_zone
 * @property ModelLocalisationLengthClass $model_localisation_length_class
 * @property ModelLocalisationOrderStatus $model_localisation_order_status
 * @property ModelLocalisationReturnAction $model_localisation_return_action
 * @property ModelLocalisationReturnStatus $model_localisation_return_status
 * @property ModelLocalisationStockStatus $model_localisation_stock_status
 * @property ModelLocalisationTaxClass $model_localisation_tax_class
 * @property ModelLocalisationTaxRate $model_localisation_tax_rate
 * @property ModelLocalisationWeightClass $model_localisation_weight_class
 * @property ModelMarketingAffiliate $model_marketing_affiliate
 * @property ModelMarketingCoupon $model_marketing_coupon
 * @property ModelMarketingMarketing $model_marketing_marketing
 * @property ModelReportActivity $model_report_activity
 * @property ModelReportAffiliate $model_report_affiliate
 * @property ModelReportCoupon $model_report_coupon
 * @property ModelReportCustomer $model_report_customer
 * @property ModelReportMarketing $model_report_marketing
 * @property ModelReportProduct $model_report_product
 * @property ModelReportReturn $model_report_return
 * @property ModelReportSale $model_report_sale
 * @property ModelSaleOrder $model_sale_order
 * @property ModelSaleRecurring $model_sale_recurring
 * @property ModelSaleReturn $model_sale_return
 * @property ModelSaleVoucher $model_sale_voucher
 * @property ModelSaleVoucherTheme $model_sale_voucher_theme
 * @property ModelToolBackup $model_tool_backup
 * @property ModelUserApi $model_user_api
 * @property ModelUserUserGroup $model_user_user_group
 * @property ModelUserUser $model_user_user
 * */
abstract class Controller
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

}