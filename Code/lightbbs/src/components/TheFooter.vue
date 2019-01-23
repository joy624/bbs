<template>
  <div class="fixed-bottom bg-light footer">
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
  height: 80px;
  line-height: 80px;
  text-align: center;
  font-size: 12px;
}
</style>

