import request from '@/utils/request'

// 文件列表
export function getFileList(params) {
    return request({
        url: '/getFileList',
        method: 'get',
        params
    })
}

// 文件分组列表
export function getFileGroup(params) {
    return request({
        url: '/getGroupList',
        method: 'get',
        params
    })
}

export function delFile(data) {
    return request({
        url: `/files/delete`,
        method: 'delete',
        data
    })
}


