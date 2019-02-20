<template>
  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12">
          <!--提示信息-->
          <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
          <!-- 回复内容列表 -->
          <div class="reply">
            <ul class="list-group list-group-flush">
              <li class="list-group-item bg-light" v-for="(reply,index) in replies" :key="index">
                <div class="row">
                  <div clas="col">
                    <img :src="'http://my.test.tp/'+reply.user.img_url" alt class="rounded-circle reply-img">
                  </div>
                  <div clas="col">
                    <div class="row">
                      <span>
                        <strong>{{ reply.user.name}}</strong>
                        <span class="d-none d-md-inline"> 创建于 {{ reply.create_time}}</span> 更新于 {{ reply.update_time}}
                        <span v-if="$store.getters.login_id == reply.user_id">
                          <button type="button" class="btn btn-link opt" @click="editReply(reply.id,reply.content,index)">编辑</button>
                          <button type="button" class="btn btn-link opt" @click="del(index,reply.id)">删除</button>
                        </span>
                      </span>
                    </div>
                    <div class="row card-text">{{ reply.content}}</div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <!-- 发表评论 -->
          <div class="add-reply" v-if="$store.getters.login_id != '' && $store.getters.login_id != null">
            <form>
              <textarea
                  name="content"
                  maxlength="10000"
                  class="mll"
                  id="reply_content"
                  style="overflow: hidden; word-wrap: break-word; resize: none; height: 112px; width: 100%;"
                  v-model="reply_content"
              ></textarea>
              <input v-if="reply_id" type="button" style="margin-top: 4px;" class="btn btn-primary" @click="updateReply" value="发布评论"/>
              <input v-else type="button" style="margin-top: 4px;" class="btn btn-primary" @click="addReply" value="发布评论"/>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- 删除回复确认框 -->
    <el-dialog class="w-30" title="提示" :visible.sync="tipShow">
      <span>确认删除当前回复？</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="tipShow = false">取 消</el-button>
        <el-button type="primary" @click="delReply">确定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { replyIndex } from "@/api/reply";
  import { addTopicReply } from "@/api/reply";
  import { editTopicReply } from "@/api/reply";
  import { delTopicReply } from "@/api/reply";

  export default {
    name: "Reply",
    data() {
      return {
        tipShow: false,
        replies: "",
        msg: "",
        editdata: "",
        reply_content: "",
        reply_id: "",
        reply_index: "",
        del_id:'',
        del_index:'',
      };
    },
    mounted () {
      replyIndex(this.$route.params.id).then(res => {
        if (res.code == 200) {
          this.replies = res.data;
        }
      });
    },
    methods: {
      del(index,id){
        this.del_index = index;
        this.del_id = id;
        this.tipShow = true;
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
      delReply() {
        delTopicReply(this.del_id).then(res => {
          if (res.code == 200) {
            this.replies.splice(this.del_index,1);
            this.tipShow = false;
            this.del_id = this.del_index = '';
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      },
      addReply() {
        addTopicReply({topic_id:this.$route.params.id, content:this.reply_content}).then(res => {
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
  }
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