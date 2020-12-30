<template>
    <div class="app-container">
        <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
            <el-table-column align="center" label="Id">
                <template slot-scope="scope">
                    <span>{{ scope.row.article_id }}</span>
                </template>
            </el-table-column>

            <el-table-column label="标题" min-width="150px" max-width="300px">
                <template slot-scope="{row}">
                    <router-link :to="'/example/edit/'+row.article_id" class="link-type">
                        <span>{{ row.article_title }}</span>
                    </router-link>
                </template>
            </el-table-column>

            <el-table-column align="center" label="分类名称">
                <template slot-scope="scope">
                    <span>{{ scope.row.article_category }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="封面">
                <template slot-scope="scope">
                    <span>{{ scope.row.article_cover }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="排序">
                <template slot-scope="scope">
                    <span>{{ scope.row.article_sort }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="创建时间" width="150px">
                <template slot-scope="scope">
                    {{ scope.row.created_time | parseTime('{y}-{m}-{d} {h}:{i}') }}
                </template>
            </el-table-column>

            <el-table-column width="120px" align="center" label="是否置顶">
                <template slot-scope="scope">
                    <span>{{ scope.row.set_top }}</span>
                </template>
            </el-table-column>

            <el-table-column width="120px" align="center" label="是否推荐">
                <template slot-scope="scope">
                    <span>{{ scope.row.is_recommend }}</span>
                </template>
            </el-table-column>

            <el-table-column class-name="status-col" label="Status" width="110">
                <template slot-scope="{row}">
                    <el-tag :type="row.status | statusFilter">
                        {{ row.is_check }}
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Actions" width="120">
                <template slot-scope="scope">
                    <router-link :to="'/example/edit/'+scope.row.article_id">
                        <el-button type="primary" size="small" icon="el-icon-edit">
                            Edit
                        </el-button>
                    </router-link>
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
    import {getList} from '@/api/articles'
    import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

    export default {
        name: 'ArticleList',
        components: {Pagination},
        filters: {
            statusFilter(status) {
                const statusMap = {
                    published: 'success',
                    draft: 'info',
                    deleted: 'danger'
                }
                return statusMap[status]
            }
        },
        data() {
            return {
                list: null,
                total: 0,
                listLoading: true,
                listQuery: {
                    page: 1,
                    limit: 20
                }
            }
        },
        created() {
            this.getList()
        },
        methods: {
            getList() {
                this.listLoading = true
                getList(this.listQuery).then(res => {
                    console.log(res)
                    this.list = res.data.data
                    this.total = res.data.count
                    this.listQuery.page = parseInt(res.data.cur_page) + 1
                    this.listQuery.limit = res.data.limit || 10
                    this.listLoading = false
                })
            }
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
