import axios from 'axios'

// axios.defaults.baseURL = baseUrl


// 获取指定分类的主题
export const index =  params => {
    return axios.get('/bbs/topic/index', {params:{category_id:params}}).then(res => res.data)
}

// 添加主题
export const addTopic =  params => {
    return axios.post('/bbs/topic/add', params).then(res => res.data)
}
// 获取主题
export const viewTopic =  params => {
    return axios.get('/bbs/topic/view', {params:{id:params}}).then(res => res.data)
}

// 删除主题
export const delTopic = params => {
    return axios.post('/bbs/topic/delete', {id:params}).then(res => res.data)
}


// 编辑主题
export const editTopic =  params => {
    return axios.post('/bbs/topic/edit', params).then(res => res.data)
}

// 获取对应主题的回复
export const replyIndex = params => {
    return axios.post('/bbs/reply/index', {topic_id:params}).then(res => res.data)
}

// 为对应主题添加回复
export const addTopicReply = params => {
    return axios.post('/bbs/reply/add', params).then(res => res.data)
}

// 删除回复
export const delTopicReply = params => {
    return axios.post('/bbs/reply/delete', {id:params}).then(res => res.data)
}
// 编辑回复
export const editTopicReply = params => {
    return axios.post('/bbs/reply/edit', params).then(res => res.data)
}