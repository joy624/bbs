import axios from 'axios'

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