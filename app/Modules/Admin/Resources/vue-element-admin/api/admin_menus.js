import request from '@/utils/request'

export function getMenusSelect() {
    return request({
        url: '/admin_menus/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'admin_menus',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/admin_menus',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/admin_menus`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/admin_menus`,
        method: 'delete',
        data
    })
}
