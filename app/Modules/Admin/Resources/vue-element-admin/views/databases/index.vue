<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入Banner标题"
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
                v-waves
                :loading="downloadLoading"
                class="filter-item"
                type="success"
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
                prop="Name"
                label="表名"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="Comment"
                label="表注释"
                align="center"
            />
            <el-table-column
                show-overflow-tooltip
                prop="Engine"
                label="存储引擎"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Rows"
                label="行数"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Data_length"
                label="数据"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Index_length"
                label="索引"
                align="center"
            />

            <el-table-column
                show-overflow-tooltip
                prop="Total_length"
                label="全部"
                align="center"
            />
            <el-table-column label="创建时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.Create_time }}
                </template>
            </el-table-column>
            <el-table-column label="修改时间" show-overflow-tooltip align="center">
                <template slot-scope="{ row }">
                    {{ row.Update_time }}
                </template>
            </el-table-column>

            <el-table-column
                fixed="right"
                label="操作"
                align="center"
            >
                <template v-slot="{row}">
                    <!-- 编辑与删除 -->
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import {getList, backupsTables} from '@/api/databases';
    import waves from '@/directive/waves'; // waves directive
    import {parseTime, getFormatDate} from '@/utils/index';

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ];

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name
        return acc
    }, {});

    export default {
        name: 'bannerManage',
        components: {},
        directives: {waves},
        filters: {
            parseTime: parseTime,
            getFormatDate: getFormatDate,
            statusFilter(status) {
                const statusMap = {
                    1: 'success',
                    0: 'danger'
                };
                return statusMap[status];
            },
            checkFilter(type) {
                return calendarCheckKeyValue[type] || '';
            }
        },
        data() {
            return {
                is_batch: 0, // 默认不开启批量删除
                list: [],
                listLoading: true,
                total: 0,
                selectRows: '',
                elementLoadingText: '正在加载...',
                listQuery: {
                    search: '',
                    is_check: '',
                    is_download: 0, // 是否下载：1.是；默认0
                },
                downloadLoading: false,
                calendarCheckOptions
            }
        },
        created() {
            this.getList();
        },
        methods: {
            checkFilter(val) {
                return calendarCheckKeyValue[val] || '';
            },
            setSelectRows(val) {
                this.selectRows = val;
                this.is_batch = 1;
            },
            handleDelete(row) {
                var ids = '';
                if (row.banner_id) {
                    ids = row.banner_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.banner_id).join();
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
                        const {status, msg} = await setDel({banner_id: ids, 'is_batch': this.is_batch});

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

            handleFilter() {
                this.listQuery.is_download = 0;
                this.getList();
            },
            async getList(callback) {
                this.listLoading = true;
                const {data, status, msg} = await getList(this.listQuery);
                console.log(data);
                if(this.listQuery.is_download == 1){
                    if (callback){
                        callback(data, status, msg);
                    }
                }else{
                    this.list = data.data;
                    this.total = data.table_total;
                }
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 状态变更
            async changeStatus(row, value) {
                const {data, msg, status} = await changeFiledStatus({
                    'banner_id': row.banner_id,
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
            handleDownload() {
                this.downloadLoading = true;
                this.listQuery.is_download = 1;
                let _this = this;
                this.getList(function (data, status, msg) {
                    // 如果获取失败，那么无需进入下一步
                    if (status != 1) {
                        _this.$message({
                            message: msg,
                            type: 'error',
                        });
                        return;
                    }
                    // 开始导出
                    import('@/vendor/Export2Excel').then((excel) => {
                        const tHeader = [
                            'Id',
                            'Banner标题',
                            '封面',
                            '外链（URL）',
                            '排序',
                            '创建时间',
                            '启用状态'
                        ];
                        const filterVal = [
                            'banner_id',
                            'banner_title',
                            'banner_cover',
                            'banner_link',
                            'banner_sort',
                            'created_time',
                            'is_check'
                        ];
                        const download_list_data = _this.formatJson(data, filterVal);
                        excel.export_json_to_excel({
                            header: tHeader,
                            data: download_list_data,
                            filename: 'Banner列表-' + getFormatDate(),
                        });
                        _this.downloadLoading = false;
                    });
                })
            },
            formatJson(data, filterVal) {
                return data.map((v) =>
                    filterVal.map((j) => {
                        switch (j) {
                            case 'created_time':
                                return parseTime(v[j]);
                                break;
                            case 'is_check':
                                return this.checkFilter(v[j]);
                                break;
                            default:
                                return v[j];
                                break;
                        }
                    })
                )
            },
        }
    }
</script>
