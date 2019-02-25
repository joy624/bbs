<template>
  <div class="row">
    <div class="col-12">
      <div class="bg-light edit-topic">
        <!--提示信息-->
        <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
        <!--标题头-->
        <h5 class="text-center" style="font-weight: bold; color: #636B6F;">
          <i class="fa fa-paint-brush"></i>编辑话题
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
          <input type="hidden" v-model="form.id">
          <!--发布按钮-->
          <input type="button" class="btn btn-primary" @click="edit" value="发布主题">
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import SimpleMDE from 'simplemde'
  import hljs from 'highlight.js'
  import { list } from "@/api/cate";
  import { editTopic, viewTopic } from "@/api/topic";

  window.hljs = hljs
  export default {
    name: "EditTopic",
    data() {
      return {
        form:{
          title:"",
          content:"",
          id:""
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
        showIcons: ["code"],
        autofocus:true,
        renderingConfig: {
          codeSyntaxHighlighting: true
        }
      });
      viewTopic(this.$route.query.id).then(res => {
        this.form = res.data;
        simplemde.value(escape2Html(this.form.content));
        simplemde.codemirror.on('change', () => {
          // 将改变后的值赋给文章内容
          this.form.content = simplemde.value()
        });
      });
      this.simplemde = simplemde;
    },
    methods:{
      edit(){
        editTopic(this.form).then(res => {
          if (res.code == 200) {
            this.simplemde.value('');
            this.$router.push({ name: "Home" });
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
  function escape2Html(str) {
    var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"','apos':'\''};
    return str.replace(/&(lt|gt|nbsp|amp|quot|apos);/ig,function(all,t){return arrEntities[t];});
  }
</script>

<style scoped>
  .edit-topic{ padding:20px;}
</style>