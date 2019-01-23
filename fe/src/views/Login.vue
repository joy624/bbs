<template>
  <div class="login" style="border-radius: 10px;">
    <b-form @submit="onSubmit" class="container" :v-model="form">
      <b-row id="title-row">
        <b-col>
          <h1>LightBBS</h1>
        </b-col>
        <b-col>用户名登录</b-col>
      </b-row>
      <b-form-group>
        <b-form-input type="text" placeholder="用户名" required v-model="form.name"></b-form-input>
      </b-form-group>
      <b-form-group>
        <b-form-input type="password" placeholder="密码" required v-model="form.password"></b-form-input>
      </b-form-group>
      <b-form-group id="login-group">
        <button type="submit" class="btn btn-primary login-btn">登录</button>
      </b-form-group>
      <b-row>
        <b-col id="forget-link">
          <b-link href="#/forget">忘记密码</b-link>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
import {login} from '@/api/user'
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
      //console.info(JSON.stringify(this.form));
      login(this.form).then(res => {
          console.info(res)
          if (res.code == 200) {
              
              this.$router.push({name: 'index'})
          } else {

          }
      }) 
    }
  }
};
</script>

<style>
body {
  background: #cccccc;
}
#title-row {
  margin-bottom: 16px;
}
#login-group {
  margin-bottom: 0px;
}
#forget-link {
  text-align: left;
}
.login {
  margin-top: 120px;
  margin-bottom: 80px;
  margin-left: 33%;
  padding-top: 20px;
  width: 400px;
  height: 320px;
  line-height: 50px;
  position: relative;
  min-height: 1px;
  padding-left: 15px;
  padding-right: 15px;
  border-radius: 0px;
  border: 1px solid #dde2e8;
  background: #ffffff;
}
.login-btn {
  width: 100%;
}
</style>
