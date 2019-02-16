<template>
  <div class="row">
    <div class="col-12">
      <div class="topic">
        <!--主题详情-->
        <div class="panel bg-light" style="padding:20px;">
          <!-- 标题 -->
          <h5>{{data.title}}</h5>
          <span
              class="small panel-subtitle mb-2 text-muted"
          >创建于：{{ data.create_time }} / 阅读数 {{data.hits}} /点赞数 {{ data.likenum }} / 更新于{{ data.update_time }}</span>
          <span v-if="data.user_id == $store.getters.login_id">
        <button type="button" class="btn btn-link opt" @click="gotoEditAddTopic(data)">编辑</button>
        <button type="button" class="btn btn-link opt" @click="delTopic(data.id)">删除</button>
      </span>
          <hr class="simple" color="#D9DADB">
          <!-- 内容 -->
          <!--<div class="panel">{{data.content}}</div>-->
          <!--<div class="panel">{{data.content}}</div>-->
          <div class="panel"><div class="markdown-body" v-html="data.content"></div></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { viewTopic } from "@/api/topic";
  import { delTopic } from "@/api/topic";

  import SimpleMDE from 'simplemde'
  import hljs from 'highlight.js'
  window.hljs = hljs

  export default {
    name: "Topic",
    data() {
      return {
        data: "",
        editdata: "",
        replies: "",
        reply_content: "",
        reply_id: "",
        reply_index: "",
        msg: ""
      };
    },
    mounted() {
      viewTopic(this.$route.query.id).then(res => {
        if (res.code == 200) {
          this.data =   res.data;
          this.data.content = SimpleMDE.prototype.markdown(this.data.content);
          this.$nextTick(() => {
            this.$el.querySelectorAll('pre code').forEach((el) => {
              hljs.highlightBlock(el)
            })
          })
        }
      });
    },
    methods: {
      gotoEditAddTopic(data) {
        this.$router.push({ name: "EditTopic", params:{data:data} });
      },
      delTopic(id) {
        delTopic(id).then(res => {
          if (res.code == 200) {
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
</script>

<style scoped>
  .reply span {
    color: #385f8a;
  }

  .add-reply button {
    margin-top: 10px;
  }
  .opt {
    color: #adadad;
    font-size: 12px;
  }
</style>