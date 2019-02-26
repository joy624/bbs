<template>
  <div class="row">
    <div class="col-md-12 col-lg-8">
      <Topic />
      <reply />
    </div>
    <div class="col-lg-4 d-none d-lg-block">
      <UserInfo />
      <BestTopic />
      <NewestTopic />
    </div>
  </div>
</template>

<script>
  import UserInfo from "@/components/UserInfo";
  import Topic from "@/components/Topic";
  import Reply from "@/components/Reply";
  import BestTopic from "@/components/BestTopic";
  import NewestTopic from "@/components/NewestTopic";

  export default {
    name: "TopicView",
    components: {NewestTopic, BestTopic, Reply, Topic, UserInfo},
    mounted () {
      //console.info('topicPage', this.$route.params.id)
    },
    watch: {
        "$route.params": "loadTopic",
    }
  }
</script>

<style scoped>

</style>