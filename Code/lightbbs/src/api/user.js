import axios from 'axios'

// axios.defaults.baseURL = baseUrl

// 登录用户
export const login = params => {
    return axios.post('/bbs/site/login', params).then(res => res.data)
}

// 登出用户
export const logout = params => {
    return axios.post('/bbs/site/logout').then(res => res.data)
}

// 获取当前登录用户
export const loginUser = params => {
    return axios.get('/bbs/site/user', params).then(res => res.data)
}

// 用户注册
export const register = params => {
    return axios.post('/bbs/user/register', params,{ headers: {'Content-Type': 'multipart/form-data'}}).then(res => res.data)
}

// 修改头像
export const headPortrait = params => {
    return axios.post('/bbs/user/headPortrait', params).then(res => res.data)
}
// 修改用户名
export const editName = params => {
    return axios.post('/bbs/user/editName', params).then(res => res.data)
}
// 修改密码
export const editPwd = params => {
    return axios.post('/bbs/user/resetPassword', params).then(res => res.data)
}
// 修改邮箱
export const editEmail = params => {
    return axios.post('/bbs/user/editEmail', params).then(res => res.data)
}

// 忘记密码
export const findPwd = params => {
    return axios.post('/bbs/user/findPassword', params).then(res => res.data)
}

