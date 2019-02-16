<template>
  <div class="row">
    <div class="col-8">
      <ListTopic />
    </div>
    <div class="col-4">
      <UserInfo />
      <Recommand />
    </div>
  </div>
</template>

<script>
  import UserInfo from "../components/UserInfo";
  import Recommand from "../components/Recommand";
  import ListTopic from "../components/ListTopic";
  export default {
    name: "Home",
    components: {ListTopic, Recommand, UserInfo}
  }
</script>

<style scoped>

</style>