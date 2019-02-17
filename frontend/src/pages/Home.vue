<template>
  <div class="row">
    <div class="col-8">
      <ListTopic />
    </div>
    <div class="col-4">
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