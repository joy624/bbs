<template>
  <div class="row">
    <div class="col-12">
      <div class="footer">
        {{msg}}
      </div>
    </div>
  </div>
</template>
<script>
  export default {
    name: "TheFooter",
    data() {
      return {
        msg: "@此网站仅用于学习使用"
      };
    }
  };
</script>
<style scoped>
  .footer {
    text-align: center;
    font-size: 12px;
    position: relative;
    height: 80px;
    line-height: 80px;
    clear: both;
  }
</style>

