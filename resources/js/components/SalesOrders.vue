<template>
    <v-container fluid>
        <!-- ... (existing code) ... -->

        <!-- Add Sales Orders -->
        <v-card>
            <v-card-title>
                <v-btn
                    color="#34495E"
                    elevation="2"
                    raised
                    small
                    dark
                    @click="toggleSalesOrdersStore(true)"
                >Add Sales Order</v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="salesOrdersHeaders"
                    :items="SALES_ORDERS"
                    :search="search"
                >
                    <template v-slot:[`item.salesOrders_actions`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="toggleSalesOrdersUpdate(true , item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="toggleSalesOrdersDelete(true , item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="initializeSalesOrders"
                        >
                            Reset
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Sales Orders Dialogs -->
        <!-- dialogSalesOrdersStore Start -->
        <v-dialog v-model="dialogSalesOrdersStore" max-width="400" persistent>
            <v-form id="SalesOrdersStore" ref="SalesOrdersStore" @submit.prevent="storeSalesOrders">
                <!-- ... (existing sales_orders form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogSalesOrdersStore End -->

        <!-- dialogSalesOrdersUpdate Start -->
        <v-dialog v-model="dialogSalesOrdersUpdate" max-width="300" persistent>
            <v-form id="SalesOrdersUpdate" ref="SalesOrdersUpdate" @submit.prevent="updateSalesOrders">
                <!-- ... (existing sales_orders update form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogSalesOrdersUpdate End -->

        <!-- dialogSalesOrdersDelete Start -->
        <v-dialog v-model="dialogSalesOrdersDelete" max-width="300" persistent>
            <v-form id="SalesOrdersDelete" ref="SalesOrdersDelete" @submit.prevent="deleteSalesOrders">
                <!-- ... (existing sales_orders delete form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogSalesOrdersDelete End -->

        <!-- ... (existing code) ... -->
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
    data() {
        return {
            // ... (existing data properties) ...
            dialogSalesOrdersStore: false,
            dialogSalesOrdersUpdate: false,
            dialogSalesOrdersDelete: false,
            salesOrdersHeaders: [
                {
                    text: 'Order Number',
                    align: 'start',
                    value: 'order_number',
                },
                {
                    text: 'Customer',
                    align: 'start',
                    value: 'customer',
                },
                {
                    text: 'Order Date',
                    align: 'start',
                    value: 'order_date',
                },
                { text: 'Actions', value: 'salesOrders_actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            overlay: false,

        };
    },
    computed: {
        ...mapState([
            // ... (existing mapped states) ...
            'SALES_ORDERS',
        ]),
    },
    methods: {
        ...mapActions([
            // ... (existing mapped actions) ...
            '_getSalesOrders',
        ]),
        async initializeSalesOrders(){
            await this._getSalesOrders()
        },
        // ... (existing methods) ...
        toggleSalesOrdersStore(isShow) {
            this.dialogSalesOrdersStore = isShow;
        },
        toggleSalesOrdersUpdate(isShow, object = {}) {
            // ... (existing code) ...
        },
        toggleSalesOrdersDelete(isShow, object = {}) {
            // ... (existing code) ...
        },
        storeSalesOrders() {
            // ... (existing code) ...
        },
        updateSalesOrders() {
            // ... (existing code) ...
        },
        deleteSalesOrders() {
            // ... (existing code) ...
        },
        // ... (existing methods) ...
    },
    async mounted() {
        this.overlay = true;
        await this._getSalesOrders().then(() => {
            this.overlay = false;
        });
    },
};
</script>
