<script>
import { Popup } from '@/libraries/popup';
import nsPosPendingOrdersPopup from '@/popups/ns-pos-pending-orders-popup';
import { __ } from '@/libraries/lang';

export default {
    name: 'ns-pos-pending-orders-button',
    methods: {
        __,

        openPendingOrdersPopup() {
            const popup     =   new Popup;
            popup.open( nsPosPendingOrdersPopup );
        }
    },
}
</script>
<template>
    <div class="ns-button default">
        <button @click="openPendingOrdersPopup()" class="rounded tab-buttons shadow flex-shrink-0 h-12 flex items-center px-2 py-1 text-sm">
            <img src="/images/orders.png" class="mr-2" alt="">
            <span>{{ __( 'Orders' ) }}</span>
        </button>
    </div>
</template>