<template>
  <div class="loading">
    <video class="loadingVid" autoplay muted loop :src="`${state.URLbase}/loading.mp4`" style="width: calc(100vw - 50px); opacity: .5"></video>
  </div>
</template>

<script>

export default {
  name: 'Loading',
  props: [ 'state' ],
  data(){
    return {
    }
  },
  methods: {
  },
  mounted(){
  }
}
</script>

<style scoped>
  .loading{
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    box-sizing: border-box;
    padding: 100px;
    z-index: 10000;
  }
  .loadingVid{
    object-fit: contain;
    min-width: 100%;
    min-height: 100%;
  }
</style>
