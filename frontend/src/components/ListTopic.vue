<template>
  <div>
    <div class="main bg-light">
      <ul class="nav nav-pills">
        <li class="nav-item bbs-cate-list" v-for="cate in $store.state.cates">
          <a
              v-if="cate.id == $store.state.cate_active"
              class="nav-link active"
              @click="changeCate(cate.id)"
          >{{ cate.name }}</a>
          <a v-else class="nav-link" @click="changeCate(cate.id)">{{ cate.name }}</a>
        </li>
      </ul>
      <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="topic in $store.state.topics">
          <div class="row">
            <div clas="col-2 col-md-2">
              <img
                  :src="'http://my.test.tp/'+topic.user.img_url"
                  alt
                  class="rounded-circle topic-img"
              >
            </div>
            <div clas="col-8 col-md-10">
              <div class="row">
                <h5 class="card-title" style="font-size: 16px;">
                  <a style="cursor: pointer;" @click="gotoTopic(topic.id)">{{ topic.title }}</a>
                </h5>
              </div>
              <div class="row">
                <span class="small">
                  <strong>{{ topic.user.name}}</strong>
                  &nbsp;•&nbsp; <span class="d-none d-md-inline">{{ topic.update_time}}&nbsp;•&nbsp;</span>  点赞数:
                  <strong>{{ topic.likenum}}</strong> &nbsp;•&nbsp; 点击数：
                  <strong>{{ topic.hits }}</strong>
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <el-pagination v-show="$store.state.topic_page_count > 1"
       background
       layout="prev, pager, next"
       :page-count="$store.state.topic_page_count"
       :current-page="$store.state.topic_page_current"
       @current-change="changeTopicPage"
    >
    </el-pagination>
  </div>
</template>

<script>
  import { mapActions } from "vuex";

  export default {
    name: "ListTopic",
    mounted () {
      this.$store.dispatch("loadCates");
      this.$store.dispatch("loadTopics", this.$store.state.cate_active);
    },
    methods: {
      gotoTopic(id) {
        this.$router.push({ path: "/topic/" + id });
      },
      ...mapActions(["changeCate", 'changeTopicPage'])
    }
  }
</script>

<style scoped>
  .bbs-cate-list {
    cursor: pointer;
  }
  .bbs-cate-list .active:hover {
    color: #ffffff;
  }
  .topic-img {
    width: 44px;
    height: 44px;
    margin-right: 25px;
  }

  .topic-model .list-group {
    list-style: decimal inside;
  }
  .topic-model .list-group-item {
    display: list-item;
  }
</style>