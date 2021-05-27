import request from '@/utils/request'

export function getList(params) {
    return request({
        url: '/database/tables',
        method: 'get',
        params
    });
}

export function backupsTables(data) {
    return request({
        url: '/database/backups',
        method: 'post',
        data
    });
}
