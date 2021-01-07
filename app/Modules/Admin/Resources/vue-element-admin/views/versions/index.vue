<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                    v-model="listQuery.search"
                    placeholder="请输入 版本名称|版本号"
                    style="width: 200px;"
                    class="filter-item"
                    @keyup.enter.native="handleFilter"
            />
            <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
            </el-button>
            <el-button v-waves class="filter-item" type="danger" icon="el-icon-delete" @click="handleDelete">
                批量删除
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
                    type="primary"
                    icon="el-icon-download"
                    @click="handleDownload"
            >
                {{ $t('table.export') }}
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
                    prop="version_id"
                    label="Id"
            />

            <el-table-column
                    show-overflow-tooltip
                    prop="version_name"
                    label="版本名称"
                    align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="version_number"
                label="版本号"
                align="center"
            />

            <el-table-column
                    show-overflow-tooltip
                    prop="version_sort"
                    label="排序"
                    align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="publish_date"
                label="版本发布时间"
                align="center"
            />

            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.created_time | parseTime("{y}-{m}-{d} {h}:{i}") }}
                </template>
            </el-table-column>

            <el-table-column
                    fixed="right"
                    label="操作"
                    align="center"
            >
                <template v-slot="{row}">
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(row)">编辑</el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <!-- 分页 -->
        <el-pagination
                background
                :current-page="listQuery.page"
                :page-size="listQuery.limit"
                :layout="layout"
                :total="total"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
        />
        <!-- 详情 -->
        <edit ref="edit" @fetchData="getList"/>
    </div>
</template>

<script>
    import {getList, setDel, changeFiledStatus} from '@/api/versions';
    import waves from '@/directive/waves' // waves directive
    import Edit from './components/detail'
    import {parseTime, getFormatDate} from '@/utils/index';

    export default {
        name: 'Versions',
        components: {Edit},
        directives: {waves},
        filters: {
            parseTime: parseTime,
            getFormatDate: getFormatDate,
        },
        data() {
            return {
                is_batch: 0, // 默认不开启批量删除
                list: [],
                listLoading: true,
                layout: 'total, sizes, prev, pager, next, jumper',
                total: 0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    page: 1,
                    limit: 10,
                    search: '',
                    is_check: ''
                },
                downloadLoading: false,
            }
        },
        created() {
            this.getList();
        },
        methods: {
            handleDownload() {
                this.downloadLoading = true
                import('@/vendor/Export2Excel').then((excel) => {
                    const tHeader = [
                        'Id',
                        'Banner标题',
                        '封面',
                        '外链（URL）',
                        '创建时间',
                        '启用状态'
                    ];
                    const filterVal = [
                        'version_id',
                        'version_name',
                        'version_cover',
                        'version_link',
                        'created_time',
                        'is_check'
                    ];
                    const data = this.formatJson(filterVal);
                    excel.export_json_to_excel({
                        header: tHeader,
                        data,
                        filename: 'Banner列表-' + getFormatDate()
                    });
                    this.downloadLoading = false;
                })
            },
            formatJson(filterVal) {
                return this.list.map((v) =>
                    filterVal.map((j) => {
                        switch (j) {
                            case 'created_time':
                                return parseTime(v[j]);
                                break;
                            case 'version_cover':
                                return v.cover.file_path;
                                break;
                            default:
                                return v[j];
                                break;
                        }
                    })
                )
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleEdit(row) {
                if (row) {
                    this.$refs['edit'].showEdit(row);
                } else {
                    this.$refs['edit'].showEdit({});
                }
            },
            handleDelete(row) {
                var ids = '';
                if (row.version_id) {
                    ids = row.version_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.version_id).join();
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
                        const {status, msg} = await setDel({version_id: ids, 'is_batch' : this.is_batch});

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
                this.listQuery.limit = val;
                this.getList();
            },
            handleCurrentChange(val) {
                this.listQuery.page = val
                this.getList()
            },
            handleFilter() {
                this.listQuery.page = 1
                this.getList()
            },
            async getList() {
                this.listLoading = true
                const {data} = await getList(this.listQuery)
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
                    'version_id': row.version_id,
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
        }
    }
</script>
