<template>
  <div class="row">
    <div class="col-md-12 col-lg-8">
      <ListTopic />
    </div>
    <div class="col-lg-4 d-none d-lg-block">
      <UserInfo />
      <BestTopic />
      <NewestTopic />
    </div>
  </div>
</template>

<script>
  import UserInfo from "../components/UserInfo";
  import ListTopic from "../components/ListTopic";
  import BestTopic from "../components/BestTopic";
  import NewestTopic from "../components/NewestTopic";
  export default {
    name: "Home",
    components: {NewestTopic, BestTopic, ListTopic, UserInfo}
  }
</script>

<style scoped>

</style>