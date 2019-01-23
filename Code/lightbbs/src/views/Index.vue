<template>
  <div>
    这是首页内容
  </div>
</template>
<script>
export default {
  name: "Index",
  data() {
    return {
      user_name: "",
      user_id: "",
    };
  }
};
</script>
<style scoped>
.header-logo {
  text-align: left;
  color: #fd7e14;
  margin-right: 20px;
  margin-top: 10px;
}
.header-btn {
  text-align: right;
}
.header-collapse-menu {
  font-size: 12px;
}
@media (max-width: 767px) {
}
</style>

