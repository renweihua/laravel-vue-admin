export function getUploadUrl() {
  return process.env.VUE_APP_BASE_API + 'upload_file'
}

export function getBatchUploadUrl() {
  return process.env.VUE_APP_BASE_API + 'upload_files'
}
