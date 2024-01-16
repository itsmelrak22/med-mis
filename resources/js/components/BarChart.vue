<template>
    <Bar
      :chart-options="chartOptions"
      :chart-data="chartData"
      :chart-id="chartId"
      :dataset-id-key="datasetIdKey"
      :plugins="plugins"
      :css-classes="cssClasses"
      :styles="styles"
      :width="width"
      :height="height"
    />
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs/legacy'
import { mapActions, mapState } from 'vuex';
  
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
  } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  
  export default {
    name: 'BarChart',
    components: {
      Bar
    },
    props: {
      chartId: {
        type: String,
        default: 'bar-chart'
      },
      datasetIdKey: {
        type: String,
        default: 'label'
      },
      width: {
        type: Number,
        default: 400
      },
      height: {
        type: Number,
        default: 400
      },
      cssClasses: {
        default: '',
        type: String
      },
      styles: {
        type: Object,
        default: () => {}
      },
      plugins: {
        type: Array,
        default: () => []
      }
    },
    data() {
      return {
        chartData: {
          labels: [
            
          ],
          datasets: [
            {
              label: 'Supplies',
              backgroundColor: '#3f51b5',
              data: []
            },
           
          ]
        },
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false
        }
      }
    },
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
    mounted() {
      setTimeout(() => {
        console.log('SUPPLIES', this.SUPPLIES)
        this.SUPPLIES.forEach(el => {
          this.chartData.labels.push(el.name);
          this.chartData.datasets[0].data.push(el.quantity);
        });
      }, 1500);
    },
  }
  </script>
  