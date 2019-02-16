<template>
  <div id="app">
    <TheHeader></TheHeader>
    <div class="container">
      <router-view></router-view>
    </div>
     <TheFooter></TheFooter>
  </div>
</template>

<script>
import TheHeader from "@/components/TheHeader";
import TheFooter from "@/components/TheFooter";
export default {
  name: "App",
  components: {
    TheHeader,
    TheFooter
  }
};
</script>

<style>
.container {
  margin-top: 20px;
}
</style>
