<template>
    <el-dialog
        :title="title"
        :visible.sync="dialogFormVisible"
        width="500px"
        @close="close"
    >
        <el-form ref="form" :model="form" :rules="rules" label-width="80px">
            <el-form-item label="父级菜单" prop="parent_id">
                <el-input v-model="form.parent_id" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="菜单名称" prop="menu_name">
                <el-input v-model="form.menu_name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue路由路径" prop="vue_path">
                <el-input v-model="form.vue_path" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue的redirect" prop="vue_redirect">
                <el-input v-model="form.vue_redirect" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="图标" prop="vue_icon">
                <el-input v-model="form.vue_icon" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue文件路径" prop="vue_component">
                <el-input v-model="form.vue_component" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="Vue的meta" prop="vue_meta">
                <el-input v-model="form.vue_meta" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="外链" prop="external_links">
                <el-input v-model="form.external_links" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="接口路由" prop="api_url">
                <el-input v-model="form.api_url" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="排序" prop="menu_sort">
                <el-input v-model="form.menu_sort" autocomplete="off" value="0"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="close">取 消</el-button>
            <el-button type="primary" @click="save">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import {create, update} from "@/api/admin_menus";

    export default {
        name: "MenuManagementEdit",
        data() {
            return {
                form: {},
                rules: {
                    id: [{required: true, trigger: "blur", message: "请输入路径"}],
                },
                title: "",
                dialogFormVisible: false,
            };
        },
        created() {
        },
        methods: {
            showEdit(row) {
                console.log(row);
                if (!row) {
                    this.title = "添加";
                } else {
                    this.title = "编辑";
                    this.form = Object.assign({}, row);
                }
                console.log(this.form);
                this.dialogFormVisible = true;
            },
            close() {
                this.$refs["form"].resetFields();
                this.form = this.$options.data().form;
                this.dialogFormVisible = false;
            },
            save() {
                this.$refs["form"].validate(async (valid) => {
                    if (valid) {
                        const {msg, status} = this.form.menu_id ? await update(this.form) : await create(this.form);

                        switch (status) {
                            case 1:
                                this.$message({
                                    message: msg,
                                    type: 'success'
                                });

                                this.$emit("fetchData");
                                this.close();
                                break;
                            default:
                                this.$message({
                                    message: msg,
                                    type: 'error'
                                });
                                break;
                        }
                    } else {
                        return false;
                    }
                });
            },
        },
    };
</script>
