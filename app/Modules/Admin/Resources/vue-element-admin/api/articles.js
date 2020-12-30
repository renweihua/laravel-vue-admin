import request from '@/utils/request'

export function getCategoriesSelect() {
    return request({
        url: '/article_categories/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'articles',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/articles',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/articles`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/articles`,
        method: 'delete',
        data
    })
}
