<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="header-logo">
        <h4>
          <a @click="home">LightBBS</a>
        </h4>
      </div>
      <div class="row collapse navbar-collapse">
        <div class="col">
          <input class="form-control mr-sm-2" type="text" placeholder="Search">
        </div>
        <div class="col header-btn" v-if="$store.getters.login_name != '' && $store.getters.login_name != null">
          <a href="#" @click="personal">{{ $store.getters.login_name }}</a>
          <span></span>
          <a href="#" @click="logoutShow = true">退出</a>
        </div>
        <div v-else class="col header-btn">
          <a @click="login" style="color:#777; cursor:pointer;"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;登录</a>
          <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <a @click="register" style="color:#777; cursor:pointer;"><i class="fa fa-user-plus"></i>&nbsp;注册</a>
        </div>
      </div>
      <!--折叠下拉列表-->
      <div class="dropdown">
        <button class="navbar-toggler" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="navbar-toggler-icon small"></span>
        </button>
        <div class="dropdown-menu header-collapse-menu dropdown-menu-right text-center" aria-labelledby="dropdownMenuButton" v-if="$store.getters.login_name != '' && $store.getters.login_name != null">
          <a class="dropdown-item" style="cursor:pointer;" @click="personal">{{ $store.getters.login_name }}</a>
          <a class="dropdown-item" style="cursor:pointer;" @click="gotoAddTopic">发布主题</a>
          <a v-if="$store.getters.login_role == 'admin'" class="dropdown-item" style="cursor:pointer;" @click="gotoCate">分类管理</a>
          <a class="dropdown-item" style="cursor:pointer;"@click="logoutShow = true">退出</a>
        </div>
        <div class="dropdown-menu  header-collapse-menu dropdown-menu-right text-center" aria-labelledby="dropdownMenuButton" v-else>
          <a class="dropdown-item" href="#" >搜索</a>
          <a class="dropdown-item" style="cursor:pointer;" @click="login">登录</a>
          <a class="dropdown-item" style="cursor:pointer;" @click="register">注册</a>
        </div>
      </div>
    </nav>
    <!-- 退出用户登录确认框 -->
    <el-dialog
        class="w-30"
        title="提示"
        :visible.sync="logoutShow"
       >
      <span>确认退出当前账户？</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="logoutShow = false">取 消</el-button>
        <el-button type="primary" @click="handleLogout">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { logout } from "@/api/user";

  export default {
    name: "TheHeader",
    data () {
      return {
        logoutShow: false
      }
    },
    methods: {
      home() {
        this.$router.push({ name: "Home" });
      },
      login() {
        this.$router.push({ name: "Login" });
      },
      register() {
        this.$router.push({ name: "Register" });
      },
      personal() {
        this.$router.push({ name: "User" });
      },
      handleLogout () {
        logout().then(res => {
          if (res.code == 200) {
            this.$store.dispatch('setLogout');
            this.logoutShow = false;
            this.$router.push({name: 'Home'})
          }
        });
      },
      gotoAddTopic() {
        this.$router.push({ name: "AddTopic" });
      },
      gotoCate() {
        this.$router.push({ name: "Cate" });
      }
    }
  }
</script>

<style scoped>
  .header-logo {
    text-align: left;
    color: #007BFF;
    margin-right: 20px;
    margin-top: 10px;
    cursor: pointer;
  }
  .header-btn {
    text-align: right;
  }
  .header-collapse-menu {
     font-size: 12px;
  }
  .header-collapse-menu a:active{
    color:#ffffff;
  }
  .dropdown-menu{
    min-width: 0;
  }
  @media (max-width: 767px) {
  }
</style>