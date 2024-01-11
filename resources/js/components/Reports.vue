<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                REPORTS
            </v-card-title>
            <v-card-text>
                <div>
                    <v-row>
                        <v-col>
                            <v-btn
                            depressed
                            color="primary"
                            class="mx-1 my-1"
                            @click="generateStockInRequestAll()"
                            >
                                All Stock In Request
                            </v-btn>
                            <v-btn
                            depressed
                            color="primary"
                            class="mx-1 my-1"
                            @click="generateStockInApproved()"
                            >
                                All Stock In Request - Approved
                            </v-btn>
                            <v-btn
                            depressed
                            color="primary"
                            class="mx-1 my-1"
                            @click="generateStockInCancelled()"
                            >
                                All Stock In Request - Cancelled
                            </v-btn>
                            <v-btn
                            depressed
                            color="primary"
                            class="mx-1 my-1"
                            @click="generateStockInPending()"
                            >
                                All Stock In Request - Pending
                            </v-btn>
                            <v-btn
                            depressed
                            color="primary"
                            class="mx-1 my-1"
                            @click="generateStockInReturnToSeller()"
                            >
                                All Stock In Request - Return To Seller
                            </v-btn>
                        </v-col>
                    </v-row>

                </div>
            </v-card-text>
        </v-card>

        <v-overlay :value="overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </v-container>
</template>

<script>
export default {
    sockets: {

    },
    data(){
        return {
            overlay: false
        }
    },

    computed:{


    },

    methods: {

        async initialize(){
            // await this._getUsers()
        },
        async generateStockInRequestAll() {
            try {
                window.open(`/generate-stock-in-request`);
            } catch (error) {
                console.error(error);
            }
        },
        async generateStockInApproved() {
            try {
                window.open(`/generate-stock-in-request-approved`);
            } catch (error) {
                console.error(error);
            }
        },
        async generateStockInPending() {
            try {
                window.open(`/generate-stock-in-request-pending`);
            } catch (error) {
                console.error(error);
            }
        },
        async generateStockInCancelled() {
            try {
                window.open(`/generate-stock-in-request-cancelled`);
            } catch (error) {
                console.error(error);
            }
        },
        async generateStockInReturnToSeller() {
            try {
                window.open(`/generate-stock-in-request-return-to-seller`);
            } catch (error) {
                console.error(error);
            }
        }
     
    },

    async mounted(){
        
    }

}
</script>