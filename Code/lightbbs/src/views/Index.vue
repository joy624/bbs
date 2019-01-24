<template>
  <div class="bg-light">
    <div class="sidebar">
      <div></div>
      <div></div>
      <div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">Cras justo odio</li>
          <li class="list-group-item">Dapibus ac facilisis in</li>
          <li class="list-group-item">Morbi leo risus</li>
          <li class="list-group-item">Porta ac consectetur ac</li>
          <li class="list-group-item">
            Vestibulum at eros
            <span class="badge badge-primary badge-pill">14</span>
          </li>
        </ul>
      </div>
      <div></div>
    </div>
    <div class="main">
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <!--<li class="nav-item">
          <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</a>
        </li>-->
        <li class="nav-item" v-for="item in items">
          <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{ item.name }}</a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
      </div>
    </div>
  </div>
</template>
<script>
import { list } from "@/api/topic";
export default {
  name: "Index",
  data () {
    return {
      items:""
    }
  },
  mounted () {
    list().then(res => {
	    this.items = res.data
    });
  }
};
</script>
<style scoped>
.main {
  width:70%;
  margin-right: 4.5rem;
  border-radius: 0.25rem;
}
.sidebar {
  float: right;
  width: 25%;
  border-radius: 0.25rem;
}
</style>

