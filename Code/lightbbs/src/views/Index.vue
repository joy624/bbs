<template>
  <div class>
    <div class="sidebar">
      <div class="model bg-light" v-if="user">
        <div class="text-center">
          <img class="user-info" :src="'http://my.test.tp/'+user.img_url" alt>
        </div>
        <div class="row user-opt">
          <div class="col-6">
            <a class="btn btn-primary" href="#" role="button" @click="gotoTopic">发布主题</a>
          </div>
          <div class="col-6">
            <a class="btn btn-primary" href="#" role="button" @click="gotoUserInfo">个人中心</a>
          </div>
        </div>
      </div>

      <div class="model bg-light" v-if="user.role == 'admin'">
        <button type="button" class="btn btn-info" @click="gotoCate">分类管理</button>
      </div>
      <div class="model bg-light topic-model">
        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-light" v-for="topic in topics">{{ topic.title }}</li>
        </ul>
      </div>
    </div>
    <div class="main bg-light">
      <ul class="nav">
        <li class="nav-item" v-for="cate in cates">
          <a class="nav-link" @click="cateTopic(cate.id)">{{ cate.name }}</a>
        </li>
      </ul>
      <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="topic in topics">
          <div class="row">
            <div clas="col">
              <img
                :src="'http://my.test.tp/'+topic.user.img_url"
                alt
                class="rounded-circle topic-img"
              >
            </div>
            <div clas="col">
              <div class="row">
                <h5 class="card-title">
                  <a href>{{ topic.title }}</a>
                </h5>
              </div>
              <div class="row">
                <span class="small">
                  <strong>{{ topic.user.name}}</strong>
                  &nbsp;•&nbsp; {{ topic.update_time}} &nbsp;•&nbsp; 点赞数:
                  <strong>{{ topic.likenum}}</strong> &nbsp;•&nbsp; 点击数：
                  <strong>{{ topic.hits }}</strong>
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import { list } from "@/api/topic";
import { index } from "@/api/topic";
import { loginUser } from "@/api/user";

export default {
  name: "Index",
  data() {
    return {
      cates: "",
      topics: "",
      user: ""
    };
  },
  mounted() {
    list().then(res => {
      this.cates = res.data;
    });
    this.cateTopic();
    loginUser().then(res => {
      if(res.code == 200){
      this.user = res.data;
      }
    });
  },
  methods: {
    cateTopic(evt) {
      index(evt).then(res => {
        this.topics = res.data;
      });
    },
    gotoCate() {
      this.$router.push({ name: "Cate" });
    },
    gotoTopic() {
      this.$router.push({ name: "addTopic" });
    },
    gotoUserInfo(){
      this.$router.push({ name:"userInfo"});
    }
  }
};
</script>
<style scoped>
.main {
  width: 70%;
  margin-right: 4.5rem;
  border-radius: 0.25rem;
}
.sidebar {
  float: right;
  width: 29%;
  border-radius: 0.25rem;
}
.topic-img {
  width: 44px;
  height: 44px;
  margin-right: 25px;
}
.model {
  height: 145px;
  margin-bottom: 8px;
}
.topic-model .list-group {
  list-style: decimal inside;
}
.topic-model .list-group-item {
  display: list-item;
}
.user-info {
  width: 80px;
  text-align: center;
  margin-top: 10px;
}
.user-opt {
  margin-left: 20px;
  margin-top: 10px;
}
</style>

