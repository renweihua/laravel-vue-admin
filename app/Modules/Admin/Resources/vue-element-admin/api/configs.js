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
        url: '/configs/create',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: '/configs/update',
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: '/configs/delete',
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: '/configs/changeFiledStatus',
        method: 'put',
        data
    })
}
