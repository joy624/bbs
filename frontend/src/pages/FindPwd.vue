<template>
  <div class="container border">
    <div v-if="success_msg">
      <div class="col-12 alert alert-success" role="alert">
        <i class="fa fa-check-circle"></i> {{ success_msg }}
      </div>
    </div>
    <div v-else>
      <!--提示信息-->
      <div class="col-12 alert alert-danger d-none" role="alert">{{ error_msg }}</div>
      <form>
        <div class="form-group">
          <label for="FormControlEmail">请输入邮箱：</label>
          <input type="text" v-model="email" class="form-control-file" id="FormControlEmail">
        </div>
        <input type="button" class="btn btn-primary" value="发送" @click="findPassword">
      </form>
    </div>
  </div>
</template>

<script>
  import { findPwd } from "@/api/user";
  export default {
    name: "FindPwd",
    data() {
      return {
        email:'',
        error_msg:'',
        success_msg:''
      };
    },
    methods: {
      findPassword(){
        console.log(this.email);
        findPwd({email:this.email}).then(res=>{
          if (res.code == 200) {
            this.success_msg = '已向新邮箱发送验证信息，请到邮箱进行验证。';
          }else{
            this.error_msg = res.msg;
          }
          $(".alert-danger")
              .removeClass("d-none")
              .addClass("d-show");
        })
      }
    }
  }
</script>

<style scoped>

</style>