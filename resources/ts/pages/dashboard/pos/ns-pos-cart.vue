<template>
    <div id="pos-cart" class="flex-auto flex flex-col">
        <div id="tools" class="flex pl-2 ns-tab" v-if="visibleSection === 'cart'">
            <div @click="switchTo( 'cart' )" class="flex cursor-pointer rounded-tl-lg rounded-tr-lg px-3 py-2 font-semibold active tab">
                <span>{{ __( 'Cart' ) }}</span>
                <span v-if="order" class="flex items-center justify-center text-sm rounded-full h-6 w-6 bg-green-500 text-white ml-1">{{ order.products.length }}</span>
            </div>
            <div @click="switchTo( 'grid' )" class="cursor-pointer rounded-tl-lg rounded-tr-lg px-3 py-2 border-t border-r border-l inactive tab">
                {{ __( 'Products' ) }}
            </div>
        </div>
        <div class="rounded ns-tab-item flex-auto flex overflow-hidden">
            <div class="cart-table flex flex-auto flex-col overflow-hidden">
                <div id="cart-toolbox" class="w-full mb-10">
                    <div class="overflow-hidden">
                        <div class="flex flex-wrap">
                            <div class="ns-button">
                                <button @click="openNotePopup()" class="w-full h-10 px-3 outline-none">
                                    <img src="/images/comment.png" alt="" srcset="">
                                    <span class="ml-1 hidden md:inline-block">{{ __( 'Comments' ) }}</span>
                                </button>
                            </div>
                            <div class="ns-button">
                                <button @click="selectTaxGroup()" class="w-full h-10 px-3 outline-none flex items-center">
                                    <img src="/images/taxes.png" alt="" srcset="">
                                    <span class="ml-1 hidden md:inline-block">{{ __( 'Taxes' ) }}</span>
                                    <span v-if="order.taxes && order.taxes.length > 0" class="ml-1 rounded-full flex items-center justify-center h-6 w-6 bg-info-secondary text-white">{{ order.taxes.length }}</span>
                                </button>
                            </div>
                            <div class="ns-button">
                                <button @click="selectCoupon()" class="w-full h-10 px-3 outline-none flex items-center">
                                    <img src="/images/coupons.png" alt="" srcset="">
                                    <span class="ml-1 hidden md:inline-block">{{ __( 'Coupons' ) }}</span>
                                    <span v-if="order.coupons && order.coupons.length > 0" class="ml-1 rounded-full flex items-center justify-center h-6 w-6 bg-info-secondary text-white">{{ order.coupons.length }}</span>
                                </button>
                            </div>
                            <div class="ns-button">
                                <button @click="defineOrderSettings()" class="w-full h-10 px-3 outline-none flex items-center">
                                    <img src="/images/settings.png" alt="" srcset="">
                                    <span class="ml-1 hidden md:inline-block">{{ __( 'Settings' ) }}</span>
                                </button>
                            </div>
                            <div class="ns-button" v-if="options.ns_pos_quick_product === 'yes'">
                                <button @click="openAddQuickProduct()" class="w-full h-10 px-3 outline-none flex items-center">
                                    <i class="las la-plus"></i>
                                    <span class="ml-1 hidden md:inline-block">{{ __( 'Product' ) }}</span>
                                </button>
                            </div>
                            <hr class="h-10" style="width: 1px">
                        </div>
                    </div>
                </div>
                <!-- <div id="cart-table-header" class="w-full text-primary font-semibold flex">
                    <div class="w-full lg:w-4/6 p-2 border border-l-0 border-t-0">{{ __( 'Product' ) }}</div>
                    <div class="hidden lg:flex lg:w-1/6 p-2 border-t-0">{{ __( 'Quantity' ) }}</div>
                    <div class="hidden lg:flex lg:w-1/6 p-2 border border-r-0 border-t-0">{{ __( 'Total' ) }}</div>
                </div> -->
                <div id="cart-products-table" class="flex flex-auto flex-col overflow-auto dskfjdksjf">

                    <h2 class="bills-heading">Bills</h2>
                    
                    <!-- Loop Procuts On Cart -->

                    <div class="text-primary flex" v-if="products.length === 0">
                        <div class="w-full text-center py-4">
                            <h3 class="text-header">{{ __( 'No products added...' ) }}</h3>
                        </div>
                    </div>
                    <div :product-index="index" :key="product.barcode" class="product-item flex" v-for="(product, index) of products">
                    
                        <!--<img :src="getHttpsUrl(product.galleries[0].url)" width="90" height="" style="margin-right: 10px;" alt="" srcset="">-->
                        <div class="w-full lg:w-4/6 ">
                            <div class="flex justify-between product-details mb-1">
                                <h3 class="font-semibold text-header">
                                    {{ product.name }} &mdash; {{ product.unit_name }}
                                </h3>
                                
                            </div>
                            <div class="flex justify-between product-controls mb-3">
                                <div class="-mx-1 flex flex-wrap text-description">
                                    <div class="px-1 w-1/2 md:w-auto mb-1">
                                        <a
                                            @click="changeProductPrice( product )"
                                            :class="product.mode === 'wholesale' ? 'text-success-secondary hover:text-success-secondary border-success-secondary' : 'border-info-primary'"
                                            class="cursor-pointer outline-none py-1  text-sm"
                                        >{{ __( 'Price' ) }} : {{ product.unit_price | currency }}</a>
                                    </div>
                                    <div class="px-1 w-1/2 md:w-auto mb-1"> 
                                        <a v-if="allowQuantityModification( product )" @click="openDiscountPopup( product, 'product' )" class="cursor-pointer outline-none py-1 text-sm">{{ __( 'Discount' ) }} <span v-if="product.discount_type === 'percentage'">{{ product.discount_percentage }}%</span> : {{ product.discount | currency }}</a>
                                    </div>
                                    <div class="px-1 w-1/2 md:w-auto mb-1 lg:hidden"> 
                                        <a v-if="allowQuantityModification( product )" @click="changeQuantity( product )" class="cursor-pointer outline-none py-1 text-sm">{{ __( 'Quantity' ) }}: {{ product.quantity }}</a>
                                    </div>
                                    <div class="px-1 w-1/2 md:w-auto mb-1 lg:hidden"> 
                                        <span class="cursor-pointer outline-none py-1 text-sm">{{ __( 'Total' ) }}: {{ product.total_price | currency }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex product-options items-center data">
                                <div class="px-1"> 
                                    <a @click="minusQuantity( product )" style="display: block; border-radius: 3px; border: 1px solid #828282; padding: 0 !important; background: #fff;" :class="allowQuantityModification( product ) ? 'cursor-pointer ns-numpad-key' : ''"  class="hover:text-error-secondary cursor-pointer outline-none py-1 text-sm">
                                       <img src="/images/minus.png" alt="">
                                    </a>
                                </div>
                                <div class="hidden text-header lg:flex mr-2 items-center justify-center">
                                    <span v-if="allowQuantityModification( product )">{{ product.quantity }}</span>
                                </div>
                                <div class="px-1"> 
                                    <a @click="plusQuantity( product )" style="display: block; border-radius: 3px; border: 1px solid #828282; padding: 0 !important; background: #fff;" :class="allowQuantityModification( product ) ? 'cursor-pointer ns-numpad-key' : ''" class="hover:text-error-secondary cursor-pointer outline-none py-1 text-sm">
                                       <img src="/images/plus.png" alt="">
                                    </a>
                                </div>
                                <div class="px-1"> 
                                    <a @click="remove( product )" class="hover:text-error-secondary cursor-pointer outline-none py-1 text-sm">
                                       <img src="/images/trash.png" alt="">
                                    </a>
                                </div>
                                <div class="px-1" v-if="options.ns_pos_allow_wholesale_price && allowQuantityModification( product )"> 
                                    <a :class="product.mode === 'wholesale' ? '' : ''" @click="toggleMode( product )" class="cursor-pointer outline-none text-sm">
                                        <i class="las la-award text-xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="hidden lg:flex w-1/6 p-2 price-text items-center justify-center">{{ product.total_price | currency }}</div>
                    </div>
                    
                    <!-- End Loop -->

                </div>
                <div id="cart-products-summary" class="flex">
                    <table class="table custom-table ns-table w-full text-sm " v-if="visibleSection === 'both'">
                        <tr>
                            <td width="200" class=" p-2">
                                <a @click="selectCustomer()" class="cursor-pointer outline-none py-1 -b text-sm">{{ __( 'Customer' ) }}: {{ customerName }}</a>
                            </td>
                            <td width="200" class=" p-2">{{ __( 'Sub Total' ) }}</td>
                            <td width="200" class=" p-2 text-right">{{ order.subtotal | currency }}</td>
                        </tr>
                        <tr v-if="order.coupons.length > 0">
                            <td width="200" class=" p-2"></td>
                            <td width="200" class=" p-2">
                                <a @click="selectCoupon()" class="cursor-pointer outline-none -dashed py-1 -b text-sm">{{ __( 'Coupons' ) }}</a>
                            </td>
                            <td width="200" class=" p-2 text-right">{{ summarizeCoupons() | currency }}</td>
                        </tr>
                        <tr>
                            <td width="200" class=" p-2">
                                <a @click="openOrderType()" class="cursor-pointer outlin  py-1 -b text-sm">{{ __( 'Type' ) }}: {{ selectedType }}</a>
                            </td>
                            <td width="200" class=" p-2">
                                <span>{{ __( 'Discount' ) }}</span>
                                <span v-if="order.discount_type === 'percentage'">({{ order.discount_percentage }}%)</span>
                                <span v-if="order.discount_type === 'flat'">({{ __( 'Flat' ) }})</span>
                            </td>
                            <td width="200" class=" p-2 text-right">
                                <a @click="openDiscountPopup( order, 'cart' )" class="cursor-pointer outlin  py-1 -b text-sm">{{ order.discount | currency }}</a>
                            </td>
                        </tr>
                        <tr v-if="order.type && order.type.identifier === 'delivery'">
                            <td width="200" class="p-2"></td>
                            <td width="200" class="p-2">
                                <a @click="openShippingPopup()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Shipping' ) }}</a>
                            </td>
                            <td width="200" class=" p-2 text-right">{{ order.shipping | currency }}</td>
                        </tr>
                        <tr class="success">
                            <td width="200" class="p-2">
                                <template v-if="order && options.ns_pos_tax_type === 'exclusive'">
                                    <a v-if="options.ns_pos_price_with_tax === 'yes'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax Included' ) }}: {{ order.total_tax_value + order.products_exclusive_tax_value | currency }}</a>
                                    <a v-else-if="options.ns_pos_price_with_tax === 'no'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax' ) }}: {{ order.total_tax_value | currency }}</a>
                                </template>
                                <template v-else-if="order && options.ns_pos_tax_type === 'inclusive'">
                                    <a v-if="options.ns_pos_price_with_tax === 'yes'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax Included' ) }}: {{ order.total_tax_value + order.products_exclusive_tax_value | currency }}</a>
                                    <a v-else-if="options.ns_pos_price_with_tax === 'no'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax' ) }}: {{ order.total_tax_value | currency }}</a>
                                </template>
                            </td>
                            <td width="200" class="p-2">{{ __( 'Total' ) }}</td>
                            <td width="200" class="p-2 text-right">{{ order.total | currency }}</td>
                        </tr>
                    </table>
                    <table class="table ns-table w-full text-sm" v-if="visibleSection === 'cart'">
                        <tr>
                            <td width="200" class="border p-2">
                                <a @click="selectCustomer()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Customer' ) }}: {{ customerName }}</a>
                            </td>
                            <td width="200" class="border p-2">
                                <div class="flex justify-between">
                                    <span>{{ __( 'Sub Total' ) }}</span>
                                    <span>{{ order.subtotal | currency }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="order.coupons.length > 0">
                            <td width="200" class="border p-2"></td>
                            <td width="200" class="border p-2">
                                <a @click="selectCoupon()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Coupons' ) }}</a>
                            </td>
                            <td width="200" class="border p-2 text-right">{{ summarizeCoupons() | currency }}</td>
                        </tr>
                        <tr>
                            <td width="200" class="border p-2">
                                <a @click="openOrderType()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Type' ) }}: {{ selectedType }}</a>
                            </td>
                            <td width="200" class="border p-2">
                                <div class="flex justify-between items-center">
                                    <p>
                                        <span>{{ __( 'Discount' ) }}</span>
                                        <span v-if="order.discount_type === 'percentage'">({{ order.discount_percentage }}%)</span>
                                        <span v-if="order.discount_type === 'flat'">({{ __( 'Flat' ) }})</span>
                                    </p>
                                    <a @click="openDiscountPopup( order, 'cart' )" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ order.discount | currency }}</a>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="order.type && order.type.identifier === 'delivery'">
                            <td width="200" class="border p-2"></td>
                            <td width="200" class="border p-2">
                                <a @click="openShippingPopup()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Shipping' ) }}</a>
                                <span></span>                          
                            </td>
                        </tr>
                        <tr class="success">
                            <td width="200" class="border p-2">
                                <template v-if="order && options.ns_pos_tax_type === 'exclusive'">
                                    <a v-if="options.ns_pos_price_with_tax === 'yes'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax' ) }}: {{ order.total_tax_value | currency }}</a>
                                    <a v-else-if="options.ns_pos_price_with_tax === 'no'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax Inclusive' ) }}: {{ order.total_tax_value + order.products_exclusive_tax_value | currency }}</a>
                                </template>
                                <template v-else-if="order && options.ns_pos_tax_type === 'inclusive'">
                                    <a v-if="options.ns_pos_price_with_tax === 'yes'" @click="openTaxSummary()" class="cursor-pointer outline-none  py-1 border-info-primary text-sm">{{ __( 'Tax Included' ) }}: {{ order.total_tax_value | currency }}</a>
                                    <a v-else-if="options.ns_pos_price_with_tax === 'no'" @click="openTaxSummary()" class="cursor-pointer outline-none py-1 border-info-primary text-sm">{{ __( 'Tax Included' ) }}: {{ order.total_tax_value + order.products_exclusive_tax_value | currency }}</a>
                                </template>
                            </td>
                            <td width="200" class="border p-2">
                                <div class="flex justify-between w-full">
                                    <span>{{ __( 'Total' ) }}</span>
                                    <span>{{ order.total | currency }}</span>    
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="flex flex-shrink-0 border-tox-edge" id="cart-bottom-buttons">
                    <div @click="payOrder()" id="pay-button" class="side-button">
                        <img src="/images/pay.png" alt="" class="mr-2">
                        <span >{{ __( 'Pay' ) }}</span>
                    </div>
                    <div @click="holdOrder()" id="hold-button" class="side-button block">
                        <img src="/images/kithen.png" alt="" class="mr-2">
                        <span>{{ __( 'Hold' ) }}</span>
                    </div>
                    <div @click="openDiscountPopup( order, 'cart' )" id="discount-button" class="side-button">
                        <img src="/images/discount.png" alt="" class="mr-2"> 
                        <span>{{ __( 'Discount' ) }}</span>
                    </div>
                    <div @click="voidOngoingOrder( order )" id="void-button" class="side-button">
                        <img src="/images/void.png" alt="" class="mr-2">
                        <span>{{ __( 'Void' ) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Popup } from '@/libraries/popup';
import PosPaymentPopup from '@/popups/ns-pos-payment-popup';
import PosConfirmPopup from '@/popups/ns-pos-confirm-popup';
import nsPosQuantityPopupVue from '@/popups/ns-pos-quantity-popup.vue';
import { ProductQuantityPromise } from "./queues/products/product-quantity";
import nsPosDiscountPopupVue from '@/popups/ns-pos-discount-popup.vue';
import nsPosOrderTypePopupVue from '@/popups/ns-pos-order-type-popup.vue';
import { nsHooks, nsSnackBar } from '@/bootstrap';
import nsPosCustomerPopupVue from '@/popups/ns-pos-customer-select-popup.vue';
import { ProductsQueue } from "./queues/order/products-queue";
import { CustomerQueue } from "./queues/order/customer-queue";
import { PaymentQueue } from "./queues/order/payment-queue";
import { TypeQueue } from "./queues/order/type-queue";
import switchTo from "@/libraries/pos-section-switch";
import nsPosShippingPopupVue from '@/popups/ns-pos-shipping-popup.vue';
import nsPosHoldOrdersPopupVue from '@/popups/ns-pos-hold-orders-popup.vue';
import nsPosLoadingPopupVue from '@/popups/ns-pos-loading-popup.vue';
import nsPosNotePopupVue from '@/popups/ns-pos-note-popup.vue';
import nsPosTaxPopupVue from '@/popups/ns-pos-tax-popup.vue';
import nsPosCouponsLoadPopupVue from '@/popups/ns-pos-coupons-load-popup.vue';
import { __ } from '@/libraries/lang';
import nsPosOrderSettingsVue from '@/popups/ns-pos-order-settings.vue';
import nsPosProductPricePopupVue from '@/popups/ns-pos-product-price-popup.vue';
import nsPosQuickProductPopupVue from '@/popups/ns-pos-quick-product-popup.vue';

export default {
    name: 'ns-pos-cart',
    data: () => {
        return {
            popup : null,
            products: [],
            visibleSection: null,
            visibleSectionSubscriber: null,
            optionsSubscriber: null,
            options: {},
            typeSubscribe: null,
            orderSubscribe: null,
            productSubscribe: null,
            settingsSubscribe: null,
            settings: {},
            types: [],
            order: {},
            productImage: {
                galleries: [
                    {
                        url: "http://shishkabsa.touchpaysa.com/storage/2023/07/sandw.jpg" // Replace with your actual URL
                    }
                ]
            },
        }
    },
    computed: {
        selectedType() {
            return this.order.type ? this.order.type.label : 'N/A';
        },
        isVisible() {
            return this.visibleSection === 'cart';
        },
        customerName() {
            return this.order.customer ? this.order.customer.name : 'N/A';
        },
        couponName() {
            return __( 'Apply Coupon' );
        }
    },
    mounted() {
        this.optionsSubscriber  =   POS.options.subscribe( options => {
            this.options    =   options;
        });
        this.typeSubscribe  =   POS.types.subscribe( types => this.types = types );
        this.orderSubscribe  =   POS.order.subscribe( order => {
            this.order   =   order;
        });
        this.productSubscribe  =   POS.products.subscribe( products => {
            this.products = products;
            this.$forceUpdate();
        });

        this.settingsSubscribe  =   POS.settings.subscribe( settings => {
            this.settings   =   settings;
        });

        this.visibleSectionSubscriber   =   POS.visibleSection.subscribe( section => {
            this.visibleSection     =   section;
        });

        /**
         * let's register hotkeys
         */
        for( let shortcut in nsShortcuts ) {
            /**
             * let's declare only shortcuts that
             * works on the pos grid and that doesn't 
             * expect any popup to be visible
             */
            if ([ 
                    'ns_pos_keyboard_hold_order', 
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_hold_order' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.holdOrder();
                });
            }

            if ([ 
                    'ns_pos_keyboard_payment', 
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_payment' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.payOrder();
                });
            }

            if ([ 
                    'ns_pos_keyboard_shipping', 
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_shipping' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.openShippingPopup();
                });
            }

            if ([ 
                    'ns_pos_keyboard_note', 
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_note' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.openNotePopup();
                });
            }
        }
    },
    destroyed() {
        this.visibleSectionSubscriber.unsubscribe();
        this.typeSubscribe.unsubscribe();
        this.orderSubscribe.unsubscribe();
        this.productSubscribe.unsubscribe();
        this.settingsSubscribe.unsubscribe();
        this.optionsSubscriber.unsubscribe();
        
        nsHotPress.destroy( 'ns_pos_keyboard_hold_order' );
        nsHotPress.destroy( 'ns_pos_keyboard_payment' );
        nsHotPress.destroy( 'ns_pos_keyboard_shipping' );
        nsHotPress.destroy( 'ns_pos_keyboard_note' );
    },
    methods: {
        __,

        switchTo,

        openAddQuickProduct() {
            const promise   =   new Promise( ( resolve, reject ) => {
                Popup.show( nsPosQuickProductPopupVue, { resolve, reject })
            });

            promise.then( _ => {
                // ...
            }).catch( _ => {
                // ...
            })
        },

        getHttpsUrl(url) {
            return url.replace("http://", "https://");
        },

        summarizeCoupons() {
            const coupons   =   this.order.coupons.map( coupon => coupon.value );

            if ( coupons.length > 0 ) {
                return coupons.reduce( ( before, after ) => before + after );
            }

            return 0;
        },

        async changeProductPrice( product ) {
            if ( ! this.settings.edit_purchase_price ) {
                return nsSnackBar.error( __( `You don't have the right to edit the purchase price.` ) ).subscribe();
            }

            if ( product.product_type === 'dynamic' ) {
                return nsSnackBar.error( __( 'Dynamic product can\'t have their price updated.' ) ).subscribe();
            }

            if ( this.settings.unit_price_editable ) {
                try {
                    const newPrice  =   await new Promise( ( resolve, reject ) => {
                        return Popup.show( nsPosProductPricePopupVue, { product: Object.assign({}, product ), resolve, reject })
                    });

                    const quantities  =   {
                        ...product.$quantities(), 
                        ...{
                            custom_price_edit : newPrice
                        }
                    }

                    product.$quantities     =   () => quantities;

                    /**
                     * We need to change the price mode
                     * to avoid restoring the original prices.
                     */
                    product.mode    =   'custom';
                    product         =   POS.computeProductTax( product );
                                        
                    POS.recomputeProducts( POS.products.getValue() );
                    POS.refreshCart();

                    return nsSnackBar.success( __( 'The product price has been updated.' ) ).subscribe();
                } catch( exception ) {
                    if ( exception !== false ) {
                        nsSnackBar.error( exception ).subscribe();
                        throw exception;
                    }
                }
            } else {
                return nsSnackBar.error( __( 'The editable price feature is disabled.' ) ).subscribe();
            }
        },

        async selectCoupon() {
            try {
                const response  =   await new Promise( ( resolve, reject ) => {
                    Popup.show( nsPosCouponsLoadPopupVue, { resolve, reject })
                })
            } catch( exception ) {
                
            }
        },

        async defineOrderSettings() {
            if ( ! this.settings.edit_settings ) {
                return nsSnackBar.error( __( 'You\'re not allowed to edit the order settings.' ) ).subscribe();
            }

            try {
                const response  =   await new Promise( ( resolve, reject) => {
                    Popup.show( nsPosOrderSettingsVue, { resolve, reject, order : this.order });
                });

                /**
                 * We'll update the order
                 */
                POS.order.next({ ...this.order, ...response });

            } catch( exception ) {
                // we shouldn't catch any exception here.
            }
        },

        async openNotePopup() {
            try {
                const response  =   await new Promise( ( resolve, reject ) => {
                    const note              =   this.order.note;
                    const note_visibility   =   this.order.note_visibility;
                    Popup.show( nsPosNotePopupVue, { resolve, reject, note, note_visibility });
                });

                const order     =   { ...this.order, ...response };
                POS.order.next( order );
            } catch( exception ) {
                if ( exception !== false ) {
                    nsSnackBar.error( exception.message ).subscribe();
                }
            }
        },

        async selectTaxGroup( activeTab = 'settings' ) {
            try {
                const response              =   await new Promise( ( resolve, reject ) => {
                    const taxes             =   this.order.taxes;
                    const tax_group_id      =   this.order.tax_group_id;
                    const tax_type          =   this.order.tax_type;
                    Popup.show( nsPosTaxPopupVue, { resolve, reject, taxes, tax_group_id, tax_type, activeTab })
                });

                const order             =   { ...this.order, ...response };
                
                POS.order.next( order );
                POS.refreshCart();

            } catch( exception ) {
                console.error( exception );
            }
        },

        openTaxSummary() {
            this.selectTaxGroup( 'summary' );
        },

        voidOngoingOrder() {
            POS.voidOrder( this.order );
        },

        async holdOrder() {

            if ( this.order.payment_status !== 'hold' && this.order.payments.length > 0 ) {
                return nsSnackBar.error( __( 'Unable to hold an order which payment status has been updated already.' ) ).subscribe();
            }

            const queues    =   nsHooks.applyFilters( 'ns-hold-queue', [
                ProductsQueue,
                CustomerQueue,
                TypeQueue,
            ]);
            
            for( let index in queues ) {
                try {
                    const promise   =   new queues[ index ]( this.order );
                    const response  =   await promise.run();
                } catch( exception ) {
                    /**
                     * in case there is something broken
                     * on the promise, we just stop the queue.
                     */
                    return false;    
                }
            }

            /**
             * overriding hold popup
             * This will be useful to inject custom 
             * hold popup.
             */
            const popup     =   nsHooks.applyFilters( 'ns-override-hold-popup', () => {
                const promise   =   new Promise( ( resolve, reject ) => {
                    Popup.show( nsPosHoldOrdersPopupVue, { resolve, reject, order : this.order });
                });

                promise.then( result => {
                    this.order.title            =   result.title;
                    this.order.payment_status   =   'hold';
                    POS.order.next( this.order );

                    const popup     =   Popup.show( nsPosLoadingPopupVue );
                    
                    POS.submitOrder().then( result => {
                        popup.close();
                        POS.printOrderReceipt( result.data.order, 'silent' );
                        nsSnackBar.success( result.message ).subscribe();
                    }, ( error ) => {
                        popup.close();
                        POS.printOrderReceipt( result.data.order, 'silent' );
                        nsSnackBar.error( error.message ).subscribe();
                    });
                })
            });

            popup();
        },

        openDiscountPopup( reference, type ) {
            if ( ! this.settings.products_discount && type === 'product' ) {
                return nsSnackBar.error( __( `You're not allowed to add a discount on the product.` ) ).subscribe();
            }

            if ( ! this.settings.cart_discount && type === 'cart' ) {
                return nsSnackBar.error( __( `You're not allowed to add a discount on the cart.` ) ).subscribe();
            }

            Popup.show( nsPosDiscountPopupVue, { 
                reference,
                type,
                onSubmit( response ) {
                    if ( type === 'product' ) {
                        POS.updateProduct( reference, response );
                    } else if ( type === 'cart' ) {
                        POS.updateCart( reference, response );
                    }
                }
            }, {
                popupClass: 'bg-white h:2/3 shadow-lg xl:w-1/4 lg:w-2/5 md:w-2/3 w-full'
            })
        },

        selectCustomer() {
            Popup.show( nsPosCustomerPopupVue );
        },

        toggleMode( product ) {
            if ( ! this.options.ns_pos_allow_wholesale_price ) {
                return nsSnackBar.error( __( 'Unable to change the price mode. This feature has been disabled.' ) ).subscribe();
            }

            if ( product.mode === 'normal' ) {
                Popup.show( PosConfirmPopup, {
                    title: __( 'Enable WholeSale Price' ),
                    message: __( 'Would you like to switch to wholesale price ?' ),
                    onAction( action ) {
                        if ( action ) {
                            POS.updateProduct( product, { mode: 'wholesale' });
                        }
                    }
                });
            } else {
                Popup.show( PosConfirmPopup, {
                    title: __( 'Enable Normal Price' ),
                    message: __( 'Would you like to switch to normal price ?' ),
                    onAction( action ) {
                        if ( action ) {
                            POS.updateProduct( product, { mode: 'normal' });
                        }
                    }
                });
            }
        },
        remove( product ) {
            Popup.show( PosConfirmPopup, {
                title: __( 'Confirm Your Action' ),
                message: __( 'Would you like to delete this product ?' ),
                onAction( action ) {
                    if ( action ) {
                        POS.removeProduct( product );
                    }
                }
            });
        },

        allowQuantityModification( product ) {
            return product.product_type === 'product';
        },

        /**
         * This will use the previously used 
         * popup to run the promise.
         */
        changeQuantity( product ) {
            if ( this.allowQuantityModification( product ) ) {
                const quantityPromise   =   new ProductQuantityPromise( product );
                quantityPromise.run({ 
                    unit_quantity_id    : product.unit_quantity_id, 
                    unit_name           : product.unit_name, 
                    $quantities         : product.$quantities 
                }).then( result => {
                    POS.updateProduct( product, result );
                });
            }
        },

        plusQuantity( product ) {
            if ( this.allowQuantityModification( product ) ) {
                const result = product.quantity += 1;
                POS.updateProduct( product, result );
            }
        },

        minusQuantity( product ) {
            if (product.quantity > 1) {
                if ( this.allowQuantityModification( product ) ) {
                    const result = product.quantity -= 1;
                    POS.updateProduct( product, result );
                }
            }
        },

        async payOrder() {
            const queues    =   nsHooks.applyFilters( 'ns-pay-queue', [
                ProductsQueue,
                CustomerQueue,
                TypeQueue,
                PaymentQueue
            ]);

            for( let index in queues ) {
                try {
                    const promise   =   new queues[ index ]( this.order );
                    const response  =   await promise.run();
                } catch( exception ) {
                    /**
                     * in case there is something broken
                     * on the promise, we just stop the queue.
                     */
                    return false;    
                }
            }
        },

        openOrderType() {
            Popup.show( nsPosOrderTypePopupVue );
        },

        openShippingPopup() {
            Popup.show( nsPosShippingPopupVue );
        }
    }
}
</script>