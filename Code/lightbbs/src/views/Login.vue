<template>
  <div class="login">
    <div class="row">
      <div class="col login-logo">
        <h2>LightBBS</h2>
      </div>
      <div class="col login-title">用户名登录</div>
    </div>
    <div class="row login-tip">
        {{ $store.state.login_error_msg }}
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
          data-error="名称长度3~25，以英文字母开头，由字母、数字和_组成！"
        ></div>
        <div class="help-block with-errors login-validate-data"></div>
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
          data-error="密码长度6~18，以英文字母和数字开头，由字母、数字和_组成！"
        ></div>
        <div class="help-block with-errors login-validate-data"></div>
      </div>
      <input class="btn btn-success login-sub" type="buton" @click="onSubmit" value="登录">
    </form>
    <div class="row">
      <div class="col login-forget-pwd" @click="forgetPwd">忘记密码？</div>
    </div>
  </div>
</template>
<script>
import { login } from "@/api/user";
import { findPwd } from "@/api/user";

export default {
  data() {
    return {
      form: {
        name: "",
        password: ""
      }
    };
  },
  methods: {
    onSubmit(evt) {
      evt.preventDefault();
      login(this.form).then(res => {
        if (res.code == 200) {
          this.$store.dispatch('setLoginUser', res.data)
          this.$router.push({ name: "Index" });
        } else {
            this.msg = res.msg;
        }
      });
    },
    forgetPwd(){
        this.$router.push({ name: "findPwd" });
    }
  }
};
</script>
<style scoped>
.login {
  margin-top: 100px;
  margin-bottom: 80px;
  margin-left: 33%;
  padding-top: 30px;
  width: 400px;
  height: 330px;
  line-height: 50px;
  position: relative;
  min-height: 1px;
  padding-left: 15px;
  padding-right: 15px;
  border-radius: 0px;
  border: 1px solid #dde2e8;
  background: #ffffff;
}

.login-logo {
  text-align: left;
  color: #fd7e14;
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
}
.login-validate-data{
    font-size: 12px;
    color: red;
    height: 0px;
}
</style>
