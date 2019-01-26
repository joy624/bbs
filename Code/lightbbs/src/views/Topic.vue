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
        <button type="button" class="btn btn-link opt" @click="gotoEditAddTopic(data.id)">编辑</button>
        <button type="button" class="btn btn-link opt" @click="delTopic(data.id)">删除</button>
      </span>
      <hr class="simple" color="#D9DADB">
      <!-- 内容 -->
      <div class="panel">{{data.content}}</div>
    </div>
    <!--提示信息-->
    <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
    <!-- 回复内容列表 -->
    <div class="reply" style="width:50rem;">
      <ul class="list-group list-group-flush">
        <li class="list-group-item bg-light" v-for="reply in replies">
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
                    <button type="button" class="btn btn-link opt" @click="editReply(reply.id)">编辑</button>
                    <button type="button" class="btn btn-link opt" @click="delReply(reply.id)">删除</button>
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
        <button class="btn btn-primary" @click="addReply(data.id)">发表评论</button>
      </form>
    </div>
  </div>
</template>
<script>
import { viewTopic } from "@/api/topic";
import { replyIndex } from "@/api/topic";
import { loginUser } from "@/api/user";
import { addTopicReply } from "@/api/topic";
import { delTopicReply } from "@/api/topic";

export default {
  data() {
    return {
      data: "",
      replies: "",
      user: "",
      reply_content: "",
      msg: ""
    };
  },
  mounted() {
    viewTopic(this.$route.query.id).then(res => {
      if (res.code == 200) {
        //console.log(res.data);
        this.data = res.data;
      }
    });
    replyIndex(this.$route.query.id).then(res => {
      if (res.code == 200) {
        //console.log(res);
        this.replies = res.data;
      }
    });
    loginUser().then(res => {
      if (res.code == 200) {
        //console.log(res);
        this.user = res.data;
      }
    });
  },
  methods: {
    gotoEditAddTopic(id) {
      this.$router.push({ name: "editAddTopic", query: { id: id } });
    },
    delTopic(id) {},
    editReply(id) {},
    delReply(id) {
      delTopicReply(id).then(res => {
        if (res.code == 200) {
          //this.$router.push({ name: "Topic" });
          //this.$router.push({name:"Topic",query:{id:this.data.id}});
          //this.$router.go(0)
          // todo 页面刷新跳转
        } else {
          this.msg = res.msg;
          $(".alert-danger")
            .removeClass("d-none")
            .addClass("d-show");
        }
      });
    },
    addReply(id) {
      addTopicReply({id:id,content:this.reply_content}).then(res => {
          if(res.code == 200){
           console.log(res);
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
