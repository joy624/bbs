<template>
  <div class="register">
    <div class="row">
      <div class="col register-logo">
        <h2>LightBBS</h2>
      </div>
      <div class="col register-title">用户注册</div>
    </div>
    <div class="row register-tip">{{msg}}</div>
    <form :v-model="form" data-toggle="validator">
      <div class="form-group row">
        <label for="inputUsername" class="col-sm-3 col-form-label text-right">用户名：</label>
        <div class="col-sm-9">
          <input
            type="text"
            class="form-control"
            id="inputUsername"
            placeholder="用户名"
            v-model="form.name"
            pattern="^[a-zA-Z]\w+$"
            minlength="3"
            maxlength="25"
            data-error="名称长度3~25，以英文字母开头，由字母、数字和_组成！"
            required
          >
          <div class="help-block with-errors register-validate-data"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-3 col-form-label text-right">邮箱：</label>
        <div class="col-sm-9">
          <input
            type="email"
            class="form-control"
            id="inputEmail"
            placeholder="邮箱"
            v-model="form.email"
            data-error="你输入的不是一个有效的邮件地址！"
            required
          >
          <div class="help-block with-errors register-validate-data"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-3 col-form-label text-right">密码：</label>
        <div class="col-sm-9">
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
        <div class="help-block with-errors register-validate-data register-format"></div>
      </div>
      <div class="form-group row">
        <label for="inputRePassword" class="col-sm-3 col-form-label text-right">确认密码：</label>
        <div class="col-sm-9">
          <input
            type="password"
            class="form-control"
            id="inputRePassword"
            placeholder="确认密码"
            data-match="#inputPassword"
            data-error="两次输入的密码不匹配！"
            required
          >
        </div>
        <div class="help-block with-errors register-validate-data register-format"></div>
      </div>
      <button class="btn btn-success register-sub" type="submit" @click="onSubmit">注册</button>
    </form>
  </div>
</template>
<script>
import { register } from "@/api/user";
export default {
  data() {
    return {
      form: {
        name: "",
        password: "",
        email: ""
      },
      msg: ""
    };
  },
  methods: {
    onSubmit(evt) {
      //evt.preventDefault();
      //console.info(JSON.stringify(this.form));
      register(this.form).then(res => {
        // console.info(JSON.stringify(this.form));
        if (res.code == 200) {
          this.$router.push({ name: "Index" });
        } else {
            this.msg = res.msg;
        }
      });
    }
  }
};
</script>
<style scoped>
.register {
  margin-top: 100px;
  margin-bottom: 80px;
  margin-left: 21%;
  padding-top: 30px;
  width: 500px;
  height: 405px;
  line-height: 50px;
  position: relative;
  min-height: 1px;
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
    margin-top: -17px;
}
.register-format{
    margin-left: 140px;
}
</style>
