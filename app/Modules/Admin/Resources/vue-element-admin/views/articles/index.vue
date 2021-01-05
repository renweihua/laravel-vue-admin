<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="listQuery.search"
                placeholder="请输入 文章标题"
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

        <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
            <el-table-column align="center" label="Id">
                <template slot-scope="scope">
                    <span>{{ scope.row.article_id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="标题" min-width="150px" max-width="300px">
                <template slot-scope="{row}">
                    <router-link :to="'./edit/'+row.article_id" class="link-type">
                        <span>{{ row.article_title }}</span>
                    </router-link>
                </template>
            </el-table-column>

            <el-table-column align="center" label="分类名称">
                <template slot-scope="{row}">
                    {{ row.category? row.category.category_name : ('Id：' + row.category_id) }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="封面">
                <template slot-scope="{row}">
                    <img v-if="row.article_cover" :src="row.article_cover">
                </template>
            </el-table-column>

            <el-table-column align="center" label="排序">
                <template slot-scope="{row}">
                    {{ row.article_sort }}
                </template>
            </el-table-column>

            <el-table-column align="center" label="创建时间" width="150px">
                <template slot-scope="{row}">
                    {{ row.created_time | parseTime('{y}-{m}-{d} {h}:{i}') }}
                </template>
            </el-table-column>

            <el-table-column width="120px" align="center" label="是否置顶">
                <template slot-scope="{row}">
                    <span>{{ row.set_top }}</span>
                </template>
            </el-table-column>

            <el-table-column width="120px" align="center" label="是否推荐">
                <template slot-scope="{row}">
                    <span>{{ row.is_recommend }}</span>
                </template>
            </el-table-column>

            <el-table-column class-name="status-col" label="Status" width="110">
                <template slot-scope="{row}">
                    <el-tag :type="row.is_check | statusFilter">
                        {{ row.is_check }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center"
                             label="操作"
                             width="230">
                <template slot-scope="scope">
                    <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.row)"> 编辑 </el-button>
                    <el-button type="text" icon="el-icon-delete" @click="handleDelete(scope.row)"> 删除 </el-button>
                </template>
            </el-table-column>
        </el-table>

        <pagination
                v-show="total>0"
                :total="total"
                :page.sync="listQuery.page"
                :limit.sync="listQuery.limit"
                @pagination="getList"
        />
    </div>
</template>

<script>
    import {getList, setDel} from '@/api/articles';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import waves from '@/directive/waves';
    import {getFormatDate, parseTime} from "@/utils"; // waves directive

    const calendarCheckOptions = [
        {key: '-1', display_name: '全部'},
        {key: '1', display_name: '启用'},
        {key: '0', display_name: '禁用'}
    ];

    const calendarCheckKeyValue = calendarCheckOptions.reduce((acc, cur) => {
        acc[cur.key] = cur.display_name;
        return acc;
    }, {});

    export default {
        name: 'ArticleList',
        components: {Pagination},
        directives: {waves},
        filters: {
            statusFilter(status) {
                const statusMap = {
                    published: 'success',
                    draft: 'info',
                    deleted: 'danger',
                };
                return statusMap[status];
            },
            checkFilter(type) {
                return calendarCheckKeyValue[type] || '';
            },
        },
        data() {
            return {
                list: [],
                total: 0,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 20,
                },
                downloadLoading: false,
                calendarCheckOptions,
            }
        },
        created() {
            this.getList();
        },
        methods: {
            handleFilter() {
                this.listQuery.page = 1;
                this.getList();
            },
            async getList() {
                this.listLoading = true;
                const {data} = await getList(this.listQuery);
                this.list = data.data;
                console.log(data)
                this.total = data.total;
                this.listQuery.page = parseInt(data.current_page) + 1;
                this.listQuery.limit = data.per_page || 10;
                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            handleDelete(row) {
                var ids = '';
                if (row.article_id) {
                    ids = row.article_id;
                } else {
                    if (this.selectRows.length > 0) {
                        ids = this.selectRows.map((item) => item.article_id).join();
                    } else {
                        this.$message('未选中任何行', 'error');
                        return false;
                    }
                }
                this.$confirm(
                    '你确定要删除操作吗？删除之后将无法恢复，请谨慎操作',
                    'Warning',
                    {
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                    })
                    .then(async () => {
                        const {status, msg} = await setDel({article_id: ids, 'is_batch' : this.is_batch});
                        this.$message({
                            message: msg,
                            type: 'success'
                        });
                        // 重新加载列表
                        this.handleFilter();
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            // 新增与编辑
            handleEdit(row) {
                var query = {};
                if (row.article_id) query.article_id = row.article_id;
                console.log(query);
                this.$router.push({
                    'path':`/articles/detail`,
                    'query': query,
                });
            },
            // 下载
            handleDownload() {
                this.downloadLoading = true
                import('@/vendor/Export2Excel').then((excel) => {
                    const tHeader = [
                        'Id',
                        '文章标题',
                        '创建时间',
                        '启用状态'
                    ];
                    const filterVal = [
                        'article_id',
                        'role_name',
                        'created_time',
                        'is_check'
                    ];
                    const data = this.formatJson(filterVal);
                    excel.export_json_to_excel({
                        header: tHeader,
                        data,
                        filename: '文章列表-' + getFormatDate()
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

<style scoped>
    .edit-input {
        padding-right: 100px;
    }

    .cancel-btn {
        position: absolute;
        right: 15px;
        top: 10px;
    }
</style>
