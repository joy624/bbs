<template>
  <div class="row">
    <div class="col-12">
      <div class="bg-light mb8">
        <div class="card-header">最受欢迎的话题</div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-light" v-for="topic in topics" :key="topic.id">
            <a style="cursor: pointer;" v-if="topic.title.length < 12 " @click="gotoTopic(topic.id)">{{ topic.title }}</a>
            <a style="cursor: pointer;" v-else @click="gotoTopic(topic.id)">{{ topic.title.slice(0, 12) }}...</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import {bestTopic} from "../api/topic";

  export default {
    name: "BestTopic",
    data() {
      return {
        topics: []
      }
    },
    mounted () {
      bestTopic().then(res => {
        if (res.code == 200)
          this.topics = res.data;
      })
    },
    methods: {
      gotoTopic(id) {
        this.$router.push({ path: "/topic/" + id });
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
  .mb8 {
    margin-bottom: 8px;
  }
</style>