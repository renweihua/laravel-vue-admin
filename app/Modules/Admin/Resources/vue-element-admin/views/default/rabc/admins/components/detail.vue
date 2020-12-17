<template>
    <el-dialog
            :title="title"
            :visible.sync="dialogFormVisible"
            width="500px"
            @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="账户：">
                <el-input
                        v-model.trim="form.admin_name"
                        :autosize="{ minRows: 2, maxRows: 30}"
                        placeholder="管理员账户"
                />
            </el-form-item>
            <el-form-item label="邮箱">
                <el-input
                        v-model="form.admin_email"
                        :autosize="{ minRows: 2, maxRows: 20}"
                        type="textarea"
                        placeholder="邮箱"
                />
            </el-form-item>
            <el-form-item label="头像" prop="admin_head">
                <pan-thumb :image="image_url"/>

                <el-button
                        type="primary"
                        icon="el-icon-upload"
                        style="position: absolute;bottom: 15px;margin-left: 40px;"
                        @click="show=true"
                >
                    头像
                </el-button>

                <my-upload
                        v-model="show"
                        img-format="png"
                        :size="size"
                        :width="50"
                        :height="50"
                        lang-type="zh"
                        :no-rotate="false"
                        field="file"
                        :url="upload_url"
                        @crop-success="cropSuccess"
                        @crop-upload-success="cropUploadSuccess"
                        @crop-upload-fail="cropUploadFail"
                />
            </el-form-item>
            <el-form-item label="密码">
                <el-input v-model="form.password" placeholder="登录密码"/>
                <span>
                        已设置密码【再次输入，默认会更改；不输入，则不变动】
                        请设置密码【默认为：123456】
                    </span>
            </el-form-item>
            <el-form-item label="授权角色" prop="role_id">
                <el-radio-group>
                    <el-radio :label="1" :checked="form.role_id == 1 ? 'checked' : ''">角色1</el-radio>
                    <el-radio :label="2" :checked="form.role_id == 2 ? 'checked' : ''">角色2</el-radio>
                </el-radio-group>
                <select name="public-choice" v-model ="roleSelected" style="width: 200px;" autocomplete="off" @change ="changeRole($event)">
          <option value="" >请选择</option>
          <option :value="role.id"  v-for="role in roleList"  >
            {{ role.role_name }}
          </option>
        </select>
            </el-form-item>

            <el-form-item label="是否启用" prop="is_check">
                <el-radio-group v-model="form.is_check">
                    <el-radio :label="0" :checked="form.is_check == 0 ? 'checked' : ''">禁用</el-radio>
                    <el-radio :label="1" :checked="form.is_check == 1 ? 'checked' : ''">启用</el-radio>
                </el-radio-group>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button type="danger" @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import {create, update} from '@/api/admins';
    import {getUploadUrl} from '@/api/common';
    import {validEmail} from '@/utils/validate';

    import myUpload from '@/components/Uploads/image/index';
    import PanThumb from '@/components/PanThumb';

    // 定义一个全局的变量，谁用谁知道
    var _validEmail = (rule, value, callback) => {
        if (!validEmail(value)) {
            callback(new Error('请输入正确的邮箱'))
        } else {
            callback()
        }
    };

    export default {
        name: '',
        components: {
            'my-upload': myUpload,
            PanThumb
        },
        data() {
            return {
                form: {
                    admin_name: '',
                    admin_email: '',
                    admin_head: '',
                    password: '',
                    role_id: '',
                    is_check: 0
                },
                rules: {
                    admin_name: [
                        {required: true, trigger: 'blur', message: '请输入管理员账户'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        }
                    ],
                    admin_email: [
                        {required: true, trigger: 'blur', message: '请输入管理员邮箱'},
                        {
                            min: 2,
                            max: 20,
                            message: '长度在 2 到 20 个字符',
                            trigger: 'blur'
                        },
                        {required: false, trigger: 'blur', validator: _validEmail, message: '请输入正确的邮箱'}
                    ],
                },
                title: '',
                dialogFormVisible: false,

                // 图片上传
                show: false,
                size: 2.1,

                // 图片上传
                upload_url: '',
                image_url: ''
            }
        },
        created() {
            this.upload_url = getUploadUrl()
        },
        methods: {
            toggleShow() {
                this.show = !this.show
            },
            cropSuccess(imgDataUrl, field) {
                console.log('-------- crop success --------', imgDataUrl, field)
            },
            // 上传成功回调
            cropUploadSuccess(jsonData, field) {
                var file = jsonData.data;
                console.log(file);
                this.image_url = file.file_path;
                this.form.admin_head = file.file_id;
            },
            // 上传失败回调
            cropUploadFail(status, field) {
                console.log('-------- upload fail --------');
                console.log('上传失败状态' + status);
                console.log('field: ' + field)
            },

            showEdit(row) {
                const detail = Object.assign({}, row);
                if (!detail) {
                    this.title = '添加';
                } else {
                    this.title = '编辑';
                    this.form = Object.assign(this.form, detail);
                    // 设置展示的图标
                    this.image_url = this.form.cover ? this.form.cover.file_path : '';
                }
                console.log(this.form);
                this.dialogFormVisible = true
            },
            close() {
                this.$refs['form'].resetFields();
                this.form = this.$options.data().form;
                this.dialogFormVisible = false;
            },
            save() {
                this.$refs['form'].validate(async (valid) => {
                    console.log(valid);
                    if (valid) {
                        const {msg} = this.form.admin_id ? await update(this.form) : await create(this.form);
                        this.$message({
                            message: msg,
                            type: 'success'
                        });
                        this.$emit('fetchData');
                        this.close();
                    } else {
                        return false;
                    }
                })
            }
        }
    }
</script>
