<template>
    <v-container fluid>
        <v-card>
            <v-toolbar elevation="4" >
                <v-container>SUPPLY INVENTORY</v-container>
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
                    @click="toggleStore(true)"
                >
                    Request Supply Stock-In
                </v-btn>

            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="headers"
                    :items="SUPPLIES"
                    :search="search"
                >
                <template v-slot:[`item.created_at`]="{ item }">
                    {{ getFormattedDate(item.created_at) }}
                </template>
                <template v-slot:[`item.updated_at`]="{ item }">
                    {{ getFormattedDate(item.updated_at) }}
                </template>
                <!-- <template v-slot:[`item.actions`]="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="toggleUpdate(true , item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="toggleDelete(true , item)"
                    >
                        mdi-delete
                    </v-icon>
                </template> -->
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
        <!-- dialogStore Start -->
          <v-dialog v-model="dialogStore" max-width="400" persistent>
                <v-card>
                    <v-card-title> 
                      <span class="overline">Create New Supply</span> 
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-tabs v-model="tab" centered grow>
                                    <v-tab>New</v-tab>
                                    <v-tab>Existing</v-tab>
                                </v-tabs>
                                <v-tabs-items v-model="tab">
                                    <v-tab-item>
                                        <v-card-text>
                                            <v-form id="Store" ref="Store" @submit.prevent="Store">
                                                <v-text-field 
                                                    outlined 
                                                    dense 
                                                    label="Name"
                                                    name="name" 
                                                    class="required"
                                                    :rules="rules.uniqueData(SUPPLIERS)"
                                                > </v-text-field>

                                                <v-autocomplete
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
                                                ></v-autocomplete>

                                                <v-text-field 
                                                    outlined 
                                                    dense 
                                                    label="Quantity"
                                                    name="quantity" 
                                                    class="required"
                                                    type="number"
                                                    :rules="[v => !isNaN(parseFloat(v)) && v > 0 || 'Input must be a number greater than 0']"
                                                > </v-text-field>

                                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                                                <input type="hidden" name="stock_in_type" value="new">

                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text @click="dialogStore = false">Cancel</v-btn>
                                                    <v-btn text type="submit">Submit</v-btn>
                                                </v-card-actions>
                                            </v-form>
                                        </v-card-text>
                                    </v-tab-item>
                                    <v-tab-item>
                                        <v-card-text>
                                            <v-form id="StoreExist" ref="StoreExist" @submit.prevent="StoreExist">
                                                <v-autocomplete
                                                    v-model="tempData.id"
                                                    :items="SUPPLIES"
                                                    item-value="id"
                                                    item-text="name"
                                                    outlined 
                                                    dense 
                                                    label="Name"
                                                    name="id" 
                                                    class="required"
                                                    :rules="rules.uniqueData(SUPPLIERS)"
                                                    @change="setSupplierInfo(tempData.id)"
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

                                                <v-text-field 
                                                    outlined 
                                                    dense 
                                                    label="Quantity"
                                                    name="quantity" 
                                                    class="required"
                                                    type="number"
                                                    :rules="[v => !isNaN(parseFloat(v)) && v > 0 || 'Input must be a number greater than 0']"
                                                    :disabled="!tempData.id"
                                                > </v-text-field>

                                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                                                <input type="hidden" name="stock_in_type" value="existing">
                                                <input type="hidden" name="supply_name" :value="tempData.name">

                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn text @click="dialogStore = false">Cancel</v-btn>
                                                    <v-btn text type="submit">Submit</v-btn>
                                                </v-card-actions>
                                            </v-form>
                                        </v-card-text>
                                    </v-tab-item>
                                </v-tabs-items>
                                
                            </v-col>
                        </v-row>
                    </v-card-text>
                    
                </v-card>
          </v-dialog>
        <!-- dialogStore End -->

        <!-- dialogUpdate Start -->
        <v-dialog v-model="dialogUpdate" max-width="300" persistent>
            <v-form id="Update" ref="Update" @submit.prevent="Update">
                <v-card>
                    <v-card-title> <span class="overline">Update Supply</span> </v-card-title>
                        <v-card-text>
                            <v-row>
                              <v-col cols="12">
                                <v-text-field 
                                    v-model="tempData.name"
                                    outlined 
                                    dense 
                                    label="Name"
                                    name="name" 
                                    class="required"
                                    :rules="rules.uniqueDataEdit(SUPPLIERS, tempData.name)"
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.info"
                                    outlined 
                                    dense 
                                    label="Info"
                                    name="info" 
                                    class="required"
                                    :rules="rules.required"
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.address"
                                    outlined 
                                    dense 
                                    label="Address"
                                    name="address"
                                    class="required"
                                    :rules="rules.required"
                                > </v-text-field>
                                <v-text-field 
                                    v-model="tempData.phone"
                                    outlined 
                                    dense 
                                    label="Phone"
                                    name="phone" 
                                    class="required"
                                    :rules="rules.required"
                                > </v-text-field>
                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
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

        <!-- dialogDelete Start -->
        <v-dialog v-model="dialogDelete" max-width="300" persistent>
            <v-form id="Delete" ref="Delete" @submit.prevent="Delete">
                <v-card>
                    <v-card-title> <span class="overline">Delete Supply</span> </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12">
                                    <p class="overline">CONFIRM DELETE ROLE?</p>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text @click="dialogDelete = false">Cancel</v-btn>
                        <v-btn text type="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <!-- dialogDelete End -->

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
            dialogStore: false,
            dialogUpdate: false,
            dialogDelete: false,
            overlay: false,

            headers: [
                {
                    text: 'Name',
                    align: 'start',
                    value: 'name',
                },
                {
                    text: 'Stock Quantity',
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
                    text: 'Stock In Date',
                    align: 'start',
                    value: 'created_at',
                },
                {
                    text: 'Last Updated Date',
                    align: 'start',
                    value: 'updated_at',
                },
                // { text: 'Actions', value: 'actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            tab: 0
        }
    },

    computed:{
        ...mapState([
            'SUPPLIERS',
            'SUPPLIES',
            'rules',
            'loggedInUser'
        ]),
    },

    methods: {
      ...mapActions([
          '_getSuppliers',
          '_getSupplies',
          '_getPendingStockInRequest'
      ]),
      async initialize(){
          await this._getSuppliers()
          await this._getSupplies()
      },
      setSupplierInfo(id){
        const supply = this.SUPPLIES.find(res => res.id == id);
        if(supply.id){
            this.tempData = supply
            this.tempData.supplier_id = +this.tempData.supplier_id
        }
      },
      toggleStore(isShow){
        this.dialogStore = isShow;
      },
      toggleUpdate(isShow, object = {}){
          if( ! isShow ) {
              this.dialogUpdate = false;
              this.tempData = {};
              return;
          }
          if( ! Object.keys(object).length > 0 ) {
              console.log( 'toggleUpdate', 'no data' );
              return;
          }

			  this.dialogUpdate = isShow;
            this.tempData = {...object};
        },
        toggleDelete(isShow, object = {}){
            if( ! isShow ) {
                this.dialogDelete = false;
                this.tempData = {};
                return;
            }
            if( ! Object.keys(object).length > 0 ) {
                console.log( 'toggleDelete', 'no data' );
                return;
            }

			this.dialogDelete = isShow;
            this.tempData = {...object};
		},
        Store(){
            if(this.$refs.Store.validate()){
                this.overlay = true;
                const myForm = document.getElementById('Store');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: '/api/supply/store',
                    data: formdata
                }).then(() => {
                    this._getSupplies();
                    this._getPendingStockInRequest();
                    this.$refs.Store.reset()
                    this.toggleStore(false);
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
        },
        StoreExist(){
            if(this.$refs.StoreExist.validate()){
                this.overlay = true;
                const myForm = document.getElementById('StoreExist');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: `/api/supply/store/exist/${this.tempData.id}`,
                    data: formdata
                }).then(() => {
                    this._getSupplies();
                    this._getPendingStockInRequest();
                    this.$refs.StoreExist.reset()
                    this.toggleStore(false);
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
        },
        Update(){
            if(this.$refs.Update.validate()){
                this.overlay = true;
                const myForm = document.getElementById('Update');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: `/api/supply/update/${this.tempData.id}`,
                    data: formdata
                }).then(() => {
                    this._getSupplies();
                    this.$refs.Update.reset()
                    this.toggleUpdate(false);
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
        },
        Delete(){
            if(this.$refs.Delete.validate()){
                this.overlay = true;
                const myForm = document.getElementById('Delete');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: `/api/supply/delete/${this.tempData.id}`,
                    data: formdata
                }).then(() => {
                    this._getSupplies();
                    this.$refs.Delete.reset()
                    this.toggleDelete(false);
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                    this.overlay = false;
                })
            }
        },
        getFormattedDate(date) {
            let newDate = new Date(date);
            let year = newDate.getFullYear();
            let month = (1 + newDate.getMonth()).toString().padStart(2, '0');
            let day = newDate.getDate().toString().padStart(2, '0');
        
            return month + '/' + day + '/' + year;
        }
    },

    async mounted(){
        this.overlay = true;
        await this.initialize().then(() => {
            this.overlay = false
        })
    }

}
</script>