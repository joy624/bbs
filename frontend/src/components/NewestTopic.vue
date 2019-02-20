<template>
  <div class="row">
    <div class="col-12">
      <div class="bg-light">
        <div class="card-header">最新发布的话题</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-light" v-for="topic in topics" :key="topic.id">
            <a style="cursor: pointer;" v-if="topic.title.length < 18" @click="gotoTopic(topic.id)">{{ topic.title }}</a>
            <a style="cursor: pointer;" v-else @click="gotoTopic(topic.id)">{{ topic.title.slice(0, 18) }}...</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import {newestTopic} from "../api/topic";

  export default {
    name: "NewestTopic",
    data() {
      return {
        topics: []
      }
    },
    mounted () {
      newestTopic(this.$store.state.cate_active).then(res => {
        if (res.code == 200)
          this.topics = res.data;
      })
    },
    methods: {
      gotoTopic(id) {
        console.info('topic', id)
        // this.$router.push({ name: "Topic", query: { id: id } });
        this.$router.push({ path: "/topic/"+id});
      },
    }
  }
</script>

<style scoped>
  .card-header {
    /*font-weight: bold;*/
    /*font-size: 1.1em;*/
    font-size: 14px;
    line-height: 120%;
    color: #cccccc;
  }
</style>