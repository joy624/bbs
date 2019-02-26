import axios from 'axios'

// 获取指定分类的主题
export const index =  params => {
  return axios.get('/bbs/topic/index', {params:params}).then(res => res.data)
}

// 添加主题
export const addTopic =  params => {
  return axios.post('/bbs/topic/add', params).then(res => res.data)
}

// 获取主题
export const viewTopic = topic_id => {
  return axios.get('/bbs/topic/view', {params:{ id: topic_id}}).then(res => res.data)
}

// 删除主题
export const delTopic = params => {
  return axios.post('/bbs/topic/delete', {id:params}).then(res => res.data)
}

// 编辑主题
export const editTopic =  params => {
  return axios.post('/bbs/topic/edit', params).then(res => res.data)
}

// 最新主题
export const newestTopic = num => {
  return axios.post('/bbs/topic/newest', {params:{ num: num}}).then(res => res.data)
}

// 最受欢迎主题
export const bestTopic = num => {
  return axios.post('/bbs/topic/best', {params:{ num: num}}).then(res => res.data)
}

// 是否点赞
export const isLikeTopic = params => {
  return axios.post('/bbs/like/index', {topic_id:params}).then(res => res.data)
}
// 点赞
export const addLikeTopic = params => {
  return axios.post('/bbs/like/add', {topic_id:params}).then(res => res.data)
}
// 取消点赞
export const delLikeTopic = params => {
  return axios.post('/bbs/like/del', {topic_id:params}).then(res => res.data)
}