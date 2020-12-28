<template>
    <div class="app-container">
        <div class="filter-container">
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-plus"
                @click="handleEdit"
            >
                {{ $t('table.add') }}
            </el-button>
        </div>

        <el-table
            v-loading="listLoading"
            :data="list"
            :element-loading-text="elementLoadingText"
            row-key="menu_id"
            border
            :tree-props="{ children: '_child', hasChildren: true }"
        >
            <el-table-column
                show-overflow-tooltip
                prop="menu_name"
                label="菜单名称"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="vue_path"
                label="Vue路由"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="vue_component"
                label="vue文件路径"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="api_url"
                label="API路由"
                align="center"
            ></el-table-column>
            <el-table-column show-overflow-tooltip label="是否隐藏" align="center">
                <template slot-scope="scope">
                          <span>
                            {{ scope.row.is_hidden == 1 ? "是" : "否" }}
                          </span>
                </template>
            </el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="external_links"
                label="重定向（外链）"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                prop="menu_sort"
                label="排序"
                align="center"
            ></el-table-column>
            <el-table-column
                show-overflow-tooltip
                label="图标"
                align="center"
            >
                <template slot-scope="scope">
                    <i :class="scope.row.vue_icon"></i>
                </template>
            </el-table-column>
            <el-table-column
                show-overflow-tooltip
                align="center"
                label="操作"
                width="200"
            >
                <template v-slot="scope">
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <edit ref="edit" @fetchData="getMenus"></edit>
    </div>
</template>

<script>
    import {getList as getMenus, setDel} from "@/api/admin_menus";
    import Edit from "./components/detail";

    export default {
        name: "MenuManagement",
        components: {Edit},
        data() {
            return {
                defaultProps: {
                    children: "children",
                    label: "label",
                },
                list: [],
                listLoading: true,
                elementLoadingText: "正在加载...",
            };
        },
        async created() {
            this.getMenus();
        },
        methods: {
            handleEdit(row) {
                if (row.menu_id) {
                    this.$refs["edit"].showEdit(row);
                } else {
                    this.$refs["edit"].showEdit();
                }
            },
            handleDelete(row) {
                if (row.menu_id) {
                    this.$confirm(
                        '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                        'Warning',
                        {
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel',
                            type: 'warning'
                        })
                        .then(async () => {
                            const {msg} = await setDel({menu_id: row.menu_id});
                            this.$message({
                                message: msg,
                                type: 'success'
                            });
                            this.getMenus();
                        })
                        .catch(err => {
                            console.error(err);
                        });
                }
            },
            async getMenus() {
                this.listLoading = true;
                const {data} = await getMenus();
                console.log(data);

                this.list = data;
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            handleNodeClick(data) {
                this.getMenus();
            },
        },
    };
</script>
