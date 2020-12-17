import request from '@/utils/request'

export function getList(query) {
  return request({
    url: '/configs',
    method: 'get',
    params: query
  })
}

export function create(data) {
  return request({
    url: '/friendlinks/create',
    method: 'post',
    data
  })
}

export function update(data) {
  console.log(data)
  return request({
    url: '/friendlinks/update',
    method: 'post',
    parmas: data
  })
}

export function setDel(data) {
  return request({
    url: '/friendlinks/delete',
    method: 'delete',
    data
  })
}
