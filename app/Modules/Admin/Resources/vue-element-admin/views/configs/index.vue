<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入配置标题/名称"
                style="width: 200px;"
                class="filter-item"
                @keyup.enter.native="handleFilter"
            />
            <el-select v-model="listQuery.is_check" placeholder="请选择启用状态" clearable class="filter-item">
                <el-option
                    v-for="item in calendarCheckOptions"
                    :key="item.key"
                    :checked="item.key == listQuery.is_check"
                    :label="item.display_name+'('+item.key+')'"
                    :value="item.key"
                />
            </el-select>
            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>
            <el-button v-waves class="filter-item" type="danger" icon="el-icon-delete" @click="handleDelete">
                {{ $t('table.batchDelete') }}
            </el-button>
            <el-button
                class="filter-item"
                style="margin-left: 10px;"
                type="primary"
                icon="el-icon-plus"
                @click="handleEdit"
            >
                {{ $t('table.add') }}
            </el-button>
            <el-button
                v-waves
                :loading="downloadLoading"
                class="filter-item"
                type="success"
                icon="el-icon-download"
                @click="handleDownload"
            >
                {{ $t('table.export') }}
            </el-button>
            <el-button
                v-waves
                :loading="downloadLoading"
                class="filter-item"
                type="warning"
                icon="el-icon-refresh"
                @click="pushRefresh"
            >
                同步配置文件
            </el-button>
        </div>

        <el-table
            v-loading="listLoading"
            :data="list"
            :element-loading-text="elementLoadingText"
            @selection-change="setSelectRows"
            border
            class="margin-buttom-10"
        >
            <el-table-column show-overflow-tooltip type="selection"/>

            <el-table-column
                show-overflow-tooltip
                prop="config_id"
                label="Id"
            />

            <el-table-column
                show-overflow-tooltip
                prop="config_title"
                label="配置标题"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="config_name"
                label="配置名称"
            />

            <el-table-column align="center" prop="config_value" label="配置值">
                <template slot-scope="{row}">
                    <img v-if="row.config_type == 5" width="50px" height="50px" :src="row.config_value">
                    <span v-else class="config-value">{{ row.config_value }}</span>
                </template>
            </el-table-column>

            <el-table-column
                show-overflow-tooltip
                prop="config_group"
                label="配置分组"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="config_type"
                label="配置类型"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="config_sort"
                label="排序"
                align="center"
            />

            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>

            <el-table-column align="center" prop="is_check" label="启用状态">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_check | statusFilter">
                        <i :class="row.is_check == 1 ? 'el-icon-unlock' : 'el-icon-lock'" />
                        {{ row.is_check | checkFilter }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column
                fixed="right"
                label="操作"
                align="center"
            >
                <template v-slot="{row}">
                    <!-- 状态变更 -->
                    <el-button v-if="row.is_check == 0" type="text" icon="el-icon-unlock"
                               @click="changeStatus(row, 1)">
                        <el-tag :type="1 | statusFilter">
                            启用
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_check == 1" type="text" icon="el-icon-lock"
                               @click="changeStatus(row, 0)">
                        <el-tag :type="0 | statusFilter">
                            禁用
                        </el-tag>
                    </el-button>
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
            background
            :current-page="queryForm.page"
            :page-size="queryForm.limit"
            :layout="layout"
            :total="total"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
        />
    </div>
</template>

<script>
    import {getList, setDel, changeFiledStatus, pushRefreshConfig} from '@/api/configs'
    import waves from '@/directive/waves' // waves directive
    import {parseTime} from '@/utils/index';

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ]

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name
        return acc
    }, {})

    export default {
        name: 'UserManagement',
        components: {},
        directives: {waves},
        filters: {
            parseTime: parseTime,
            statusFilter(status) {
                const statusMap = {
                    1: 'success',
                    0: 'danger'
                }
                return statusMap[status]
            },
            checkFilter(type) {
                return calendarCheckKeyValue[type] || ''
            }
        },
        data() {
            return {
                is_batch: 0, // 默认不开启批量删除
                layout: 'total, sizes, prev, pager, next, jumper',
                selectRows: '',
                elementLoadingText: '正在加载...',
                queryForm: {
                    page: 1,
                    limit: 10,
                    search: '',
                    is_check: ''
                },
                downloadLoading: false,
                calendarCheckOptions,

                list: [],
                total: 0,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 20,
                    is_check: ''
                },
                importanceOptions: [1, 2, 3],
                statusOptions: ['published', 'draft', 'deleted'],
                temp: {
                    id: undefined,
                    importance: 1,
                    remark: '',
                    timestamp: new Date(),
                    title: '',
                    type: '',
                    status: 'published'
                },
                dialogFormVisible: false,
                dialogStatus: '',
                rules: {
                    type: [{required: true, message: 'type is required', trigger: 'change'}],
                    timestamp: [{type: 'date', required: true, message: 'timestamp is required', trigger: 'change'}],
                    title: [{required: true, message: 'title is required', trigger: 'blur'}]
                }
            }
        },
        created() {
            this.getList()
        },
        methods: {
            handleDownload() {
                this.downloadLoading = true
                import('@/vendor/Export2Excel').then((excel) => {
                    const tHeader = [
                        'Id',
                        '管理员',
                        '邮箱',
                        '登录次数',
                        '创建时间',
                        '启用状态'
                    ]
                    const filterVal = [
                        'config_id',
                        'admin_name',
                        'admin_email',
                        'admin_info.login_num',
                        'admin_info.created_time',
                        'is_check'
                    ]
                    const data = this.formatJson(filterVal)
                    excel.export_json_to_excel({
                        header: tHeader,
                        data,
                        filename: 'table-list'
                    })
                    this.downloadLoading = false
                })
            },
            formatJson(filterVal) {
                return this.list.map((v) =>
                    filterVal.map((j) => {
                        if (j === 'admin_info.created_time') {
                            return parseTime(v.admin_info.created_time)
                        } else if (j === 'is_check') {
                            return this.checkFilter(v[j])
                        } else if (j === 'admin_info.login_num') {
                            return v.admin_info.login_num
                        } else {
                            return v[j]
                        }
                    })
                )
            },
            checkFilter(val) {
                return calendarCheckKeyValue[val] || ''
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            // 新增与编辑
            handleEdit(row) {
                var query = {};
                if (row.config_id) query.config_id = row.config_id;
                console.log(query);
                this.$router.push({
                    'path':`/configs/detail`,
                    'query': query,
                });
            },
            handleDelete(row) {
                var ids = '';
                if (row.config_id) {
                    ids = row.config_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.config_id).join();
                    } else {
                        this.$message('未选中任何行', 'error');
                        return false
                    }
                }

                // 删除流程
                this.$confirm(
                    '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                    'Warning',
                    {
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    })
                    .then(async () => {
                        const {status, msg} = await setDel({config_id: ids, 'is_batch': this.is_batch});

                        switch (status) {
                            case 1:
                                // this.list.splice($index, 1);
                                this.getList();

                                this.$message({
                                    type: 'success',
                                    message: msg
                                });
                                break;
                            default:
                                this.$message({
                                    type: 'error',
                                    message: msg
                                });
                                break;
                        }

                    })
                    .catch(err => {
                        console.error(err)
                    })
            },
            handleSizeChange(val) {
                this.queryForm.limit = val
                this.getList()
            },
            handleCurrentChange(val) {
                this.queryForm.page = val
                this.getList()
            },
            handleFilter() {
                this.queryForm.page = 1
                this.getList()
            },
            async getList() {
                this.listLoading = true
                const {data} = await getList(this.queryForm)
                this.list = data.data
                console.log(data)
                this.total = data.total
                setTimeout(() => {
                    this.listLoading = false
                }, 300)
            },
            // 状态变更
            async changeStatus(row, value) {
                const {data, msg, status} = await changeFiledStatus({
                    'config_id': row.config_id,
                    'change_field': 'is_check',
                    'change_value': value
                });

                // 设置成功之后，同步到当前列表数据
                if (status == 1) row.is_check = value;
                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });
            },
            // 同步配置到文件中
            async pushRefresh() {
                this.listLoading = true;
                const {msg, status} = await pushRefreshConfig();

                this.$message({
                    message: msg,
                    type: status == 1 ? 'success' : 'error',
                });

                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
        }
    }
</script>

<style scoped>
    span.config-value {
        max-height: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        word-break: break-all;
    }
</style>
