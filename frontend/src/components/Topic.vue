<template>
  <div class="row">
    <div class="col-12">
      <div class="topic">
        <!--主题详情-->
        <div class="panel bg-light" style="padding:20px;">
          <!-- 标题 -->
          <h5>{{topic.title}}</h5>
          <span
                  class="small panel-subtitle mb-2 text-muted"
          >创建于：{{ topic.create_time }} / 阅读数 {{topic.hits}} /点赞数 {{ topic.likenum }} / 更新于{{ topic.update_time }}</span>
          <span v-if="topic.user_id == $store.getters.login_id">
            <button type="button" class="btn btn-link opt" @click="gotoEditAddTopic(topic.id)">编辑</button>
            <button type="button" class="btn btn-link opt" @click="tipShow = true">删除</button>
          </span>
          <button v-if="!is_like" type="button" class="btn btn-link opt" @click="likeTopic(topic.id)"><i class="fa fa-thumbs-up"></i>点赞
          </button>
          <button v-else type="button" class="btn btn-link opt" @click="cancelLikeTopic(topic.id)"><i class="fa fa-thumbs-up"></i>取消点赞
          </button>
          <hr class="simple" color="#D9DADB">
          <div class="panel">
            <div class="markdown-body" v-html="topic.content"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 删除主题确认框 -->
    <el-dialog class="w-30" title="提示" :visible.sync="tipShow">
      <span>确认删除当前主题？</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="tipShow = false">取 消</el-button>
        <el-button type="primary" @click="delTopic(topic.id)">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import {viewTopic} from "@/api/topic";
  import {delTopic} from "@/api/topic";
  import {isLikeTopic} from "@/api/topic";
  import {addLikeTopic} from "@/api/topic";
  import {delLikeTopic} from "@/api/topic";

  import SimpleMDE from 'simplemde'
  import hljs from 'highlight.js'

  window.hljs = hljs

  export default {
    name: "Topic",
    data() {
      return {
        tipShow: false,
        topic: "",
        editdata: "",
        replies: "",
        reply_content: "",
        reply_id: "",
        reply_index: "",
        is_like:0,
        msg: ""
      };
    },
    mounted() {
      this.loadTopic();
      isLikeTopic(this.$route.params.id).then(res => {
        if (res.code == 200) {
          this.is_like = res.data;
        }
      })
    },
    methods: {
      gotoEditAddTopic(topic_id) {
        this.$router.push({name: "EditTopic", query: {id: topic_id}});
      },
      delTopic(id) {
        delTopic(id).then(res => {
          if (res.code == 200) {
            this.$router.push({name: "Home"});
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      },
      loadTopic() {
        viewTopic(this.$route.params.id).then(res => {
          if (res.code == 200) {
            this.topic = res.data;
            this.topic.content = SimpleMDE.prototype.markdown(this.topic.content);
            this.$nextTick(() => {
              this.$el.querySelectorAll('pre code').forEach((el) => {
                hljs.highlightBlock(el)
              })
            })
          }
        });
      },
      cancelLikeTopic(topic_id) {
        delLikeTopic(topic_id).then(res => {
          if (res.code == 200) {
            this.is_like = 0;
          }
        });
      },
      likeTopic(topic_id) {
        addLikeTopic(topic_id).then(res => {
          if (res.code == 200) {
            this.is_like = 1;
          }
        });
      },
    },
    watch: {
      "$route.params": "loadTopic"
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