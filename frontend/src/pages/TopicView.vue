<template>
  <div class="row">
    <div class="col-8">
      <Topic />
      <reply />
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
  import Topic from "../components/Topic";
  import Reply from "../components/Reply";
  import BestTopic from "../components/BestTopic";
  import NewestTopic from "../components/NewestTopic";

  export default {
    name: "TopicView",
    components: {NewestTopic, BestTopic, Reply, Topic, UserInfo},
    mounted () {
      console.info('topicPage', this.$route.params.id)
    }
  }
</script>

<style scoped>

</style>