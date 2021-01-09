<template>
    <div class="createPost-container">
        <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container" label-width="90px">
            <sticky :z-index="10" :class-name="'sub-navbar publish'">
                <SourceUrlDropdown v-model="postForm.article_link"/>
                <el-button v-loading="loading" style="margin-left: 10px;" type="success" @click="submitForm">
                    Publish
                </el-button>
            </sticky>

            <div class="createPost-main-container">
                <el-form-item prop="article_title" label="文章标题">
                    <MDinput v-model="postForm.article_title" :maxlength="100" required>
                        Title
                    </MDinput>
                </el-form-item>

                <el-form-item label="父级菜单">
                    <el-select v-model="postForm.category_id" placeholder="请选择文章分类" autocomplete="off" class="article-textarea">
                        <el-option
                            v-for="item in category"
                            :key="item.category_id"
                            :checked="item.category_id == postForm.category_id"
                            :label="item.category_name"
                            :value="item.category_id"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item prop="article_cover" label="文章封面">
                    <pan-thumb :image="image_url" :borderRadius="borderRadius" @click="show=true"/>

                    <el-button
                        id="img-btn"
                        type="primary"
                        icon="el-icon-upload"
                        @click="show=true"
                    >
                        文章封面
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

                <el-form-item label-width="70px" label="关键字:">
                    <el-input v-model="postForm.article_keywords" :rows="1" type="textarea" class="article-textarea"
                              autosize placeholder="Please enter the 关键字搜索"/>
                </el-form-item>

                <el-form-item label-width="70px" label="描述:">
                    <el-input v-model="postForm.article_description" :rows="1" type="textarea" class="article-textarea"
                              autosize placeholder="Please enter the 文章描述"/>
                    <span v-show="contentShortLength" class="word-counter">{{ contentShortLength }}words</span>
                </el-form-item>

                <el-form-item prop="article_content">
                    <el-tag class="tag-title">
                        文章内容:
                    </el-tag>
                    <markdown-editor v-model="postForm.article_content" height="400px" />
                </el-form-item>

                <el-form-item label="排序" prop="article_sort">
                    <el-input v-model.trim="postForm.article_sort" type="number" autocomplete="off"/>
                </el-form-item>

                <el-form-item label="是否置顶" prop="set_top">
                    <el-radio-group v-model="postForm.set_top">
                        <el-radio :label="0" :checked="postForm.set_top == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.set_top == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="是否推荐" prop="is_recommend">
                    <el-radio-group v-model="postForm.is_recommend">
                        <el-radio :label="0" :checked="postForm.is_recommend == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.is_recommend == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="是否公开" prop="is_public">
                    <el-radio-group v-model="postForm.is_public">
                        <el-radio :label="0" :checked="postForm.is_public == 0 ? 'checked' : ''">否</el-radio>
                        <el-radio :label="1" :checked="postForm.is_public == 1 ? 'checked' : ''">是</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="文章来源" prop="article_origin">
                    <el-input v-model.trim="postForm.article_origin" type="url" autocomplete="off"/>
                </el-form-item>

                <el-form-item label="文章作者" prop="article_author">
                    <el-input v-model.trim="postForm.article_author" autocomplete="off"/>
                </el-form-item>

            </div>
        </el-form>
    </div>
</template>

<script>
    import MarkdownEditor from '@/components/MarkdownEditor'
    import myUpload from '@/components/Uploads/image/index';
    import PanThumb from '@/components/PanThumb';
    import MDinput from '@/components/MDinput'
    import Sticky from '@/components/Sticky' // 粘性header组件
    import {validURL} from '@/utils/validate'
    import {SourceUrlDropdown} from './Dropdown';
    import {getUploadUrl} from '@/api/common';

    import {detail, create, update} from '@/api/articles'
    import {getCategorySelect} from "@/api/article_categories";

    const defaultForm = {
        article_id: 0,
        article_title: '', // 文章题目
        category_id: 0, // 分类
        article_content: '', // 文章内容
        article_keywords: '', // 关键字
        article_description: '', // 文章摘要
        article_cover: '', // 封面图
        article_link: '', // 文章外链
        article_sort: 99, // 排序
        set_top:0, // 是否置顶
        is_recommend:0, // 是否推荐
        is_public:0, // 是否公开
        article_origin:'', // 文章来源
        article_author:'', // 文章作者
    };

    export default {
        name: 'ArticleDetail',
        components: {MDinput, Sticky, SourceUrlDropdown,
            'my-upload': myUpload,
            PanThumb,
            MarkdownEditor
        },
        props: {
            isEdit: {
                type: Boolean,
                default: false
            }
        },
        data() {
            const validateRequire = (rule, value, callback) => {
                if (value === '') {
                    this.$message({
                        message: rule.field + '为必填项',
                        type: 'error'
                    })
                    callback(new Error(rule.field + '为必填项'))
                } else {
                    callback();
                }
                return;
            }
            const validateSourceUri = (rule, value, callback) => {
                if (value) {
                    if (validURL(value)) {
                        callback()
                    } else {
                        this.$message({
                            message: '外链url填写不正确',
                            type: 'error'
                        })
                        callback(new Error('外链url填写不正确'))
                    }
                } else {
                    callback()
                }
            }
            return {
                postForm: Object.assign({}, defaultForm),
                loading: false,
                userListOptions: [],
                rules: {
                    article_title: [{validator: validateRequire}],
                    category_id: [{validator: validateRequire}],
                    article_cover: [{validator: validateRequire}],
                    article_content: [{validator: validateRequire}],
                    article_link: [{validator: validateSourceUri, trigger: 'blur'}]
                },
                tempRoute: {},
                // 图片上传
                show: false,
                size: 2.1,

                // 图片上传
                upload_url: '',
                image_url: '',
                borderRadius:'initial',

                category:[], // 分类
            }
        },
        computed: {
            contentShortLength() {
                return this.postForm.article_description?.length;
            },
            lang() {
                return this.$store.getters.language;
            },
        },
        created() {
            console.log('Article-detail');
            console.log(this.$route);
            if (this.isEdit) {
                const article_id = this.$route.query && this.$route.query.article_id;
                if (article_id > 0) this.getDetail(article_id);
            }

            // Why need to make a copy of this.$route here?
            // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
            // https://github.com/PanJiaChen/vue-element-admin/issues/1221
            this.tempRoute = Object.assign({}, this.$route);

            // 图片上传路径
            this.upload_url = getUploadUrl();
            // 文章分类列表
            this.getCategorySelect();
        },
        methods: {
            // 获取菜单列表
            async getCategorySelect() {
                const res = await getCategorySelect();
                this.category = res.data;
            },
            // 获取文章详情
            getDetail(article_id) {
                detail(article_id).then(response => {
                    console.log(response);

                    this.postForm = response.data;
                    // 默认展示的封面图
                    this.image_url = this.postForm.article_cover;

                    // set page title
                    this.setPageTitle();
                }).catch(err => {
                    console.log(err);
                })
            },
            setTagsViewTitle() {
                const title = this.lang === 'zh' ? '编辑文章' : 'Edit Article';
                const route = Object.assign({}, this.tempRoute, {title: `${title}-${this.postForm.article_id}`});
                this.$store.dispatch('tagsView/updateVisitedView', route);
            },
            setPageTitle() {
                const title = 'Edit Article';
                document.title = `${title} - ${this.postForm.article_id}`;
            },
            submitForm() {
                // console.log(this.postForm);
                this.$refs.postForm.validate(async valid => {
                    if (valid) {
                        this.loading = true;

                        const {msg, status} = this.postForm.article_id > 0 ? await update(this.postForm) : await create(this.postForm);
                        if (status == 1){
                            this.$notify({
                                title: '成功',
                                message: this.postForm.article_id > 0 ? '编辑文章成功' : '发布文章成功',
                                type: 'success',
                                duration: 2000,
                            });

                            // 返回文章列表
                            this.$router.push(`/articles`);
                        }else{
                            this.$message({
                                message: msg,
                                type: 'error',
                            });
                        }

                        this.loading = false;
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                })
            },
            cropSuccess(imgDataUrl, field) {
                console.log('-------- crop success --------', imgDataUrl, field);
            },
            // 上传成功回调
            cropUploadSuccess(result, field) {
                this.image_url = result.path_url;
                this.postForm.article_cover = result.data;
            },
            // 上传失败回调
            cropUploadFail(status, field) {
                console.log('上传失败状态' + status);
                console.log('field: ' + field);
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import "~@/styles/mixin.scss";

    .createPost-container {
        position: relative;

        .el-form {
            .el-form-item{
                margin-bottom: 40px;
            }
        }

        .createPost-main-container {
            padding: 40px 45px 20px 50px;

            .postInfo-container {
                position: relative;
                @include clearfix;
                margin-bottom: 10px;

                .postInfo-container-item {
                    float: left;
                }
            }
        }

        .word-counter {
            width: 40px;
            position: absolute;
            right: 10px;
            top: 0px;
        }
    }

    .article-textarea ::v-deep {
        textarea {
            padding-right: 40px;
            resize: none;
            border: none;
            border-radius: 0px;
            border-bottom: 1px solid #bfcbd9;
        }
    }
</style>
