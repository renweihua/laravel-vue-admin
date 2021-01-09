import request from '@/utils/request'

export function getList(query) {
    return request({
        url: '/configs',
        method: 'get',
        params: query
    })
}

export function getConfigGroupType() {
    return request({
        url: '/configs/getConfigGroupType',
        method: 'get',
    })
}

export function detail(id) {
    return request({
        url: '/configs/detail',
        method: 'get',
        params: { config_id:id }
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
