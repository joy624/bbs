import Vue from 'vue'
import Vuex from 'vuex'
import { index } from "@/api/topic";
import { list } from "@/api/cate";
import { login } from "@/api/user";

Vue.use(Vuex)
const state = {
  login_name: '',
  login_id: '',
  login_role: '',

  cate_active: 1,
  topics: [],
  topic_page_count: 1,
  topic_page_current: 1
}

// actions
const actions = {
  loadTopics: ({ commit } ) => commit('loadTopics' ),
  changeCate: ({ commit }, category_id ) => commit('changeCate', category_id),
  loadCates: ({ commit } ) => commit('loadCates'),
  setLoginUser: ({ commit }, args ) => commit('setLoginUser', args),
  setLogout: ({ commit } ) => commit('setLogout'),
  changeTopicPage: ({ commit }, page_current ) => commit('changeTopicPage', page_current),
}

// mutations
const mutations = {
  loadTopics: state => {
    index({category_id: state.cate_active, page: state.topic_page_current}).then(res => {
      state.topics = res.data.topic;
      state.topic_page_current = res.data.page_current;
      state.topic_page_count = res.data.page_total;
      console.info('loadTopics', state.topic_page_count, state.topic_page_current)
    });
  },
  changeCate (state, category_id) {
    state.cate_active = category_id
    state.topic_page_current = 1;
    index({category_id: state.cate_active, page: state.topic_page_current}).then(res => {
      state.topics = res.data.topic;
      state.topic_page_current = res.data.page_current;
      state.topic_page_count = res.data.page_total;
    });
  },
  loadCates: state => {
    list().then(res => {
      state.cates = res.data;
    });
  },
  setLoginUser (state, args) {
    state.login_name = args.name
    state.login_id = args.id
    state.login_role = args.role
    localStorage.setItem('login_name', args.name)
    localStorage.setItem('login_id', args.id)
    localStorage.setItem('login_role', args.role)
  },
  setLogout (state) {
    state.login_name = ''
    state.login_id = ''
    state.login_role = ''
    localStorage.removeItem('login_name')
    localStorage.removeItem('login_id')
    localStorage.removeItem('login_role')
  },
  changeTopicPage (state, page_num) {
    state.topic_page_current = page_num
    index({category_id: state.cate_active, page: state.topic_page_current}).then(res => {
      state.topics = res.data.topic;
      state.topic_page_current = res.data.page_current;
      state.topic_page_count = res.data.page_total;
    });
  },
}

// getters
const getters = {
  login_name (state) {
    if (state.login_name != '')
      return state.login_name
    else
      return localStorage.getItem('login_name')
  },
  login_id (state) {
    if (state.login_id != '')
      return state.login_id
    else
      return localStorage.getItem('login_id')
  },
  login_role (state) {
    if (state.login_role != '')
      return state.login_role
    else
      return localStorage.getItem('login_role')
  }
}

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations
})
