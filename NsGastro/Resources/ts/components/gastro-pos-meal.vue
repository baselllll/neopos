<template>
    <div class="shadow-lg w-95vw md:w-3/5-screen lg:w-2/5-screen bg-white">
        <div class="p-2 flex justify-between border-b items-center">
            <h3 class="w-full">
                <span>{{ __m( 'Meal Status: ', 'NsGastro' ) }}</span>
                <span v-if="product">{{ product.name }}</span>
            </h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <div>
            <div class="go-grid go-grid-cols-2 go-text-gray-700">
                <div @click="printKitchen()" class="cursor-pointer hover:go-bg-blue-400 hover:go-text-white go-border go-border-gray-100 go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-print"></i>
                    <span class="font-bold">{{ __m( 'Print', 'NsGastro' ) }}</span>
                </div>
                <div @click="cancelMeal()" class="cursor-pointer hover:go-bg-blue-400 hover:go-text-white go-border go-border-gray-100 go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-ban"></i>
                    <span class="font-bold">{{ __m( 'Cancel', 'NsGastro' ) }}</span>
                </div>
                <div @click="addProductNote()" class="cursor-pointer hover:go-bg-blue-400 hover:go-text-white go-border go-border-gray-100 go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-comment-alt"></i>
                    <span class="font-bold">{{ __m( 'Note', 'NsGastro' ) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'gastro-pos-meal',
    mounted() {
        this.product    =   this.$popupParams.product;

        this.$popup.event.subscribe( action => {
            if ( action.event === 'click-overlay' ) {
                this.closePopup();
            }
        });
    },
    data() {
        return {
            product: null
        }
    },
    methods: {
        __m,
        closePopup() {
            this.$popupParams.reject( false );
            this.$popup.close();
        },
        printKitchen() {
            const product   =   this.$popupParams.product;

            if ([ 'pending', 'ongoing' ].includes( product.cooking_status ) && product.id !== undefined ) {

            }

            nsSnackBar.error( 'Unable to print a meal that is not yet send at the kitchen or which is already cooked.' ).subscribe();
        },
        cancelMeal() {
            const product   =   this.$popupParams.product;

            if ([ 'pending', 'ongoing' ].includes( product.cooking_status ) && product.id !== undefined ) {

            }

            nsSnackBar.error( 'Unable to cancel a meal that is not send to the kitchen or which is already cookied.' ).subscribe();
        },
        async addProductNote() {
            try {
                const result    =   await new Promise( ( resolve, reject ) => {
                    Popup.show( nsPromptPopup, { 
                        resolve, 
                        reject, 
                        input: this.$popupParams.product.cooking_note,
                        title : 'Meal Note',
                        message: 'The following note will be visible at the kitchen and on the kitchen slip.',
                        onAction: ( output ) => {
                            resolve( output );
                        }
                    })
                });

                if ( result !== false ) {
                    this.$popupParams.product.cooking_note  =   result;
                }

                this.closePopup();
            } catch( exception ) {
                console.log( exception );
            }
        }
    }
}
</script>