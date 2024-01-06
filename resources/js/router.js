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

import store from './store'

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
            component : User,
            beforeEnter: (to, from, next) => {
                console.log('store.state.loggedInUser', store.state.loggedInUser)
                // replace `store.state.user` with your actual user state
                if (store.state.loggedInUser.is_super_admin || store.state.loggedInUser.is_admin) {
                  next(); // proceed to '/supply' if admin
                  
                } else {
                    alert('Invalid Role!')
                  next('/home'); // redirect to home if not admin
                }
            }
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