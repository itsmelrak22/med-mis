<template>
    <v-container fluid>
        <v-container>
            <v-card>
                <v-row>
                    <v-col class="mx-2">
                        <v-alert
                            border="left"
                            color="indigo"
                            dark
                            max-width="200"
                            >
                            Inventory : {{ inventoryCount }}
                        </v-alert>
                    </v-col>
                    <v-col class="mx-2">
                        <v-alert
                            border="left"
                            color="green"
                            dark
                            max-width="200"
                            >
                            Transaction : {{ pendingSalesOrderRequest }}

                        </v-alert>
                    </v-col>
                    <v-col class="mx-2">
                        <v-alert
                            border="left"
                            color="orange"
                            dark
                            max-width="200"
                            >
                            Pending : {{ pendingCount }}

                        </v-alert>
                    </v-col>
                    <v-col class="mx-2">
                        <v-alert
                            border="left"
                            color="red"
                            dark
                            max-width="200"
                            >
                            Critical : {{ criticalSupplies }}

                        </v-alert>
                    </v-col>
                </v-row>
            </v-card>
        </v-container>
        <v-divider></v-divider>
        
        <v-container>
            <BarChart />
        </v-container>
    </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import BarChart from './BarChart.vue'

  const gradients = [
    ['#222'],
    ['#42b3f4'],
    ['red', 'orange', 'yellow'],
    ['purple', 'violet'],
    ['#00c6ff', '#F0F', '#FF0'],
    ['#f72047', '#ffd200', '#1feaea'],
  ]

  export default {
    components: {
        BarChart
    },
    data: () => ({
      width: 2,
      radius: 10,
      padding: 8,
      lineCap: 'round',
      gradient: gradients[5],
      value: [0, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
      gradientDirection: 'top',
      gradients,
      fill: false,
      type: 'trend',
      autoLineWidth: false,
    }),
    computed:{
        ...mapState([
            'SUPPLIERS',
            'SUPPLIES',
            'rules',
            'loggedInUser',
            'inventoryCount',
            'pendingCount',
            'pendingSalesOrderRequest',
            'criticalSupplies',
            'SUPPLIES_CRITICAL'
        ]),
    },

    methods: {
      ...mapActions([
          '_getSuppliers',
          '_getSupplies',
          '_getPendingStockInRequest',
          '_getPendingSalesOrderRequests',
          '_getPendingStockInRequest',
          '_getSuppliesCritical'
      ]),
      async initialize(){
        await this._getSupplies()
          await this._getSuppliers()
          await this._getPendingStockInRequest()
          await this._getPendingSalesOrderRequests()
          await this._getPendingStockInRequest()
          await this._getSuppliesCritical()
      },
    },
    async mounted(){
        await this.initialize()
    }

  }
</script>