<template>
  <div id="app">
    <TheHeader />
    <div class="container">
      <router-view/>
    </div>
    <TheFooter />
  </div>
</template>

<script>
  import TheHeader from '@/components/TheHeader'
  import TheFooter from '@/components/TheFooter'

  export default {
    name: 'App',
    components: {TheFooter, TheHeader}
  }
</script>

<style>
#app {
  font-family: 'Microsoft YaHei', '微软雅黑', 'Microsoft Sans Serif', 'Avenir', Helvetica, Arial, sans-serif;
  /*-webkit-font-smoothing: antialiased;*/
  /*-moz-osx-font-smoothing: grayscale;*/
  /*text-align: center;*/
  /*color: #2c3e50;*/
}
.container {
  margin-top: 20px;
}
</style>
