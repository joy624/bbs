import axios from 'axios'

// axios.defaults.baseURL = baseUrl

// 登录用户
export const login = params => {
    return axios.post('/bbs/site/login', params).then(res => res.data)
}

// 获取当前登录用户
export const loginUser = params => {
    return axios.get('/bbs/site/user', params).then(res => res.data)
}