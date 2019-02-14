<template>
  <div>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
      <div class="header-logo">
        <h4>
          <a href="#" @click="gotoIndex">LightBBS</a>
        </h4>
      </div>
      <div class="row collapse navbar-collapse dropdown">
        <div class="col">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
          >
        </div>
        <div v-if="$store.getters.login_name != '' && $store.getters.login_name != null">
          <a href="#">{{ $store.getters.login_name }}</a>
          <a href="#" data-toggle="modal" data-target="#logoutModal">退出</a>
        </div>
        <div v-else class="col header-btn">
          <button class="btn btn-outline-success my-2 my-sm-0" @click="gotoLogin">登录</button>
          <button class="btn btn-outline-success my-2 my-sm-0" @click="gotoRegister">注册</button>
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
            <button type="button" class="btn btn-primary" @click="gotoLogout" >确定</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { loginUser } from "@/api/user";
import { logout } from "@/api/user";
export default {
  name: "TheHeader",
  data() {
    return {
      user_name: "",
      user_id: ""
    };
  },
  methods: {
    gotoLogin() {
      this.$router.push({ name: "Login" });
    },
    gotoRegister() {
      this.$router.push({ name: "Register" });
    },
    gotoIndex() {
      this.$router.push({ name: "Index" });
    },
    gotoLogout() {
      logout().then(res => {
        if (res.code == 200) {
          this.$store.dispatch('setLogout')
          // this.$router.push({ name: "Index" });
        } else {
            this.msg = res.msg;
        }
      });
    },
  },
  mounted() {
    loginUser().then(res => {
      if (res.code == 200) {
        this.user_name = res.data.name;
        this.user_id = res.data.id;
      }
    });
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

