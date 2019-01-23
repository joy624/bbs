<template>
  <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
    <div class="header-logo">
      <h4><a href="#" @click="gotoIndex">LightBBS</a></h4>
    </div>
    <div class="row collapse navbar-collapse dropdown">
      <div class="col">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      </div>
      <div v-if="user_name != ''">
        {{ user_name }}
      </div>
      <div v-else class="col header-btn">
        <button class="btn btn-outline-success my-2 my-sm-0" @click="gotoLogin">登录</button>
        <button class="btn btn-outline-success my-2 my-sm-0" >注册</button>
      </div>
    </div>
    <!--折叠按钮-->
    <div class="nav-item dropdown">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon small"></span>
      </button>
      <!--折叠按钮的下拉菜单-->
      <div
        class="dropdown-menu header-collapse-menu"
        aria-labelledby="navbarDropdown"
        id="navbarSupportedContent"
      >
        <a class="dropdown-item" href="#">搜索</a>
        <a class="dropdown-item" href="#" @click="gotoLogin">登录</a>
        <a class="dropdown-item" href="#">注册</a>
      </div>
    </div>
  </nav>
</template>
<script>
import { loginUser } from "@/api/user";
export default {
  name: "TheHeader",
  data() {
    return {
      user_name: "",
      user_id: ""
    };
  },
  methods: {
    gotoLogin () {
      this.$router.push({ name: 'Login'})
    },
    gotoIndex () {
      this.$router.push({ name: 'Index'})
    }
  },
  mounted () {
    loginUser().then(res => {
      if (res.code == 200) {
        this.user_name = res.data.name
        this.user_id = res.data.id
      }
    })
  }
};
</script>
<style scoped>
.header-logo {
  text-align: left;
  color: #fd7e14;
  margin-right: 20px;
  margin-top: 10px;
}
.header-btn {
  text-align: right;
}
.header-collapse-menu {
  font-size: 12px;
}
@media (max-width: 767px) {
}
</style>

