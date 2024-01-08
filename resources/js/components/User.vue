<template>
    <v-container fluid>
        <v-card>
            <v-toolbar elevation="4" >
                <v-container>USERS</v-container>
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
                    color="#34495E"
                    elevation="2"
                    raised
                    small
                    dark
                    @click="toggleStore(true)"
                >Add User</v-btn>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    height="60vh"
                    class="mainTable"
                    :headers="headers"
                    :items="USERS"
                    :search="search"
                >
                <template v-slot:[`item.actions`]="{ item }">
                    <v-icon
                        v-if="loggedInUser.is_super_admin || loggedInUser.is_admin"
                        small
                        class="mr-2"
                        @click="toggleUpdate(true , item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        v-if="loggedInUser.is_super_admin"
                        small
                        @click="toggleDelete(true , item)"
                    >
                        mdi-delete
                    </v-icon>
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
        <!-- dialogStore Start -->
          <v-dialog v-model="dialogStore" max-width="400" persistent>
            <v-form id="Store" ref="Store" @submit.prevent="Store">
                <v-card>
                    <v-card-title> 
                      <span class="overline">Create New User</span> 
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
                                    :rules="rules.uniqueData(USERS)"
                                > </v-text-field>

                                <v-checkbox
                                    v-model="checkbox"
                                    label="Admin"
                                    name="is_admin"
                                    hint="Give this user admin rights?"
                                    @click="toggleWarningDialog(true)"
                                    :value="checkbox"
                                ></v-checkbox>
                             

                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text @click="toggleStore(false)">Cancel</v-btn>
                        <v-btn text type="submit" >Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
          </v-dialog>
        <!-- dialogStore End -->

        <!-- dialogUpdate Start -->
        <v-dialog v-model="dialogUpdate" max-width="300" persistent>
            <v-form id="Update" ref="Update" @submit.prevent="Update">
                <v-card>
                    <v-card-title> <span class="overline">Update Supplier</span> </v-card-title>
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
                                    :rules="rules.uniqueDataEdit(USERS, tempData.name)"
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
                    <v-card-title> <span class="overline">Delete Supplier</span> </v-card-title>
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

        <!-- dialogWarning Start -->
        <v-dialog v-model="dialogWarning" max-width="400" persistent>
            <v-card>
                <v-card-title class="text-h5">
                    Warning!
                </v-card-title>

                <v-card-text>
                    Admin access allows critical system changes. It can lead to disruptions or potential misuse if in wrong hands. Only grant it to trustworthy and knowledgeable users. Always provide the least access necessary for a user’s duties.
                </v-card-text>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    color="green darken-1"
                    text
                    @click="dialogWarning = false"
                >
                    Disagree
                </v-btn>

                <v-btn
                    color="green darken-1"
                    text
                    @click="makeAdmin()"
                >
                    Agree
                </v-btn>
                </v-card-actions>
            </v-card>
          </v-dialog>
        <!-- dialogWarning End -->

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
                    text: 'Email',
                    align: 'start',
                    value: 'email',
                },
                {
                    text: 'Created',
                    align: 'start',
                    value: 'created_at',
                },
                {
                    text: 'Updated',
                    align: 'start',
                    value: 'updated_at',
                },
                { text: 'Actions', value: 'actions', sortable: false, width: "10%" },
            ],
            search: '',
            tempData: {},
            checkbox: false,
            dialogWarning: false
        }
    },

    computed:{
        ...mapState([
            'USERS',
            'rules',
            'loggedInUser'
        ]),

    },

    methods: {
        ...mapActions([
            '_getUsers'
        ]),
        async initialize(){
            await this._getUsers()
        },
        makeAdmin(){
            this.$nextTick( () => {
                this.dialogWarning = false, 
                this.checkbox = true;
            } )
        },
        toggleWarningDialog(isShow){
            
            this.$nextTick( () => {
                this.checkbox = false;
                this.dialogWarning = isShow
            } )
            
        },
        toggleStore(isShow){
            if(isShow){
			    this.dialogStore = isShow;
            }else{
                this.$refs.Store.reset();
			    this.dialogStore = false;
            }
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
                    url: '/api/users',
                    data: formdata
                }).then(() => {
                    this._getUsers();
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
        Update(){
            if(this.$refs.Update.validate()){
                this.overlay = true;
                const myForm = document.getElementById('Update');
                const formdata = new FormData(myForm);

                axios({
                    method: 'POST',
                    url: `/api/supplier/update/${this.tempData.id}`,
                    data: formdata
                }).then(() => {
                    this._getUsers();
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
                    url: `/api/supplier/delete/${this.tempData.id}`,
                    data: formdata
                }).then(() => {
                    this._getUsers();
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
    },

    async mounted(){
        this.overlay = true;
        await this._getUsers().then(() => {
            this.overlay = false
        })
    }

}
</script>