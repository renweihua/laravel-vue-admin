import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/friendlinks',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/friendlinks',
        method: 'post',
        data
    })
}

export function update(data) {
    console.log(data)
    return request({
        url: '/friendlinks',
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: '/friendlinks',
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: '/friendlinks/changeFiledStatus',
        method: 'put',
        data
    })
}
