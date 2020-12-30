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
        url: '/configs',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: '/configs',
        method: 'put',
        parmas: data
    })
}

export function setDel(data) {
    return request({
        url: '/configs',
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
