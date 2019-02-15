import Vue from 'vue'
import Router from 'vue-router'

import Login from '@/views/Login'
import Index from '@/views/Index'
import Register from '@/views/Register'
import Cate from '@/views/Cate'
import UserInfo from '@/views/UserInfo'
import Topic from '@/views/Topic'
import AddTopic from '@/views/AddTopic'
import EditTopic from '@/views/EditTopic'
import FindPwd from '@/views/FindPwd'

Vue.use(Router)

export default new Router({
  mode:'history',
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
    },  {
      path: '/userinfo',
      name: 'UserInfo',
      component: UserInfo
    }, {
      path: '/topic',
      name: 'Topic',
      component: Topic
    }, {
      path: '/addtopic',
      name: 'AddTopic',
      component: AddTopic
  }, {
      path: '/edittopic',
      name: 'EditTopic',
      component: EditTopic
  }, {
      path: '/findpwd',
      name: 'FindPwd',
      component: FindPwd
  }
  ]
})
