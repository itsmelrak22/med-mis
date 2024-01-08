<template>
  <v-app id="inspire">
      <v-navigation-drawer
        v-model="drawer"
        app
      >
         <v-list dense>
            <v-subheader>LIST COMPONENTS:</v-subheader>
               <v-list-item
                  v-for="(item, i) in items"
                  :key="i"
                  :to="item.to"
               >
                  <v-list-item-icon>
                     <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                     <v-list-item-title>
                           <v-badge v-if="item.to == 'stock_in_request' && PENDING_STOCK_IN_REQUEST.length" color="green" :content="PENDING_STOCK_IN_REQUEST.length"> <div>{{ item.text }}</div> </v-badge> 
                        <div v-else color="white">{{ item.text }} </div> 
                     </v-list-item-title>
                  </v-list-item-content>
               </v-list-item>
         </v-list>
         <v-list dense>
            <v-divider></v-divider>
            <v-subheader>ADMIN ACCESS:</v-subheader>
               <v-list-item
                  v-for="(item, i) in adminItems"
                  :key="i"
                  :to="item.to"
               >
                  <v-list-item-icon>
                     <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                     <v-list-item-title>
                           <v-badge v-if="item.to == 'stock_in_request' && PENDING_STOCK_IN_REQUEST.length" color="green" :content="PENDING_STOCK_IN_REQUEST.length"> <div>{{ item.text }}</div> </v-badge> 
                        <div v-else color="white">{{ item.text }} </div> 
                     </v-list-item-title>
                  </v-list-item-content>
               </v-list-item>
         </v-list>
      </v-navigation-drawer>

      <v-app-bar app>
         <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
         <v-toolbar-title>MCCBIOTECH MIS</v-toolbar-title>
         <v-spacer></v-spacer>
         <span class="overline">{{loggedInUser.email}}</span>
         <v-tooltip bottom>
            <template v-slot:activator="{ on : tooltip }">
            <v-menu offset-y max-width="300">
            <template v-slot:activator="{ on : menu, attrs }">
               <v-btn
                  icon
                  v-bind="attrs"
                  v-on="{ ...tooltip, ...menu }"
               >
                  <v-icon>mdi-account-circle</v-icon>
               </v-btn>
            </template>
            <v-list dense>
                  <v-subheader>ACCOUNT</v-subheader>
                  <v-list-item-group>
                  <v-list-item >
                     <v-list-item-content>
                        <v-form id="logOut" method="POST" action="logout" >
                           <input type="hidden" name="_token" :value="csrf">
                           <input id="logoutButton" type="submit" value="Logout" depressed class="caption" > <v-icon>mdi-logout</v-icon>
                        </v-form>
                     </v-list-item-content>
                  </v-list-item>
                  </v-list-item-group>
            </v-list>
            </v-menu>
            </template>
            <span>Menu</span>
         </v-tooltip>
      </v-app-bar>

      <v-main class="ma-2">
         <router-view></router-view>
      </v-main>

      <!-- dialogChangePassword Start -->
         <v-dialog v-model="dialogChangePassword" max-width="400" persistent>
            <v-form id="ChangePassword" ref="ChangePassword" @submit.prevent="ChangePassword">
                <v-card>
                    <v-card-title> 
                      <span class="overline">Reset Password</span> 
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field 
                                    v-model="userPassword.password"
                                    id="hiddenPassword"
                                    label="Password"
                                    autocomplete="off"
                                    prepend-icon="mdi-lock" 
                                    :type="showPassword ? 'text' : 'password' "
                                    :append-icon="showPassword ? 'mdi-eye': 'mdi-eye-off'"
                                    @click:append="showPassword = !showPassword"
                                    @focus="$event.target.removeAttribute('readonly');"
                                    required
                                    readonly
                                    outlined
                                    dense
                                    class="required"
                                    name="password"
                                    :rules="rules.password"
                                > </v-text-field>
                                <v-text-field 
                                    v-model="userPassword.confirmPassword"
                                    outlined 
                                    dense 
                                    label="Confirm Password"
                                    name="confirm_password" 
                                    class="required"
                                    autocomplete="off"
                                    prepend-icon="mdi-lock" 
                                    :type="showConfirmPassword ? 'text' : 'password' "
                                    :append-icon="showConfirmPassword ? 'mdi-eye': 'mdi-eye-off'"
                                    @click:append="showConfirmPassword = !showConfirmPassword"
                                    @focus="$event.target.removeAttribute('readonly');"
                                    required
                                    readonly
                                    :rules="rules.confirmpassword(userPassword.confirmPassword,userPassword.password)"
                                > </v-text-field>
                                <input type="hidden" name="auth_id" :value="loggedInUser.id">
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text type="submit" >Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
         </v-dialog>
      <!-- dialogChangePassword End -->
  </v-app>
</template>

<script>
import {mapActions, mapState} from 'vuex';
   export default {
      data: () => ({
         drawer: null,
         items:[
            // {text:'Home', icon:'mdi-home', to:'home'},
            {text:'Supplies', icon:'mdi-hand-wave', to:'supply'},
            {text: 'Sales', icon: 'mdi-account-group', to : 'sale'},
            {text: 'Sales Orders', icon: 'mdi-account', to : 'sales_orders'},
            // {text: 'Order Details', icon: 'mdi-account', to : 'order_details'},

         ],
         adminItems: [
            //Admin Access
            {text:'Supply Stock In Request', icon:'mdi-hand-wave', to:'stock_in_request'},
            {text: 'Suppliers', icon: 'mdi-account-group', to : 'supplier'},
            {text: 'Users', icon: 'mdi-account', to : 'user'},
            {text: 'Customers', icon: 'mdi-account', to : 'customer'},
         ],
         csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
         dialogChangePassword: false,
         userPassword: {
            password: '',
            confirmPassword: ''
         },
         showConfirmPassword: false,
         showPassword: false,


      }),
      computed: {
         ...mapState([
            'loggedInUser',
            'rules',
            'PENDING_STOCK_IN_REQUEST'
         ])

      },

      methods: {
         ...mapActions([
            '_getPendingStockInRequest'
         ]),

          async initialize(){
           
         },

         ChangePassword(){
            if(this.$refs.ChangePassword.validate()){
               const myForm = document.getElementById('ChangePassword');
               const formdata = new FormData(myForm);

               axios({
                    method: 'POST',
                    url: `/api/user/${this.loggedInUser.id}/change_password`,
                    data: formdata
                }).then(() => {
                    this.$refs.ChangePassword.reset()
                  //   this.dialogChangePassword = false;
                  alert("PLEASE LOGIN AGAIN");
                  // this.toggleLogout();
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                })
            }
         },

         toggleLogout(){
            const formdata = new FormData();
            formdata.append("_token", this.csrf);

            axios({
                    method: 'POST',
                    url: `/logout`,
                    data: formdata
                }).catch((err) => {
                    console.log("ERROR __")
                    console.err(err)
                })
                .finally(() => {
                  location.reload()
                })
         }
      },
      async mounted() {
         await this._getPendingStockInRequest()

         const { is_password_already_reset } = this.loggedInUser;
         if( ! +is_password_already_reset ){
            this.dialogChangePassword = true;
         }

         

      }
   }
</script>