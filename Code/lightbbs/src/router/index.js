import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/views/Login'
import Index from '@/views/Index'
import Register from '@/views/Register'
import Cate from '@/views/Cate'
import editAddTopic from '@/views/editAddTopic'
import userInfo from '@/views/userInfo'
import Topic from '@/views/Topic'
import addTopic from '@/views/addTopic'
import editTopic from '@/views/editTopic'
import findPwd from '@/views/findPwd'

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
    }, {
      path: '/editaddtopic',
      name: 'editAddTopic',
      component: editAddTopic
    }, {
      path: '/userinfo',
      name: 'userInfo',
      component: userInfo
    }, {
      path: '/topic',
      name: 'Topic',
      component: Topic
    }, {
      path: '/addtopic',
      name: 'addTopic',
      component: addTopic
  }, {
      path: '/edittopic',
      name: 'editTopic',
      component: editTopic
  }, {
      path: '/findpwd',
      name: 'findPwd',
      component: findPwd
  }
  ]
})
