<template>
    <div class="h-16 flex flex-shrink-0 border-t border-gray-200" id="gastro-add-buttons">
        <div @click="submitAddToOrder()" id="kitchen-button" class="flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center bg-green-500 text-white hover:bg-green-600 border-r border-green-600 flex-auto">
            <i class="mr-2 text-xl lg:text-3xl las la-paper-plane"></i>
            <span class="text-lg lg:text-2xl">{{ __m( 'To Kitchen' ) }}</span>
        </div>
        <div @click="cancelAddToOrder()" id="hold-button" class="flex-shrink-0 w-1/4 flex items-center font-bold cursor-pointer justify-center bg-red-500 text-white border-r hover:bg-red-600 border-red-600 flex-auto">
            <i class="mr-2 text-xl lg:text-3xl las la-times"></i>
            <span class="text-lg lg:text-2xl">{{ __m( 'Cancel', 'NsGastro' ) }}</span>
        </div>
    </div>
</template>
<script>
export default {
    name: '',
    data() {
        return {
            selectedOrder: null,
            subscription: null
        }
    },
    mounted() {
        this.subscription   =   Gastro.selectedOrder.subscribe( order => {
            this.selectedOrder  =   order;
        });
    },
    methods: {
        __m,
        cancelAddToOrder() {
            Gastro.selectedOrder.next({});
            Gastro.setAddButtonsVisibility( 'hidden' );
            POS.reset();
        },
        submitAddToOrder() {
            const products  =   POS.products.getValue();

            if ( products.length === 0 ) {
                return nsSnackBar.error( __m( 'Unable to submit if the cart is empty.', 'NsGastro' ), null, { duration: 4000 }).subscribe();
            }

            Popup.show( nsConfirmPopup, {
                title: __m( 'Confirm Your Action', 'NsGastro' ),
                message: __m( 'Would you like to add {products} items to the order {order}', 'NsGastro' )
                    .replace( '{products}', products.length )
                    .replace( '{order}', this.selectedOrder.code ),
                onAction: ( action ) => {
                    if ( action ) {
                        nsHttpClient.post( `/api/nexopos/v4/gastro/orders/${this.selectedOrder.id}/add-products`, { products })
                            .subscribe( result => {
                                nsSnackBar.success( result.message, 'OK', { duration : 3000 }).subscribe();

                                /**
                                 * We'll print only the new
                                 * item that has been added to the order
                                 */
                                if ( GastroSettings.ns_gastro_kitchen_print_gateway === 'local_print' ) {
                                    Gastro.printOrderToKichen( this.selectedOrder.id, result.data.orderProducts.map( product => product.id ) );
                                }

                                nsHooks.doAction( 'ns-gastro-after-add-products', {
                                    order: this.selectedOrder,
                                    products: result.data.orderProducts
                                });
                                
                                this.cancelAddToOrder();
                            }, ( error ) => {
                                nsSnackBar.error( error.message || __m( 'An unexpected error occured', 'NsGastro' ), 'OK', { duration: 0 }).subscribe();
                            })
                    }
                }
            })
        }
    }
}
</script>