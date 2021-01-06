<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                    v-model="listQuery.search"
                    placeholder="请输入站点名称"
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
                    prop="link_id"
                    label="Id"
            />
            <el-table-column
                    show-overflow-tooltip
                    prop="link_name"
                    label="站点名称"
                    align="center"
            />
            <el-table-column align="center" prop="link_cover" label="站点图标">
                <template slot-scope="{row}">
                    <img v-if="row.link_cover" width="100px" height="100px" :src="row.link_cover"/>
                </template>
            </el-table-column>
            <el-table-column
                    show-overflow-tooltip
                    prop="link_url"
                    label="站点网址"
            />
            <el-table-column
                    show-overflow-tooltip
                    prop="link_sort"
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
                    <el-button v-if="row.is_check == 0" type="text"
                               @click="changeStatus(row, 1)">
                        <el-tag :type="1 | statusFilter">
                            <i class="el-icon-unlock" />
                            启用
                        </el-tag>
                    </el-button>
                    <el-button v-else-if="row.is_check == 1" type="text"
                               @click="changeStatus(row, 0)">
                        <el-tag :type="0 | statusFilter">
                            <i class="el-icon-lock" />
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
                :current-page="listQuery.page"
                :page-size="listQuery.limit"
                :layout="layout"
                :total="total"
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
        />
        <edit ref="edit" @fetchData="getList"/>
    </div>
</template>

<script>
    import {getList, setDel, changeFiledStatus} from '@/api/friendlinks';
    import waves from '@/directive/waves'; // waves directive
    import Edit from './components/detail';
    import {parseTime, getFormatDate} from '@/utils/index';

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
        name: '',
        components: {Edit},
        directives: {waves},
        filters: {
            parseTime: parseTime,
            getFormatDate: getFormatDate,
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
                downloadLoading: false,
                calendarCheckOptions,

                list: [],
                total: 0,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 20,
                    is_check: '',
                    search: '',
                },
            }
        },
        created() {
            this.getList()
        },
        methods: {
            handleDownload() {
                console.log(getFormatDate());
                this.downloadLoading = true;
                import('@/vendor/Export2Excel').then((excel) => {
                    const tHeader = [
                        'Id',
                        '站点名称',
                        '站点图标（URL）',
                        '站点网址',
                        '创建时间',
                        '启用状态'
                    ];
                    const filterVal = [
                        'link_id',
                        'link_name',
                        'link_cover',
                        'link_url',
                        'created_time',
                        'is_check'
                    ];
                    const data = this.formatJson(filterVal);
                    excel.export_json_to_excel({
                        header: tHeader,
                        data,
                        filename: '友情链接列表-' + getFormatDate()
                    });
                    this.downloadLoading = false
                })
            },
            formatJson(filterVal) {
                return this.list.map((v) =>
                    filterVal.map((j) => {
                        switch (j) {
                            case 'created_time':
                                return parseTime(v[j]);
                                break;
                            case 'is_check':
                                return this.checkFilter(v[j]);
                                break;
                            case 'link_cover':
                                console.log(v.cover);
                                return v.cover.file_path;
                                break;
                            default:
                                return v[j];
                                break;
                        }
                    })
                )
            },
            checkFilter(val) {
                return calendarCheckKeyValue[val] || '';
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleEdit(row) {
                if (row) {
                    this.$refs['edit'].showEdit(row)
                } else {
                    this.$refs['edit'].showEdit()
                }
            },
            handleDelete(row) {
                var ids = '';
                if (row.link_id) {
                    ids = row.link_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.link_id).join();
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
                        const {status, msg} = await setDel({link_id: ids, 'is_batch' : this.is_batch});

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
                this.listQuery.page = val;
                this.getList();
            },
            handleFilter() {
                this.listQuery.page = 1;
                this.getList();
            },
            async getList() {
                this.listLoading = true;
                const {data} = await getList(this.listQuery);
                this.list = data.data;
                console.log(data);
                this.total = data.total;
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 状态变更
            async changeStatus(row, value) {
                const {data, msg, status} = await changeFiledStatus({
                    'link_id': row.link_id,
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
