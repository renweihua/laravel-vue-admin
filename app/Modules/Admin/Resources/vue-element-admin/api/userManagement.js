import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/admins/index',
    method: 'get',
    params
  })
}

export function create(data) {
  return request({
    url: '/admins/create',
    method: 'post',
    data
  })
}

export function update(data) {
  return request({
    url: '/admins/update',
    method: 'post',
    data
  })
}

export function doDelete(data) {
  return request({
    url: '/admins/delete',
    method: 'post',
    data
  })
}
