import request from '@/utils/request'

export function getRolesSelect() {
    return request({
        url: '/admin_roles/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'admins',
        method: 'get',
        params
    })
}

export function detail(data) {
    return request({
        url: '/admins/detail',
        method: 'post',
        data
    })
}

export function create(data) {
    return request({
        url: '/admins',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/admins`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/admins`,
        method: 'delete',
        data
    })
}
