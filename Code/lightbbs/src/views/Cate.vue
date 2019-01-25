<template>
  <div class="cate">
    <!--提示信息-->
    <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
    <!--添加分类-->
    <div class="form-row cate-add">
      <input type="text" class="col-md-5" placeholder="分类名称" ref="cname">
      <button type="submit" class="btn btn-primary" @click="add">添加</button>
    </div>
    <!--分类列表-->
    <table class="table">
      <thead>
        <tr>
          <th scope="col">排序</th>
          <th scope="col">分类名</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cate in cates">
           <input type="hidden" :value="cate.id">
          <th scope="row">
            <input type="text" :value="cate.sort" class="text-center"  @blur.prevent="edit">
          </th>
          <td>
            <input type="text" :value="cate.name" class="text-center"  @blur.prevent="edit">
          </td>
          <td>
            <button type="submit" class="btn btn-primary" @click="del(cate.id)">删除</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import { list } from "@/api/topic";
import { delCate } from "@/api/topic";
import { addCate } from "@/api/topic";
import { editCate } from "@/api/topic";
export default {
  name: "Cate",
  data() {
    return {
      cates: "",
      msg: ""
    };
  },
  mounted() {
    list().then(res => {
      this.cates = res.data;
    });
  },
  methods: {
    add() {
      addCate(this.$refs.cname.value).then(res => {
        if (res.code == 200) {
          this.$router.push({ name: "Cate" });
        } else {
          this.msg = res.msg;
          $(".alert-danger")
            .removeClass("d-none")
            .addClass("d-show");
        }
      });
    },
    del(evt) {
      delCate(evt).then(res => {
        if (res.code == 200) {
          this.$router.push({ name: "Cate" });
        } else {
          this.msg = res.msg;
          $(".alert-danger")
            .removeClass("d-none")
            .addClass("d-show");
        }
      });
    },
    edit(){
        //editCate(this.form).then(res => {
          //console.log(res);
        /*if (res.code == 200) {
          this.$router.push({ name: "Cate" });
        } else {
          this.msg = res.msg;
          $(".alert-danger")
            .removeClass("d-none")
            .addClass("d-show");
        }*/
      //});
    }
  }
};
</script>
<style scoped>
.cate-add {
  margin-bottom: 10px;
}
.cate-add input {
  margin-right: 10px;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}
</style>
