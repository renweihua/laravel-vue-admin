import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/banners',
        method: 'get',
        params
    });
}

export function create(data) {
    return request({
        url: '/banners',
        method: 'post',
        data
    });
}

export function update(data) {
    return request({
        url: '/banners',
        method: 'put',
        data
    });
}

export function setDel(data) {
    return request({
        url: '/banners',
        method: 'delete',
        data
    });
}

export function changeFiledStatus(data) {
    return request({
        url: '/banners/changeFiledStatus',
        method: 'put',
        data
    })
}
