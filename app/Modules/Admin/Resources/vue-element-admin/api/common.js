import request from '@/utils/request'

export function getUploadUrl() {
  return process.env.VUE_APP_BASE_API + '/upload_file'
}

export function getBatchUploadUrl() {
  return process.env.VUE_APP_BASE_API + '/upload_files'
}

export function getMonthLists() {
    return request({
        url: '/get_month_lists',
        method: 'get'
    })
}
