<template>
    <v-container fluid>
        <!-- ... (existing code) ... -->

        <!-- Add Order Details -->
        <v-card>
            <v-card-title>
                <v-btn
                    color="#34495E"
                    elevation="2"
                    raised
                    small
                    dark
                    @click="toggleOrderDetailsStore(true)"
                >Add Order Details</v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="orderDetailsHeaders"
                    :items="ORDER_DETAILS"
                    :search="search"
                >
                    <template v-slot:[`item.orderDetails_actions`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="toggleOrderDetailsUpdate(true , item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="toggleOrderDetailsDelete(true , item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="initializeOrderDetails"
                        >
                            Reset
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Order Details Dialogs -->
        <!-- dialogOrderDetailsStore Start -->
        <v-dialog v-model="dialogOrderDetailsStore" max-width="400" persistent>
            <v-form id="OrderDetailsStore" ref="OrderDetailsStore" @submit.prevent="storeOrderDetails">
                <!-- ... (existing order_details form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogOrderDetailsStore End -->

        <!-- dialogOrderDetailsUpdate Start -->
        <v-dialog v-model="dialogOrderDetailsUpdate" max-width="300" persistent>
            <v-form id="OrderDetailsUpdate" ref="OrderDetailsUpdate" @submit.prevent="updateOrderDetails">
                <!-- ... (existing order_details update form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogOrderDetailsUpdate End -->

        <!-- dialogOrderDetailsDelete Start -->
        <v-dialog v-model="dialogOrderDetailsDelete" max-width="300" persistent>
            <v-form id="OrderDetailsDelete" ref="OrderDetailsDelete" @submit.prevent="deleteOrderDetails">
                <!-- ... (existing order_details delete form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogOrderDetailsDelete End -->

        <!-- ... (existing code) ... -->
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
    data() {
        return {
            // ... (existing data properties) ...
            dialogOrderDetailsStore: false,
            dialogOrderDetailsUpdate: false,
            dialogOrderDetailsDelete: false,
            orderDetailsHeaders: [
                {
                    text: 'Product',
                    align: 'start',
                    value: 'product',
                },
                {
                    text: 'Quantity',
                    align: 'start',
                    value: 'quantity',
                },
                {
                    text: 'Unit Price',
                    align: 'start',
                    value: 'unit_price',
                },
                { text: 'Actions', value: 'orderDetails_actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            overlay: false,

        };
    },
    computed: {
        ...mapState([
            // ... (existing mapped states) ...
            'ORDER_DETAILS',
        ]),
    },
    methods: {
        ...mapActions([
            // ... (existing mapped actions) ...
            '_getOrderDetails',
        ]),
        async initializeOrderDetails(){
            await this._getOrderDetails()
        },
        // ... (existing methods) ...
        toggleOrderDetailsStore(isShow) {
            this.dialogOrderDetailsStore = isShow;
        },
        toggleOrderDetailsUpdate(isShow, object = {}) {
            // ... (existing code) ...
        },
        toggleOrderDetailsDelete(isShow, object = {}) {
            // ... (existing code) ...
        },
        storeOrderDetails() {
            // ... (existing code) ...
        },
        updateOrderDetails() {
            // ... (existing code) ...
        },
        deleteOrderDetails() {
            // ... (existing code) ...
        },
        // ... (existing methods) ...
    },
    async mounted() {
        this.overlay = true;
        await this._getOrderDetails().then(() => {
            this.overlay = false;
        });
    },
};
</script>
