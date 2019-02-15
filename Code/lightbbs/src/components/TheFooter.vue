<template>
  <div class="footer bg-light">
      {{msg}}
  </div>
</template>
<script>
export default {
  name: "TheFooter",
  data() {
    return {
      msg:"@此网站仅用于学习使用"
    };
  }
};
</script>
<style scoped>
.footer{
  text-align: center;
  font-size: 12px;
  position: relative;
  margin-top: -80px;/*等于footer的高度*/
  height: 80px;
  line-height: 80px;
  clear:both;
}
</style>

