<template>
  <div>
    <TheSidebar></TheSidebar>
    <div class="main bg-light">
      <ul class="nav nav-pills flex-column flex-sm-row">
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
            <div clas="col">
              <img
                :src="'http://my.test.tp/'+topic.user.img_url"
                alt
                class="rounded-circle topic-img"
              >
            </div>
            <div clas="col">
              <div class="row">
                <h5 class="card-title">
                  <a href="#" @click="gotoTopic(topic.id)">{{ topic.title }}</a>
                </h5>
              </div>
              <div class="row">
                <span class="small">
                  <strong>{{ topic.user.name}}</strong>
                  &nbsp;•&nbsp; {{ topic.update_time}} &nbsp;•&nbsp; 点赞数:
                  <strong>{{ topic.likenum}}</strong> &nbsp;•&nbsp; 点击数：
                  <strong>{{ topic.hits }}</strong>
                </span>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="bbs-pagination">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li class="page-item" v-for="page in pages">
            <a class="page-link" href="#" @click="pageTopic(page)">{{ page }}</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script>
import TheSidebar from "@/components/TheSidebar";
import { list } from "@/api/cate";
import { index } from "@/api/topic";
import { mapActions } from "vuex";
import { getTopicTotal } from "@/api/topic";

export default {
  name: "Index",
  components: {
    TheSidebar
  },
  data() {
    return {
      topics: "",
      maxnum: 2,
      topic_total: 0,
      pages: 1
    };
  },
  mounted() {
    this.$store.dispatch("loadCates");
    this.$store.dispatch("loadTopics", this.$store.state.cate_active);
    getTopicTotal(this.$store.state.cate_active).then(res => {
      this.topic_total = res.data;
      this.pages = Math.ceil(this.topic_total / this.maxnum);
    });
  },
  methods: {
    gotoTopic(id) {
      this.$router.push({ name: "Topic", query: { id: id } });
    },
    pageTopic(page) {
      console.log(page);
    },
    ...mapActions(["changeCate"])
  }
};
</script>
<style scoped>
.bbs-cate-list {
  cursor: pointer;
}
.bbs-cate-list .active:hover {
  color: #ffffff;
}
.bbs-pagination{
  margin-top: 20px;
}

.main {
  width: 70%;
  margin-right: 4.5rem;
  border-radius: 0.25rem;
}
.sidebar {
  float: right;
  width: 29%;
  border-radius: 0.25rem;
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
.user-info {
  width: 80px;
  text-align: center;
  margin-top: 10px;
}
.user-opt {
  margin-left: 20px;
  margin-top: 10px;
}
.topic-title {
  color: #778087;
}
.cate-manager {
  margin-bottom: 10px;
}
</style>

