import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)
import Home from './components/Home'
import Supply from './components/Supply'
import Supplier from './components/Supplier'
import Sales from './components/Sales'
import User from './components/User'
import Customer from './components/Customer'
import OrderDetails from './components/OrderDetails'
import SalesOrders from './components/SalesOrders'

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/supply',
            name: 'supply',
            component: Supply
        },
        {
            path: '/supplier',
            name: 'supplier',
            component : Supplier
        },
        {
            path: '/sale',
            name: 'sale',
            component : Sales
        },
        {
            path: '/user',
            name: 'user',
            component : User
        },
        {
            path: '/customer',
            name: 'customer',
            component : Customer
        },
        {
            path: '/order_details',
            name: 'order_details',
            component : OrderDetails
        },
        {
            path: '/sales_orders',
            name: 'sales_orders',
            component : SalesOrders
        },

      
    ],
});