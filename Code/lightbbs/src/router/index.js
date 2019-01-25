import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login'
import Index from '@/views/Index'
import Register from '@/views/Register'
import Cate from '@/views/Cate'
import addTopic from '@/views/addTopic'
import userInfo from '@/views/userInfo'

Vue.use(Router)

export default new Router({
  routes: [{
      path: '/login',
      name: 'Login',
      component: Login
    }, {
      path: '/',
      name: 'Index',
      component: Index
    }, {
      path: '/register',
      name: 'Register',
      component: Register
    }, {
      path: '/cate',
      name: 'Cate',
      component: Cate
    }, {
      path: '/addtopic',
      name: 'addTopic',
      component: addTopic
    }, {
      path: '/userinfo',
      name: 'userInfo',
      component: userInfo
    }
  ]
})
