<template>
  <el-dialog
    :title="title"
    :visible.sync="dialogFormVisible"
    width="500px"
    @close="close"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="80px">
      <el-form-item label="管理员" prop="admin_name">
        <el-input v-model.trim="form.admin_name" autocomplete="off" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input type="password" autocomplete="off" />
      </el-form-item>
      <el-form-item label="邮箱" prop="admin_email">
        <el-input v-model.trim="form.admin_email" autocomplete="off" />
      </el-form-item>
      <el-form-item label="权限">
        <!--prop="permissions"-->
        <!--v-model="form.permissions"-->
        <el-checkbox-group>
          <el-checkbox label="admin" />
          <el-checkbox label="editor" />
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="启用状态" prop="resource">
        <el-radio-group v-model="form.is_check">
          <el-radio :label="0" :checked="form.is_check == 0 ? 'checked' : ''">禁用</el-radio>
          <el-radio :label="1" :checked="form.is_check == 1 ? 'checked' : ''">启用</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="close">取 消</el-button>
      <el-button type="primary" @click="save">确 定</el-button>
    </div>
  </el-dialog>
</template>

<script>
import { create, update } from '@/api/configs'
import { isEmail } from '@/utils/validate'

// 定义一个全局的变量，谁用谁知道
var validEmail = (rule, value, callback) => {
  if (!isEmail(value)) {
    callback(new Error('请输入正确的邮箱'))
  } else {
    callback()
  }
}

export default {
  name: 'UserManagementEdit',
  data() {
    return {
      form: {
        admin_name: '',
        password: '',
        admin_email: '',
        permissions: [],
        is_check: 0
      },
      rules: {
        admin_name: [
          { required: true, trigger: 'blur', message: '请输入用户名' },
          {
            min: 3,
            max: 20,
            message: '长度在 3 到 20 个字符',
            trigger: 'blur'
          }
        ],
        // password: [
        //     {required: true, trigger: "blur", message: "请输入密码"},
        // ],
        admin_email: [
          { required: true, trigger: 'blur', message: '请输入邮箱' },
          { required: true, trigger: 'blur', validator: isEmail }// 这里需要用到全局变量
        ],
        permissions: [
          { required: true, trigger: 'blur', message: '请选择权限' }
        ]
      },
      title: '',
      dialogFormVisible: false
    }
  },
  created() {
  },
  methods: {
    showEdit(row) {
      const admin = Object.assign({}, row)
      if (!admin) {
        this.title = '添加'
      } else {
        this.title = '编辑'
        this.form = admin
        this.form.password = this.form.login_token = this.form.use_role = ''
      }
      this.dialogFormVisible = true
    },
    close() {
      this.$refs['form'].resetFields()
      this.form = this.$options.data().form
      this.dialogFormVisible = false
    },
    save() {
      this.$refs['form'].validate(async(valid) => {
        console.log(valid)
        console.log(this.form.admin_id)
        if (valid) {
          const { msg } = this.form.admin_id ? await update(this.form) : await create(this.form)
          this.$baseMessage(msg, 'success')
          this.$emit('fetchData')
          this.close()
        } else {
          return false
        }
      })
    }
  }
}
</script>
