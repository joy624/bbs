import axios from 'axios'

// axios.defaults.baseURL = baseUrl

// 获取分类
export const list =  params => {
    return axios.post('/bbs/category/list').then(res => res.data)
}