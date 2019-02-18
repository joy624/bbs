<template>
  <div class="row">
    <div class="col-12">
      <div class="cate">
        <!--提示信息-->
        <div class="alert alert-danger d-none" role="alert">{{ msg }}</div>
        <!--添加分类-->
        <div class="form-row cate-add">
          <input type="text" class="col-5" placeholder="分类名称" v-model="cateName">
          <button type="submit" class="btn btn-primary" @click="add">添加</button>
        </div>
        <!--分类列表-->
        <div class="table-responsive-lg">
          <table class="table">
            <thead>
            <tr>
              <th scope="col">排序</th>
              <th scope="col">分类名</th>
              <th scope="col">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(cate,index) in cates" :key="index">
              <th>
                <input type="text" v-model="cate.sort" class="text-center"  @blur="edit(index)">
              </th>
              <td>
                <input type="text" v-model="cate.name" class="text-center"  @blur="edit(index)">
              </td>
              <td>
                <button type="submit" class="btn btn-primary" @click="del(index,cate.id)" style="min-width:60px">删除</button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <!--<table class="table responsive">-->
          <!--<thead>-->
          <!--<tr>-->
            <!--<th scope="col">排序</th>-->
            <!--<th scope="col">分类名</th>-->
            <!--<th scope="col">操作</th>-->
          <!--</tr>-->
          <!--</thead>-->
          <!--<tbody>-->
          <!--<tr v-for="(cate,index) in cates" :key="index">-->
            <!--<th>-->
              <!--<input type="text" v-model="cate.sort" class="text-center"  @blur="edit(index)">-->
            <!--</th>-->
            <!--<td>-->
              <!--<input type="text" v-model="cate.name" class="text-center"  @blur="edit(index)">-->
            <!--</td>-->
            <!--<td>-->
              <!--<button type="submit" class="btn btn-primary" @click="del(index,cate.id)">删除</button>-->
            <!--</td>-->
          <!--</tr>-->
          <!--</tbody>-->
        <!--</table>-->
      </div>
    </div>
  </div>
</template>

<script>
  import { list } from "@/api/cate";
  import { delCate } from "@/api/cate";
  import { addCate } from "@/api/cate";
  import { editCate } from "@/api/cate";

  export default {
    name: "Category",
    data() {
      return {
        cates: "",
        msg: "",
        cateName:"",
        sort:""
      };
    },
    mounted() {
      list().then(res => {
        this.cates = res.data;
      });
    },
    methods: {
      add() {
        addCate(this.cateName).then(res => {
          if (res.code == 200) {
            // 添加分类成功，为分类设置默认的排序值，并将其添加到分类列表中
            res.data.sort = 0 ;
            this.cates.push(res.data);
            // 添加分类成功后，若之前有提示，则隐藏错误提示信息
            $(".alert-danger").addClass("d-none");
            // 将添加分类的输入框重置为空
            this.cateName = "";
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      },
      del(index,id) {
        delCate(id).then(res => {
          if (res.code == 200) {
            // 删除数据库中的分类成功，同时删除页面中展示的对应分类
            this.cates.splice(index,1);
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      },
      edit(index){
        editCate(this.cates[index]).then(res => {
          if (res.code == 200) {
            list().then(res => {
              this.cates = res.data;
            });
          } else {
            this.msg = res.msg;
            $(".alert-danger")
                .removeClass("d-none")
                .addClass("d-show");
          }
        });
      }
    }
  }
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