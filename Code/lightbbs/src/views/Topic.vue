<template>
  <div class="topic">
    <!--主题详情-->
    <div class="panel bg-light" style="width: 50rem; padding:20px;">
      <!-- 标题 -->
      <h5>{{data.title}}</h5>
      <span
        class="small panel-subtitle mb-2 text-muted"
      >创建于：{{ data.create_time }} / 阅读数 {{data.hits}} /点赞数 {{ data.likenum }} / 更新于{{ data.update_time }}</span>
      <span v-if="user.id == data.user_id">
        <button type="button" class="btn btn-link opt" @click="gotoEditAddTopic(data)">编辑</button>
        <button type="button" class="btn btn-link opt" @click="delTopic(data.id)">删除</button>
      </span>
      <hr class="simple" color="#D9DADB">
      <!-- 内容 -->
      <!--<div class="panel">{{data.content}}</div>-->
      <!--<div class="panel">{{data.content}}</div>-->
      <div class="panel"><div class="markdown-body" v-html="data.content"></div></div>
    </div>
    <!--提示信息-->
    <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
    <!-- 回复内容列表 -->
    <div class="reply" style="width:50rem;">
      <ul class="list-group list-group-flush">
        <li class="list-group-item bg-light" v-for="(reply,index) in replies" :key="index">
          <div class="row">
            <div clas="col">
              <img
                :src="'http://my.test.tp/'+reply.user.img_url"
                alt
                class="rounded-circle reply-img"
              >
            </div>
            <div clas="col">
              <div class="row">
                <span>
                  <strong>{{ reply.user.name}}</strong>
                  创建于 {{ reply.create_time}} 更新于 {{ reply.update_time}}
                  <span
                    v-if="user.id == reply.user_id"
                  >
                    <button type="button" class="btn btn-link opt" @click="editReply(reply.id,reply.content,index)">编辑</button>
                    <button type="button" class="btn btn-link opt" @click="delReply(index,reply.id)">删除</button>
                  </span>
                </span>
              </div>
              <div class="row card-text">{{ reply.content}}</div>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- 发表评论 -->
    <div class="add-reply" v-if="user.id">
      <form>
        <textarea
          name="content"
          maxlength="10000"
          class="mll"
          id="reply_content"
          style="overflow: hidden; word-wrap: break-word; resize: none; height: 112px;"
          v-model="reply_content"
        ></textarea>
        <input v-if="reply_id" type="button" class="btn btn-primary" @click="updateReply" value="发布评论"/>
        <input v-else type="button" class="btn btn-primary" @click="addReply(data.id)" value="发布评论"/>
        <!--<button class="btn btn-primary" @click="addReply(data.id)">发表评论</button>-->
      </form>
    </div>
  </div>
</template>
<script>
import { viewTopic } from "@/api/topic";
import { replyIndex } from "@/api/topic";
import { loginUser } from "@/api/user";
import { addTopicReply } from "@/api/topic";
import { editTopicReply } from "@/api/topic";
import { delTopicReply } from "@/api/topic";
import { delTopic } from "@/api/topic";
import SimpleMDE from 'simplemde'
import hljs from 'highlight.js'
window.hljs = hljs

export default {
  data() {
    return {
      data: "",
      editdata: "",
      replies: "",
      user: "",
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
    replyIndex(this.$route.query.id).then(res => {
      if (res.code == 200) {
        this.replies = res.data;
      }
    });
    loginUser().then(res => {
       if (res.code == 200) {
          this.user = res.data;
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
                this.$router.push({ name: "Index" });
            } else {
                this.msg = res.msg;
                $(".alert-danger")
                    .removeClass("d-none")
                    .addClass("d-show");
            }
        });
    },

    editReply(id,content,index) {
        this.reply_content = content;
        this.reply_id = id;
        this.reply_index = index;
    },
    updateReply(){
        editTopicReply({id:this.reply_id,content:this.reply_content}).then(res => {
            if(res.code == 200){
                this.replies.splice(this.reply_index,1);
                this.replies.push(res.data);
                $(".alert-danger").addClass("d-none");
                this.reply_content = "";
                this.reply_id = "";
                this.reply_index = "";
            }else{
                this.msg = res.msg;
                $(".alert-danger")
                    .removeClass("d-none")
                    .addClass("d-show");
            }
        });
    },
    delReply(index,id) {
      delTopicReply(id).then(res => {
        if (res.code == 200) {
            this.replies.splice(index,1);
        } else {
          this.msg = res.msg;
          $(".alert-danger")
            .removeClass("d-none")
            .addClass("d-show");
        }
      });
    },
    addReply(id) {
      addTopicReply({topic_id:id,content:this.reply_content}).then(res => {
          if(res.code == 200){
              this.replies.push(res.data);
              $(".alert-danger").addClass("d-none");
              this.reply_content = "";
          }else{
            this.msg = res.msg;
            $(".alert-danger")
              .removeClass("d-none")
              .addClass("d-show");
            }
      });
    }
  }
};
</script>
<style scoped>
.reply {
  font-size: 14px;
  margin-top: 20px;
  margin-bottom: 20px;
}
.reply span {
  color: #385f8a;
}
.reply-img {
  width: 44px;
  height: 44px;
  margin-right: 25px;
}

.mll {
  border-radius: 3px;
  padding: 5px;
  font-size: 14px;
  border: 1px solid #ccc;
  display: block;
  width: 50rem;
  box-sizing: border-box;
}

.add-reply button {
  margin-top: 10px;
}
.opt {
  color: #adadad;
  font-size: 12px;
}
</style>
