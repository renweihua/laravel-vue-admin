import request from '@/utils/request'

export function getCategorySelect() {
    return request({
        url: '/article_categorys/getSelectLists',
        method: 'get'
    })
}

export function getList(params) {
    return request({
        url: 'article_categorys',
        method: 'get',
        params
    })
}

export function create(data) {
    return request({
        url: '/article_categorys',
        method: 'post',
        data
    })
}

export function update(data) {
    return request({
        url: `/article_categorys`,
        method: 'put',
        data
    })
}

export function setDel(data) {
    return request({
        url: `/article_categorys`,
        method: 'delete',
        data
    })
}

export function changeFiledStatus(data) {
    return request({
        url: `/article_categorys/changeFiledStatus`,
        method: 'put',
        data
    })
}
