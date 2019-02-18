<template>
  <div class="row">
    <div class="col-md-4">
      <div class="list-group text-center" id="list-tab" role="tablist">
        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home"><i class="fa fa-list"></i>个人信息</a>
        <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile"><i class="fa fa-picture-o"></i> 修改头像</a>
        <a class="list-group-item list-group-item-action" id="list-email-list" data-toggle="list" href="#list-email" role="tab" aria-controls="messages"><i class="fa fa-envelope"></i> 修改邮箱</a>
        <a class="list-group-item list-group-item-action" id="list-username-list" data-toggle="list" href="#list-username" role="tab" aria-controls="messages"><i class="fa fa-user"></i> 修改用户名</a>
        <a class="list-group-item list-group-item-action" id="list-password-list" data-toggle="list" href="#list-password" role="tab" aria-controls="settings"><i class="fa fa-lock"></i> 修改密码</a>
      </div>
    </div>
    <div class="col-md-8">
      <div class="row">
        <!--提示信息-->
        <div class="col alert alert-danger d-none" role="alert">{{ msg }}</div>
      </div>
      <div class="row">
        <div class="tab-content col" id="nav-tabContent">
          <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
            <div class="card border-secondary">
              <h5 class="card-header">个人信息</h5>
              <div class="card-body text-secondary">
                <div class="row">
                  <div class="col-7 text-center">
                    <img :src="'http://my.test.tp/'+info.img_url" alt="" class="img-thumbnail">
                  </div>
                </div>
                <div class="card-text row">
                  <div class="col-3 text-right">用户名：</div>
                  <div class="col-4">{{ info.name }}</div>
                </div>
                <div class="card-text row">
                  <div  class="col-3 text-right">邮  箱：</div>
                  <div class="col-4">{{ info.email }}</div>
                </div>
                <div class="card-text row">
                  <div  class="col-3 text-right">注册时间：</div>
                  <div class="col-4">{{ info.reg_time }}</div>
                </div>
                <div class="card-text row">
                  <div  class="col-3 text-right">最后修改时间：</div>
                  <div class="col-4">{{ info.update_time }}</div>
                </div>
                <div class="card-text row">
                  <div  class="col-3 text-right">是否激活：</div>
                  <div class="col-4" v-if="info.is_active">已激活</div>
                  <div class="col-4" v-else>未激活</div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
            <div class="card border-secondary">
              <div class="card-header"><i class="fa fa-picture-o"></i> 修改头像</div>
              <div class="card-body text-secondary">
                <div class="row">
                  <div class="col-1"></div>
                  <img :src="'http://my.test.tp/'+info.img_url" alt="" class="img-thumbnail">
                </div>
                <div class="row">
                  <div class="col-1"></div>
                  <form>
                    <div class="form-group">
                      <label class="small" for="FormControlFile">请选择图片:</label>
                      <div class="border choose-file">
                        <input type="file" ref="file" class="form-control-file" id="FormControlFile">
                      </div>
                    </div>
                    <input type="button" class="btn btn-primary" value="上传头像" @click="editPortrait">
                  </form>
                </div>

              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="list-email" role="tabpanel" aria-labelledby="list-email-list">
            <div class="card border-secondary">
              <div class="card-header"><i class="fa fa-user"></i> 修改邮箱</div>
              <div class="card-body text-secondary">
                <form>
                  <div class="form-group">
                    <label for="FormControlEmail">请输入新邮箱：</label>
                    <input type="text" v-model="email" class="form-control-file" id="FormControlEmail">
                  </div>
                  <input type="button" class="btn btn-primary" value="修改邮箱" @click="updateEmail">
                </form>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="list-username" role="tabpanel" aria-labelledby="list-username-list">
            <div class="card border-secondary">
              <div class="card-header"><i class="fa fa-user"></i> 修改用户名</div>
              <div class="card-body text-secondary">
                <form>
                  <div class="form-group">
                    <label for="FormControlUser">用户名：</label>
                    <input type="text" v-model="name" class="form-control-file" id="FormControlUser">
                  </div>
                  <input type="button" class="btn btn-primary" value="修改用户名" @click="updateName">
                </form>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="list-password" role="tabpanel" aria-labelledby="list-password-list">
            <div class="card border-secondary">
              <div class="card-header"><i class="fa fa-lock"></i> 修改密码</div>
              <div class="card-body text-secondary">
                <form>
                  <div class="form-group">
                    <label for="FormControlOldPwd">原密码：</label>
                    <input type="password" v-model="old_password" class="form-control-file" id="FormControlOldPwd">
                  </div>
                  <div class="form-group">
                    <label for="FormControlNewPwd">新密码：</label>
                    <input type="password" v-model="new_password" class="form-control-file" id="FormControlNewPwd">
                  </div>
                  <input type="button" class="btn btn-primary" value="修改密码" @click="updatePwd">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
  import { loginUser } from "@/api/user";
  import { headPortrait } from "@/api/user";
  import { editName } from "@/api/user";
  import { editPwd } from "@/api/user";
  import { editEmail } from "@/api/user";
  import { logout } from "@/api/user";

  export default {
    name: "UserPage",
    data() {
      return {
        info: [],
        img_url:'',
        name:'',
        old_password:'',
        new_password:'',
        email:'',
        msg:''
      };
    },
    beforeMount() {
      loginUser({id:this.$store.getters.login_id}).then(res => {
        if(res.code == 200){
          this.info = res.data;
        }
      });
    },
    methods:{
      editPortrait(){
        let formData = new FormData()
        let file = this.$refs.file.files[0];
        formData.append('portrait', file);
        headPortrait(formData).then(res => {
          if (res.code == 200) {
            this.info.img_url = res.data;
            $(".alert-danger").addClass("d-none");
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      },
      updateName(){
        editName({name:this.name}).then(res => {
          if (res.code == 200) {
            this.info.name = this.name;
            this.$store.dispatch('setLoginUser', {id:this.$store.getters.login_id,name:this.name,role:this.$store.getters.login_role})
            $(".alert-danger").addClass("d-none");
            this.name = '';
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        })
      },
      updatePwd(){
        var params = {old_password: this.old_password,new_password: this.new_password};
        editPwd(params).then(res => {
          if (res.code == 200) {
            console.log(res);
            // 退出登录
            logout().then(res => {
              if (res.code == 200) {
                this.$store.dispatch('setLogout')
                this.$router.push({ name: "Login" });
              } else {
                this.msg = res.msg;
              }
            });
            this.$router.push({ name: "User" });
            $(".alert-danger").addClass("d-none");
            this.name = '';
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        })
      },
      updateEmail(){
        console.log(this.email);
        editEmail({email:this.email}).then(res=>{
          if (res.code == 200) {
            this.msg = '已向新邮箱发送验证信息，请到邮箱进行验证。';
          }else{
            this.msg = res.msg;
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
  .choose-file{
    padding: 10px;
    width:600px;
  }
</style>