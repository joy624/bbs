<template>
  <div class="row">
    <div class="col-8">
      <Topic />
      <reply />
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
  import Topic from "../components/Topic";
  import Reply from "../components/Reply";

  export default {
    name: "TopicView",
    components: {Reply, Topic, Recommand, UserInfo}
  }
</script>

<style scoped>

</style>