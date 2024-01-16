<template>
    <v-container fluid>
        <v-card>
            <v-toolbar elevation="4" >
                <v-container>SALES ORDER REQUEST</v-container>
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
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="headers"
                    :items="SALES_ORDER_REQUEST"
                    :search="search"
                >
                <template v-slot:[`item.created_at`]="{ item }">
                    {{ getFormattedDate(item.created_at) }}
                </template>
                <template v-slot:[`item.updated_at`]="{ item }">
                    {{ getFormattedDate(item.updated_at) }}
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
                <template v-slot:[`item.actions`]="{ item }">
                    <v-tooltip top v-if="item.status == 'PENDING'">
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                                v-bind="attrs"
                                v-on="on"
                                small
                                class="mr-2"
                                @click="toggleUpdate(true , item)"
                            >
                            mdi-checkbox-marked
                            </v-icon>
                        </template>
                        <span>Update Status</span>
                    </v-tooltip>
                    <v-tooltip top v-if="loggedInUser.is_super_admin || loggedInUser.is_super_admin">
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                                v-bind="attrs"
                                v-on="on"
                                small
                                class="mr-2"
                                @click="toggleUpdateInfo(true , item)"
                            >
                                mdi-pencil-box
                            </v-icon>
                        </template>
                        <span>Update Info</span>
                    </v-tooltip>
                </template>
                    <template v-slot:no-data>
                    <v-btn
                        color="primary"
                        @click="initialize"
                    >
                        Reset
                    </v-btn>
                </template>
                </v-data-table>
            </v-card-text>
        </v-card>


<!-- ################################# DIALOGS #################################-->
        <!-- dialogUpdate Start -->
        <v-dialog v-model="dialogUpdate" max-width="400" persistent>
            <v-form id="Update" ref="Update" @submit.prevent="Update">
                <v-card>
                    <v-card-title> <span class="overline">Sales Order Request Request</span> </v-card-title>
                        <v-card-text>
                            <v-row>
                              <v-col cols="12">
                                <v-text-field 
                                    v-model="tempData.name"
                                    outlined 
                                    dense 
                                    label="Name"
                                    name="name" 
                                    readonly
                                > </v-text-field>
                                
                                <v-text-field 
                                    v-model="tempData.quantity"
                                    outlined 
                                    dense 
                                    label="Sale Order Quantity"
                                    name="quantity" 
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.supplier"
                                    outlined 
                                    dense 
                                    label="Supplier"
                                    name="supplier"
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.requested_by"
                                    outlined 
                                    dense 
                                    label="Reqeuested By"
                                    name="requested_by"
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.requested_date"
                                    outlined 
                                    dense 
                                    label="Requested Date"
                                    name="requested_date" 
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="itemInStockQty"
                                    outlined 
                                    dense 
                                    label="Item in Stock Quantity"
                                    readonly
                                > </v-text-field>
                                <v-autocomplete
                                    :items="['APPROVED', 'CANCELLED']"
                                    outlined 
                                    label="Status"
                                    name="status" 
                                    class="required"
                                    clearable
                                    :rules="rules.required"
                                    dense
                                ></v-autocomplete>
                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                                <input type="hidden" name="supply_id" :value="tempData.supply_id">
                                <input type="hidden" name="sales_order_request_details_id" :value="tempData.sales_order_request_details_id">
                                <input type="hidden" name="sales_order_requests_id" :value="tempData.sales_order_requests_id">
                              </v-col>
                            </v-row>
                        </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text @click="dialogUpdate = false">Cancel</v-btn>
                        <v-btn text type="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <!-- dialogUpdate End -->

        <!-- dialogUpdateInfo Start -->
        <v-dialog v-model="dialogUpdateInfo" max-width="400" persistent>
            <v-form id="Update" ref="Update" @submit.prevent="Update">
                <v-card>
                    <v-card-title> <span class="overline">Sales Order Request Request</span> </v-card-title>
                        <v-card-text>
                            <v-row>
                              <v-col cols="12">
                                <v-text-field 
                                    v-model="tempData.name"
                                    outlined 
                                    dense 
                                    label="Name"
                                    name="name" 
                                    readonly
                                > </v-text-field>
                                
                                <v-text-field 
                                    v-model="tempData.quantity"
                                    outlined 
                                    dense 
                                    label="Sale Order Quantity"
                                    name="quantity" 
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.supplier"
                                    outlined 
                                    dense 
                                    label="Supplier"
                                    name="supplier"
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.requested_by"
                                    outlined 
                                    dense 
                                    label="Reqeuested By"
                                    name="requested_by"
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.requested_date"
                                    outlined 
                                    dense 
                                    label="Requested Date"
                                    name="requested_date" 
                                    readonly
                                > </v-text-field>
                                <v-text-field 
                                    v-model="itemInStockQty"
                                    outlined 
                                    dense 
                                    label="Item in Stock Quantity"
                                    readonly
                                > </v-text-field>
                                <v-autocomplete
                                    v-model="tempData.status"
                                    :items="['APPROVED', 'CANCELLED', 'RETURN TO SELLER']"
                                    outlined 
                                    label="Status"
                                    name="status" 
                                    class="required"
                                    clearable
                                    :rules="rules.required"
                                    dense
                                ></v-autocomplete>
                                <input type="hidden" name="is_editted" :value="true">
                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                                <input type="hidden" name="supply_id" :value="tempData.supply_id">
                                <input type="hidden" name="sales_order_request_details_id" :value="tempData.sales_order_request_details_id">
                                <input type="hidden" name="sales_order_requests_id" :value="tempData.sales_order_requests_id">
                              </v-col>
                            </v-row>
                        </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text @click="dialogUpdateInfo = false">Cancel</v-btn>
                        <v-btn text type="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <!-- dialogUpdate End -->

   

<!-- ################################# OVERLAY #################################-->
        <v-overlay :value="overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
    sockets: {

    },
    data(){
        return {
            dialogView: false,
            dialogUpdate: false,
            dialogUpdateInfo: false,
            overlay: false,

            headers: [
                {
                    text: 'Customer',
                    align: 'start',
                    value: 'customer_name',
                },
                {
                    text: 'Supply',
                    align: 'start',
                    value: 'name',
                },
                {
                    text: 'Sale Quantity',
                    align: 'start',
                    value: 'quantity',
                },
                {
                    text: 'Supplier',
                    align: 'start',
                    value: 'supplier',
                },
                {
                    text: 'Status',
                    align: 'start',
                    value: 'status',
                },
                {
                    text: 'Sales Order Request Date',
                    align: 'start',
                    value: 'created_at',
                },
                // {
                //     text: 'Requested By',
                //     align: 'start',
                //     value: 'requested_by',
                // },
                // {
                //     text: 'Last Updated Date',
                //     align: 'start',
                //     value: 'updated_at',
                // },
                { text: 'Actions', value: 'actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            tab: 0,
            itemInStockQty: 0
        }
    },

    computed:{
        ...mapState([
            'SUPPLIERS',
            'SUPPLIES',
            'rules',
            'loggedInUser',
            'SALES_ORDER_REQUEST'
        ]),
    },

    methods: {
        ...mapActions([
            '_getSuppliers',
            '_getSupplies',
            '_getSalesOrderRequests',
        ]),
        async initialize(){
            await this._getSalesOrderRequests()
            await this._getSuppliers()
            await this._getSupplies()
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
        async toggleUpdate(isShow, object = {}){
            if( ! isShow ) {
                this.dialogUpdate = false;
                this.tempData = {};
                return;
            }
            if( ! Object.keys(object).length > 0 ) {
                console.log( 'toggleUpdate', 'no data' );
                return;
            }

            await this._getSupplies()

            this.dialogUpdate = isShow;
            this.tempData = {...object};
            const itemInStock = this.SUPPLIES.find(res => res.id == this.tempData.supply_id);
            if(itemInStock.id){
                this.itemInStockQty = itemInStock.quantity
            }
        },
        async toggleUpdateInfo(isShow, object = {}){
            if( ! isShow ) {
                this.dialogUpdateInfo = false;
                this.tempData = {};
                return;
            }
            if( ! Object.keys(object).length > 0 ) {
                console.log( 'toggleUpdateInfo', 'no data' );
                return;
            }

            await this._getSupplies()

            this.dialogUpdateInfo = isShow;
            this.tempData = {...object};
            const itemInStock = this.SUPPLIES.find(res => res.id == this.tempData.supply_id);
            if(itemInStock.id){
                this.itemInStockQty = itemInStock.quantity
            }

        },
        toggleView(isShow, object = {}){
          if( ! isShow ) {
              this.dialogView = false;
              this.tempData = {};
              return;
          }
          if( ! Object.keys(object).length > 0 ) {
              return;
          }

            this.dialogView = isShow;
            this.tempData = {...object};
        },
        Update(){
            if(this.$refs.Update.validate()){
                this.overlay = true;
                const myForm = document.getElementById('Update');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: `/api/sales_order_request/update/${this.tempData.sales_order_requests_id}`,
                    data: formdata
                }).then((res) => {
                    if(res.data == 'out_of_stock'){
                        alert('Insuficient Quantity!')
                        return;
                    }
                    this._getSalesOrderRequests();
                    this.$refs.Update.reset()
                    this.toggleUpdate(false);
                    this.toggleUpdateInfo(false);
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
        },
    },

    async mounted(){
        this.overlay = true;
        await this.initialize().then(() => {
            this.overlay = false
        })
    }

}
</script>