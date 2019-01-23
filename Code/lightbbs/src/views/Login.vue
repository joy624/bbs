<template>
  <div class="login">
    <div class="row">
      <div class="col login-logo">
        <h2>LightBBS</h2>
      </div>
      <div class="col login-title">用户名登录</div>
    </div>
    <div class="row login-tip">
        {{msg}}
    </div>
    <form :v-model="form">
      <input
        type="text"
        class="form-control form-group"
        placeholder="用户名"
        v-model="form.name"
        required
      >
      <input
        type="password"
        class="form-control form-group"
        placeholder="密码"
        v-model="form.password"
        required
      >
      <button class="btn btn-success login-sub" type="submit" @click="onSubmit">登录</button>
    </form>
    <div class="row">
      <div class="col login-forget-pwd">忘记密码？</div>
    </div>
  </div>
</template>
<script>
import { login } from "@/api/user";
export default {
  data() {
    return {
      form: {
        name: "",
        password: ""
      },
      msg:""
    };
  },
  methods: {
    onSubmit(evt) {
      evt.preventDefault();
      //console.info(JSON.stringify(this.form));
      login(this.form).then(res => {
        if (res.code == 200) {
          this.$router.push({ name: "index" });
        } else {
            this.msg = res.msg;
        }
      });
    }
  }
};
</script>
<style scoped>
body {
  background: #cccccc;
}
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
</style>
