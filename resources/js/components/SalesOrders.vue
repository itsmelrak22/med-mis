<template>
    <v-container fluid>
        <v-card>
            <v-toolbar elevation="4" >
                <v-container>SALES ORDER</v-container>
                <v-card-text>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        hide-details
                        outlined
                        dense
                    ></v-text-field>
                </v-card-text>
            </v-toolbar>
        </v-card>
        <v-card>
            <v-card-title>
                <v-btn
                    class="mx-1 my-1"
                    color="#34495E"
                    elevation="2"
                    raised
                    small
                    dark
                    @click="toggleSalesOrdersStore(true)"
                >                    
                Request Sales Order
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="salesOrdersHeaders"
                    :items="SALES_ORDER_REQUEST"
                    :search="search"
                >
                    <template v-slot:[`item.created_at`]="{ item }">
                        {{ getFormattedDate(item.created_at) }}
                    </template>
                    <template v-slot:[`item.salesOrders_actions`]="{ item }">
                        <v-tooltip top >
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                    v-bind="attrs"
                                    v-on="on"
                                    small
                                    class="mr-2"
                                    @click="generateOrderSlip(item.sales_order_requests_id)"
                                >
                                    mdi-eye
                                </v-icon>
                            </template>
                            <span>View OrderSlip</span>
                        </v-tooltip>
                    </template>
                    <template v-slot:[`item.status`]="{ item }">
                        <v-chip
                            class="ma-2"
                            color="orange"
                            text-color="white"
                            small
                            v-if="item.status == 'RETURN TO SELLER'"
                        >
                            RETURN TO SELLER
                        </v-chip>
                        <v-chip
                            class="ma-2"
                            color="red"
                            text-color="white"
                            small
                            v-else-if="item.status == 'CANCELLED'"
                        >
                        CANCELLED
                        </v-chip>
                        <v-chip
                            class="ma-2"
                            color="green"
                            text-color="white"
                            small
                            v-else-if="item.status == 'APPROVED'"
                        >
                        APPROVED
                        </v-chip>
                        <v-chip
                            class="ma-2"
                            text-color="white"
                            small
                            dark
                            v-else-if="item.status == 'PENDING'"
                        >
                        PENDING
                        </v-chip>
                </template>
                    <!-- <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="_getSalesOrderRequests"
                        >
                            Reset
                        </v-btn>
                    </template> -->
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Sales Orders Dialogs -->
        <!-- dialogSalesOrdersStore Start -->
        <v-dialog v-model="dialogSalesOrdersStore" max-width="400" persistent>
            <v-form id="SalesOrdersStore" ref="SalesOrdersStore" @submit.prevent="SalesOrdersStore">
                <v-card>
                    <v-card-title> 
                      <span class="overline">Create New Sales Order Request</span> 
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-card-text>
                                    
                                    <v-autocomplete
                                        v-model="tempData.supply_id"
                                        :items="SUPPLIES"
                                        item-value="id"
                                        item-text="name"
                                        outlined 
                                        dense 
                                        label="Supply Ordered"
                                        name="supply_id" 
                                        class="required"
                                        :rules="rules.uniqueData(SUPPLIERS)"
                                        @change="setSupplierInfo(tempData.supply_id)"
                                    > </v-autocomplete>

                                    <v-autocomplete
                                        v-model="tempData.supplier_id"
                                        :items="SUPPLIERS"
                                        item-text="name"
                                        item-value="id"
                                        outlined 
                                        label="Supplier"
                                        name="supplier_id" 
                                        class="required"
                                        clearable
                                        :rules="rules.required"
                                        dense
                                        :disabled="true"
                                    ></v-autocomplete>

                                    <v-autocomplete
                                        v-model="tempData.customer_id"
                                        :items="CUSTOMERS"
                                        item-value="id"
                                        item-text="name"
                                        outlined 
                                        dense 
                                        label="Customer"
                                        name="customer_id" 
                                        class="required"
                                        :disabled="!tempData.supply_id"
                                    > </v-autocomplete>

                                    <v-text-field 
                                        outlined 
                                        dense 
                                        label="Quantity"
                                        name="quantity" 
                                        class="required"
                                        type="number"
                                        :rules="[v => !isNaN(parseFloat(v)) && v > 0 || 'Input must be a number greater than 0']"
                                        :disabled="!tempData.supply_id"
                                    > </v-text-field>

                                    <input type="hidden" name="auth_id" :value="loggedInUser.id">
                                    <input type="hidden" name="stock_in_type" value="existing">
                                    <input type="hidden" name="supply_name" :value="tempData.name">

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn text @click="dialogSalesOrdersStore = false">Cancel</v-btn>
                                        <v-btn text type="submit">Submit</v-btn>
                                    </v-card-actions>
                                </v-card-text>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
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
                    value: 'sales_order_requests_id',
                },
                {
                    text: 'Ordered Supply',
                    align: 'start',
                    value: 'name',
                },
                {
                    text: 'Ordered Quantity',
                    align: 'start',
                    value: 'quantity',
                },
                {
                    text: 'Customer',
                    align: 'start',
                    value: 'customer_name',
                },
                {
                    text: 'Status',
                    align: 'start',
                    value: 'status',
                },
                {
                    text: 'Order Date',
                    align: 'start',
                    value: 'created_at',
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
            'SALES_ORDER_REQUEST',
            'SUPPLIERS',
            'rules',
            'loggedInUser',
            'SUPPLIES',
            'CUSTOMERS'

        ]),
    },
    methods: {
        ...mapActions([
            // ... (existing mapped actions) ...
            '_getSalesOrderRequests',
            '_getSuppliers',
            '_getSupplies',
            '_getCustomers'
        ]),
        setSupplierInfo(id){
            const supply = this.SUPPLIES.find(res => res.id == id);
            if(supply.id){
                this.tempData = supply
                this.tempData.supply_id = supply.id
                this.tempData.supplier_id = +this.tempData.supplier_id
            }
        },
        async initializeSalesOrders(){
            await this._getSalesOrderRequests()
            await this._getSuppliers()
            await this._getSupplies()
            await this._getCustomers()
        },
        SalesOrdersStore(){
            if(this.$refs.SalesOrdersStore.validate()){
                this.overlay = true;
                const myForm = document.getElementById('SalesOrdersStore');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: '/api/sales_order_request/store',
                    data: formdata
                }).then(() => {
                    this._getSalesOrderRequests();
                    this.$refs.SalesOrdersStore.reset()
                    this.dialogSalesOrdersStore = false;
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
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
        getFormattedDate(date) {
            let newDate = new Date(date);
            let year = newDate.getFullYear();
            let month = (1 + newDate.getMonth()).toString().padStart(2, '0');
            let day = newDate.getDate().toString().padStart(2, '0');
            let hours = newDate.getHours().toString().padStart(2, '0');
            let minutes = newDate.getMinutes().toString().padStart(2, '0');
            let seconds = newDate.getSeconds().toString().padStart(2, '0');

            return month + '/' + day + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;
        },
        generateOrderSlip(id){
            console.log('id', id)
            try {
                window.open(`/generate-order-slip-${id}`);
            } catch (error) {
                console.error(error);
            }
        }
        // ... (existing methods) ...
    },
    async mounted() {
       this.overlay = true;
        await this.initializeSalesOrders().then(() => {
            this.overlay = false
        })
    },
};
</script>
