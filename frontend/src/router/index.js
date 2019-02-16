import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Home from '@/pages/Home'
import Login from '@/pages/Login'
import Register from '@/pages/Register'
import TopicView from '@/pages/TopicView'
import Category from '@/pages/Category'
import AddTopic from '@/pages/AddTopic'
import EditTopic from '@/pages/EditTopic'
import UserPage from '@/pages/UserPage'
import FindPwd from '@/pages/FindPwd'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/hello',
      name: 'HelloWorld',
      component: HelloWorld
    },
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/topic',
      name: 'Topic',
      component: TopicView
    },
    {
      path: '/cate',
      name: 'Cate',
      component: Category
    },
    {
      path: 'addtopic',
      name: 'AddTopic',
      component: AddTopic
    },
    {
      path: '/edittopic',
      name: 'EditTopic',
      component: EditTopic
    },
    {
      path: '/user',
      name: 'User',
      component: UserPage
    },
    {
      path: '/findpwd',
      name: 'FindPwd',
      component: FindPwd
    }
  ]
})
