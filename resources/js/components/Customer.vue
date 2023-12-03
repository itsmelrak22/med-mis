<template>
    <v-container fluid>
        <!-- ... (existing code) ... -->

        <!-- Add Customer -->
        <v-card>
            <v-card-title>
                <v-btn
                    color="#34495E"
                    elevation="2"
                    raised
                    small
                    dark
                    @click="toggleCustomerStore(true)"
                >Add Customer</v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="customerHeaders"
                    :items="CUSTOMERS"
                    :search="search"
                >
                    <template v-slot:[`item.customer_actions`]="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="toggleCustomerUpdate(true , item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="toggleCustomerDelete(true , item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="initializeCustomers"
                        >
                            Reset
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- Customer Dialogs -->
        <!-- dialogCustomerStore Start -->
        <v-dialog v-model="dialogCustomerStore" max-width="400" persistent>
            <v-form id="CustomerStore" ref="CustomerStore" @submit.prevent="storeCustomer">
                <v-card>
                    <v-card-title> 
                      <span class="overline">Create New Customer Data</span> 
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field 
                                    outlined 
                                    dense 
                                    label="Name"
                                    name="name" 
                                    class="required"
                                    :rules="rules.required"
                                > </v-text-field>
                                <v-text-field 
                                    outlined 
                                    dense 
                                    label="Email"
                                    name="email" 
                                    class="required"
                                    :rules="rules.uniqueData(CUSTOMERS)"
                                > </v-text-field>
                                <v-text-field 
                                    outlined 
                                    dense 
                                    label="Phone"
                                    name="phone" 
                                > </v-text-field>
                                <v-text-field 
                                    outlined 
                                    dense 
                                    label="Address"
                                    name="address" 
                                > </v-text-field>

                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text @click="dialogCustomerStore = false">Cancel</v-btn>
                        <v-btn text type="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <!-- dialogCustomerStore End -->

        <!-- dialogCustomerUpdate Start -->
        <v-dialog v-model="dialogCustomerUpdate" max-width="300" persistent>
            <v-form id="CustomerUpdate" ref="CustomerUpdate" @submit.prevent="updateCustomer">
                <!-- ... (existing customer update form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogCustomerUpdate End -->

        <!-- dialogCustomerDelete Start -->
        <v-dialog v-model="dialogCustomerDelete" max-width="300" persistent>
            <v-form id="CustomerDelete" ref="CustomerDelete" @submit.prevent="deleteCustomer">
                <!-- ... (existing customer delete form code) ... -->
            </v-form>
        </v-dialog>
        <!-- dialogCustomerDelete End -->

        <!-- ... (existing code) ... -->
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
    data() {
        return {
            // ... (existing data properties) ...
            dialogCustomerStore: false,
            dialogCustomerUpdate: false,
            dialogCustomerDelete: false,
            customerHeaders: [
                {
                    text: 'Name',
                    align: 'start',
                    value: 'name',
                },
                {
                    text: 'Email',
                    align: 'start',
                    value: 'email',
                },
                {
                    text: 'Phone',
                    align: 'start',
                    value: 'phone',
                },
                { text: 'Actions', value: 'customer_actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            overlay: false,
        };
    },
    computed: {
        ...mapState([
            // ... (existing mapped states) ...
            'CUSTOMERS',
            'rules',
            'loggedInUser'
        ]),
    },
    methods: {
        ...mapActions([
            // ... (existing mapped actions) ...
            '_getCustomers',
        ]),
        async initializeCustomers(){
            await this._getCustomers()
        },
        // ... (existing methods) ...
        toggleCustomerStore(isShow) {
            this.dialogCustomerStore = isShow;
        },
        toggleCustomerUpdate(isShow, object = {}) {
            // ... (existing code) ...
        },
        toggleCustomerDelete(isShow, object = {}) {
            // ... (existing code) ...
        },
        storeCustomer() {
            // ... (existing code) ...
        },
        updateCustomer() {
            // ... (existing code) ...
        },
        deleteCustomer() {
            // ... (existing code) ...
        },
        // ... (existing methods) ...
    },
    async mounted() {
        this.overlay = true;
        await this._getCustomers().then(() => {
            this.overlay = false;
        });
    },
};
</script>
