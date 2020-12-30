import request from '@/utils/request'

export function statistics() {
    return request({
        url: '/indexs',
        method: 'get'
    })
}
