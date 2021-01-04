import request from '@/utils/request'

export function statistics() {
    return request({
        url: '/indexs',
        method: 'get'
    })
}

export function logsStatistics() {
    return request({
        url: '/logsStatistics',
        method: 'get'
    })
}
