<template>
    <div class="bg-light add-topic">
        <!--提示信息-->
        <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
        <!--标题头-->
        <h5 class="text-center" style="font-weight: bold; color: #636B6F;">
            <i class="fa fa-paint-brush"></i>新建话题
        </h5>
        <form v-model="form">
            <div class="form-group">
                <!--标题-->
                <input type="text" v-model="form.title" class="form-control" placeholder="标题">
            </div>
            <!--分类-->
            <div class="form-group">
                <label for="cateFormControlSelect">选择分类</label>
                <select class="form-control" id="cateFormControlSelect" v-model="form.category_id">
                    <option v-for="cate in cates" :value="cate.id" name="category_id">{{ cate.name }}</option>
                </select>
            </div>
            <!--编辑区 -->
            <div class="form-group">
                <textarea id="editor"></textarea>
            </div>
            <!--发布按钮-->
            <input type="button" class="btn btn-primary" @click="add" value="发布话题">
            <!--<button type="submit" class="btn btn-primary" @click="add">发布话题</button>-->
        </form>
    </div>
</template>
<script>
    import SimpleMDE from 'simplemde'
    import hljs from 'highlight.js'
    import { list } from "@/api/cate";
    import { addTopic } from "@/api/topic";

    window.hljs = hljs

    export default {
        name: 'Create',
        data() {
            return {
              form:{
                  title:"",
                  content:""
              },
              cates:"",
              msg:""
            };
        },
        mounted() {
            list().then(res => {
                this.cates = res.data;
            });
            const simplemde = new SimpleMDE({
                element: $('#editor')[0],
                placeholder: '请使用 Markdown 格式书写 ;-)，代码片段黏贴时请注意使用高亮语法。',
                spellChecker: false,
                autoDownloadFontAwesome: false,
                autosave: {
                    enabled: true,
                    uniqueId: 'content'
                },
                showIcons: ["code", "table"],
                autofocus:true,
                renderingConfig: {
                    codeSyntaxHighlighting: true
                }
            });
            simplemde.codemirror.on('change', () => {
                // 将改变后的值赋给文章内容
                this.form.content = simplemde.value()
            });
            this.simplemde = simplemde;
        },
        methods:{
            add(){
                addTopic(this.form).then(res => {
                    if (res.code == 200) {
                        this.simplemde.value('');
                        this.$router.push({ name: "Index" });
                    } else {
                        this.msg = res.msg;
                        $(".alert-danger")
                            .removeClass("d-none")
                            .addClass("d-show");
                    }
                });
            }
        }
    }
</script>
<style scoped>
    .add-topic{ padding:20px;}
</style>