<template>
  <div class="col-lg-4 offset-lg-4">
    <div class="login">
      <div class="row">
        <div class="col login-logo">
          <h2>LightBBS</h2>
        </div>
        <div class="col login-title">用户名登录</div>
      </div>
      <div class="row login-tip">
        {{ msg }}
      </div>
      <form :v-model="form" data-toggle="validator">
        <div class="form-group">
          <div><input
              type="text"
              class="form-control"
              placeholder="用户名"
              v-model="form.name"
              pattern="^[a-zA-Z]\w+$"
              minlength="3"
              maxlength="25"
          ></div>
          <!--<div class="help-block with-errors login-validate-data"></div>-->
        </div>
        <div class="form-group">
          <div><input
              type="password"
              class="form-control"
              placeholder="密码"
              v-model="form.password"
              data-toggle="validator"
              pattern="^[a-zA-Z0-9]\w+$"
              minlength="6"
              maxlength="18"
          ></div>
          <!--<div class="help-block with-errors login-validate-data"></div>-->
        </div>
        <button class="btn btn-primary login-sub" @click="onSubmit" >登录</button>
      </form>
      <div class="row">
        <div class="col login-forget-pwd" @click="forgetPwd">忘记密码？</div>
      </div>
    </div>
  </div>

</template>

<script>
  import { login } from "@/api/user";
  import { findPwd } from "@/api/user";

  export default {
    name: "Login",
    data() {
      return {
        form: {
          name: "",
          password: ""
        },
        msg: ''
      };
    },
    methods: {
      onSubmit(evt) {
        evt.preventDefault();
        login(this.form).then(res => {
          if (res.code == 200) {
            this.$store.dispatch('setLoginUser', res.data)
            this.$router.push({ name: "Home" });
          } else {
            this.msg = res.msg;
          }
        });
      },
      forgetPwd(){
        this.$router.push({ name: "FindPwd" });
      }
    }
  }
</script>

<style scoped>
  .login {
    margin-top: 100px;
    padding-top: 30px;
    height: 330px;
    line-height: 50px;
    min-height: 100px;
    padding-left: 15px;
    padding-right: 15px;
    border-radius: 0px;
    border: 1px solid #dde2e8;
    background: #ffffff;
  }

  .login-logo {
    text-align: left;
    color: #007BFF;
  }
  .login-title {
    text-align: right;
    font-size: 16px;
  }
  .login-tip{
    height:28px;
    line-height:28px;
    color:#DC3545;
    font-size: 12px;
    margin-left:4px;
  }
  .login-sub {
    width: 100%;
  }
  .login-forget-pwd {
    text-align: left;
    font-size: 12px;
    cursor: pointer;
  }
  .login-validate-data{
    font-size: 12px;
    color: red;
    /*margin-top: -10px;*/
    /*margin-bottom: -26px;*/
  }
</style>