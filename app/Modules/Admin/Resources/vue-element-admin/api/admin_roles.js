import request from '@/utils/request'

export function getRolesSelect() {
    return request({
        url: '/admin_roles/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'admin_roles',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/admin_roles',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/admin_roles`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/admin_roles`,
        method: 'delete',
        data
    })
}
