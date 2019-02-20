<template>
  <div class="col-lg-4 offset-lg-4">
    <div class="alert alert-success alert-dismissable d-none">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      您已成功注册，请到邮箱进行激活!
    </div>
    <div class="register">
      <div class="row">
        <div class="col register-logo">
          <h2>LightBBS</h2>
        </div>
        <div class="col register-title">用户注册</div>
      </div>
      <div class="row register-tip">{{msg}}</div>
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
              required
          ></div>
            <!--<div class="help-block with-errors register-validate-data"></div>-->
        </div>
        <div class="form-group">
          <div><input
                type="email"
                class="form-control"
                placeholder="邮箱"
                v-model="form.email"
                data-error="你输入的不是一个有效的邮件地址！"
                required
            ></div>
            <!--<div class="help-block with-errors register-validate-data"></div>-->
        </div>
        <div class="form-group">
          <div>
            <input
                type="password"
                class="form-control"
                id="inputPassword"
                placeholder="密码"
                v-model="form.password"
                pattern="^[a-zA-Z0-9]\w+$"
                minlength="6"
                maxlength="18"
                data-error="密码长度6~18，以英文字母和数字开头，由字母、数字和_组成！"
                required
            >
          </div>
          <!--<div class="help-block with-errors register-validate-data register-format"></div>-->
        </div>
        <div class="form-group">
          <div>
            <input
                type="password"
                class="form-control"
                placeholder="确认密码"
                v-model="form.repassword"
                data-match="#inputPassword"
                data-error="两次输入的密码不匹配！"
                required
            >
          </div>
          <!--<div class="help-block with-errors register-validate-data register-format"></div>-->
        </div>
        <button class="btn btn-success register-sub" type="submit" @click="onSubmit">注册</button>
      </form>
    </div>
  </div>
</template>

<script>
  import { register } from "@/api/user";

  export default {
    name: "Register",
    data() {
      return {
        form: {
          name: "",
          password: "",
          repassword: '',
          email: ""
        },
        msg: ""
      };
    },
    methods: {
      onSubmit(evt) {
        evt.preventDefault();
        if (this.form.repassword != this.form.password) {
          this.msg = '两次密码不正确，请重新输入密码';
        } else {
          register(this.form).then(res => {
            if (res.code == 200) {
              var reg = this;
              $('.alert').removeClass('d-none');
              window.setTimeout(function(){
                $('[data-dismiss="alert"]').alert('close');
                reg.$router.push({ name: "Login" });
              },3000);
            } else {
              this.msg = res.msg;
            }
          });
        }
      }
    }
  }
</script>

<style scoped>
  .register {
    margin-top: 100px;
    padding-top: 30px;
    height: 405px;
    line-height: 50px;
    min-height: 100px;
    padding-left: 15px;
    padding-right: 15px;
    border-radius: 0px;
    border: 1px solid #dde2e8;
    background: #ffffff;
  }
  .register-logo {
    text-align: left;
    color: #fd7e14;
  }
  .register-title {
    text-align: right;
    font-size: 16px;
  }
  .register-tip {
    height: 28px;
    line-height: 28px;
    color: #dc3545;
    font-size: 12px;
    margin-left: 4px;
  }
  .register-sub {
    width: 100%;
  }
  .register-validate-data{
    font-size: 12px;
    color: red;
    height: 0px;
  }
  .register-format{
    margin-left: 140px;
  }
</style>