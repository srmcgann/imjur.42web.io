<template>
  <div
    class="admin"
    ref="admin"
    tabindex="1000"
    v-if="state.showAdmin"
  >
    <button
      @click="close()"
      class="cancelButton"
      title="close this view [ESC]"
    >
      close/cancel
    </button>
    <div class="modalInner" v-html="content"></div>
  </div>
  <button
    class="adminButton"
    @click="state.showAdmin=!state.showAdmin"
    v-html="state.showAdmin ? 'exit admin view' : 'show admin view'"
  >
  </button>
</template>

<script>

export default {
  name: 'Admin',
  props: [ 'state' ],
  components: {
    Pages
  },
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
  .admin{
    height: 70px;
    background: linear-gradient(90deg, #206b, #000b);
    color: #fff;
    width: calc(100vw - 20px);
    left: 0;
    font-size: 20px;
    text-shadow: 2px 2px 2px #000;
    padding-top: 5px;
    line-height: 16px;
    box-sizing: border-box;
    text-align: left;
    padding-left: 5px;
    position: fixed;
    bottom: 0;
  }
  .adminButton{
    position: fixed;
    bottom: 25px;
    left: 50%;
    transform: translate(-50%);
    min-width: 150px;
    background: #f80;
    color: #000;
    border: 2px solid #f00;
  }
</style>
