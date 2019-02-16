<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="header-logo">
        <h4>
          <a @click="home">LightBBS</a>
        </h4>
      </div>
      <div class="row collapse navbar-collapse dropdown">
        <div class="col">
          <input class="form-control mr-sm-2" type="text" placeholder="Search">
        </div>
        <div class="col header-btn" v-if="$store.getters.login_name != '' && $store.getters.login_name != null">
          <a href="#" @click="personal">{{ $store.getters.login_name }}</a>
          <span></span>
          <a href="#" data-toggle="modal" data-target="#logoutModal">退出</a>
        </div>
        <div v-else class="col header-btn">
          <a @click="login" style="color:#777; cursor:pointer;"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;登录</a>
          <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <a @click="register" style="color:#777; cursor:pointer;"><i class="fa fa-user-plus"></i>&nbsp;注册</a>
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
          <a class="dropdown-item" href="#" >搜索</a>
          <a class="dropdown-item" style="cursor:pointer;" @click="login">登录</a>
          <a class="dropdown-item" style="cursor:pointer;" @click="register">注册</a>
        </div>
      </div>
    </nav>
    <!-- 退出用户登录确认框 -->
    <div
        class="modal fade"
        id="logoutModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="logoutModal"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">退出登录</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">确定要从 lightBBS 登出？</div>
          <div class="modal-footer">
            <button type="button" id="logoutbtn" class="btn btn-primary" @click="logout" >确定</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { logout } from "@/api/user";

  export default {
    name: "TheHeader",
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
      logout () {
        logout().then(res => {
          if (res.code == 200) {
            this.$store.dispatch('setLogout');
          } else {
            this.msg = res.msg;
          }
        });
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

  @media (max-width: 767px) {
  }
</style>