import axios from 'axios'

// 获取指定分类的主题
export const index =  params => {
  return axios.get('/bbs/topic/index', {params:params}).then(res => res.data)
}

// 获取指定分类的主题总记录数
export const getTopicTotal =  params => {
  return axios.get('/bbs/topic/getCateTopicNum', {params:{category_id:params}}).then(res => res.data)
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
