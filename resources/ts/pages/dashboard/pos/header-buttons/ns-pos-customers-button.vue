<script>
import { Popup } from '@/libraries/popup';
import { default as nsPosCustomers } from '@/popups/ns-pos-customers';
import { __ } from '@/libraries/lang';

export default {
    name: 'ns-pos-customers-button',
    methods: {
        __,
        openCustomerPopup() {
            const popup     =   new Popup;
            popup.open( nsPosCustomers );
        }
    },
    beforeDestroy() {
        nsHotPress.destroy( 'ns_pos_keyboard_create_customer' );
    },
    mounted() {
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
                    'ns_pos_keyboard_create_customer', 
                ].includes( shortcut ) ) {
                nsHotPress
                    .create( 'ns_pos_keyboard_create_customer' )
                    .whenNotVisible([ '.is-popup' ])
                    .whenPressed( nsShortcuts[ shortcut ], ( event ) => {
                        event.preventDefault();
                        this.openCustomerPopup();
                });
            }
        }
    }
}
</script>
<template>
    <div class="ns-button default">
        <button @click="openCustomerPopup()" class="rounded shadow tab-buttons flex-shrink-0 h-12 flex items-center px-2 py-1 text-sm">
            <img src="/images/customers.png" class="mr-2" alt="">
            <span>{{ __( 'Customers' ) }}</span>
        </button>
    </div>
</template>