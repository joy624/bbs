import axios from 'axios'

// axios.defaults.baseURL = baseUrl

// 获取分类
export const list =  params => {
    return axios.post('/bbs/category/index').then(res => res.data)
}

// 删除分类
export const delCate = params => {
    return axios.post('/bbs/category/delete', {id:params}).then(res => res.data)
}

// 添加分类
export const addCate = params => {
    return axios.post('/bbs/category/add', {name:params}).then(res => res.data)
}

// 编辑分类
export const editCate = params => {
    return axios.post('/bbs/category/edit', params).then(res => res.data)
}