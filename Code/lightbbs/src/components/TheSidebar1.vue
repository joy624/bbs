<template>
  <div>
    <div class="col">
      <div class="model bg-light">
        <div class="modal-header">
          <h5 class="modal-title small">LightBBS是一个关于分享和探索的地方</h5>
        </div>
        <div class="modal-body row" v-if="user">
          <div class="col-3">
            <img
                    class="rounded"
                    style="width:65px"
                    :src="'http://my.test.tp/'+user.img_url"
                    alt
                    @click="gotoUserInfo"
            >
          </div>
          <div class="col-9">
            <div class="row">
              <div class="col-3 bbs-link" @click="gotoUserInfo">
                <strong>{{ $store.getters.login_name }}</strong>
              </div>
              <div class="col bbs-link" @click="gotoUserInfo">
                <strong>个人信息</strong>
              </div>
            </div>
            <div class="row" style="margin-top:10px;">
              <div class="col">
                <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        @click="gotoAddTopic"
                >发布主题</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-body text-center" v-else>
          <div>
            <button type="button" class="btn btn-outline-secondary" @click="gotoRegister">现在注册</button>
          </div>
          <div class="small">
            已注册用户请
            <a href="#" @click="gotoLogin">登录</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col">
      <button
              v-if="user.role == 'admin'"
              type="button"
              class="btn btn-outline-dark btn-lg btn-sm btn-block bbs-cate"
              @click="gotoCate"
      >分类管理</button>
    </div>
    <div class="col">
      <div class="model bg-light">
        <ul class="list-group list-group-flush">
          <!--<li class="list-group-item bg-light" v-for="topic in topics">{{ topic.title }}</li>-->
          <li class="list-group-item bg-light">来自单身狗的...祝福——记一次走心的 2.14 福利</li>
          <li class="list-group-item bg-light">来自单身狗的...祝福——记一次走心的 2.14 福利</li>
          <li class="list-group-item bg-light">来自单身狗的...祝福——记一次走心的 2.14 福利</li>
          <li class="list-group-item bg-light">来自单身狗的...祝福——记一次走心的 2.14 福利</li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import { loginUser } from "@/api/user";
import { mapActions } from "vuex";

export default {
  name: "TheSidebar",
  data() {
    return {
      user: ""
    };
  },
  mounted() {
    this.$store.dispatch("loadCates");
    this.$store.dispatch("loadTopics", this.$store.state.cate_active);
    loginUser().then(res => {
      if (res.code == 200) {
        this.user = res.data;
      }
    });
  },
  methods: {
    gotoRegister() {
      this.$router.push({ name: "Register" });
    },
    gotoLogin() {
      this.$router.push({ name: "Login" });
    },
    gotoAddTopic() {
      this.$router.push({ name: "AddTopic" });
    },
    gotoUserInfo() {
      this.$router.push({ name: "UserInfo" });
    },
    gotoCate() {
      this.$router.push({ name: "Cate" });
    }
  }
};
</script>
<style scoped>
.bbs-link {
  font-size: 14px;
  color: #949292;
  cursor: pointer;
}
.bbs-link:hover {
  color: #000000;
}
.bbs-cate {
  margin-bottom: 8px;
  margin-top: 8px;
}
.model {
  height: 145px;
  margin-bottom: 8px;
}
</style>
